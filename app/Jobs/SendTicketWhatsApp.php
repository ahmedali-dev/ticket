<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendTicketWhatsApp implements ShouldQueue
{
    use Queueable;

    // Do not automatically retry an ambiguous delivery failure.
    public int $tries = 1;
    public int $timeout = 600;

    public function __construct(public string $phone, public string $message, public string $notificationId)
    {
        $this->onConnection('whatsapp')->onQueue('whatsapp');
        $this->afterCommit();
    }

    public function handle(WhatsAppService $whatsapp): void
    {
        $whatsapp->send($this->phone, $this->message, $this->notificationId);
    }

    public function failed(?Throwable $exception): void
    {
        Log::warning('Ticket WhatsApp delivery failed; inspect failed jobs before retrying.', [
            'notification_id' => $this->notificationId,
            'exception' => $exception ? get_class($exception) : null,
        ]);
    }
}
