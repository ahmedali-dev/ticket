<?php

namespace App\Services;

use App\Jobs\SendTicketWhatsApp;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TicketWhatsAppNotifier
{
    public function created(Ticket $ticket): void
    {
        if (! config('services.whatsapp.enabled')) return;
        $ticket->loadMissing('user');
        $message = $this->message($ticket, 'created', $ticket->user?->name ?? '—');
        $sent = [];
        foreach (User::whereIn('type', [User::TYPE_ADMIN, User::TYPE_SUPERADMIN])->where('status', true)->get() as $admin) {
            $phone = WhatsAppService::normalizePhone((string) $admin->phone);
            if (! $phone) {
                Log::notice('WhatsApp skipped: administrator has no valid phone.', ['user_id' => $admin->id]);
                continue;
            }
            if (isset($sent[$phone])) continue;
            $sent[$phone] = true;
            SendTicketWhatsApp::dispatch($phone, $message, (string) Str::uuid());
        }
    }

    public function statusChanged(Ticket $ticket, User $admin, string $event): void
    {
        if (! config('services.whatsapp.enabled')) return;
        $ticket->loadMissing('user');
        $phone = WhatsAppService::normalizePhone((string) $ticket->user?->phone);
        if (! $phone) {
            Log::notice('WhatsApp skipped: ticket owner has no valid phone.', ['ticket_id' => $ticket->id]);
            return;
        }
        SendTicketWhatsApp::dispatch($phone, $this->message($ticket, $event, $admin->name), (string) Str::uuid());
    }

    public function message(Ticket $ticket, string $event, string $actor): string
    {
        $lines = [];
        foreach (['ar', 'en'] as $locale) {
            $lines[] = trans('whatsapp.'.$event, ['actor' => $actor], $locale);
            $lines[] = trans('whatsapp.number', ['number' => $ticket->id], $locale);
            $lines[] = trans('whatsapp.title', ['title' => $ticket->title], $locale);
            $lines[] = trans('whatsapp.description', ['description' => $ticket->description], $locale);
            $lines[] = '';
        }
        $base = rtrim((string) config('services.whatsapp.ticket_base_url'), '/');
        $lines[] = 'رابط التذكرة / Ticket link:';
        $lines[] = $base.route('ticket.reply', $ticket, false);

        return implode("\n", $lines);
    }
}
