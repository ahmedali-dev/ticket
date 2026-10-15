<?php

namespace Tests\Feature;

use App\Jobs\SendTicketWhatsApp;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketWhatsAppNotifier;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TicketWhatsAppTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        config(['services.whatsapp.enabled' => true, 'services.whatsapp.token' => 'test-secret',
            'services.whatsapp.ticket_base_url' => 'https://tickets.example.com']);
        Queue::fake();
    }

    public function test_new_ticket_notifies_all_active_admins_with_both_languages_and_link(): void
    {
        $owner = User::factory()->create(['status' => true]);
        User::factory()->create(['type' => 'admin', 'phone' => '0501234567', 'status' => true]);
        User::factory()->create(['type' => 'admin', 'phone' => '+966501234568', 'status' => true]);
        User::factory()->create(['type' => 'admin', 'phone' => '0501234569', 'status' => false]);
        $this->actingAs($owner)->post(route('ticket.store'), ['title' => 'Cannot export', 'description' => 'Export fails.'])->assertRedirect(route('ticket.index'));
        $ticket = Ticket::firstOrFail();
        Queue::assertPushed(SendTicketWhatsApp::class, 2);
        Queue::assertPushed(SendTicketWhatsApp::class, fn ($job) => $job->phone === '966501234567'
            && str_contains($job->message, 'تذكرة دعم جديدة') && str_contains($job->message, 'New support ticket')
            && str_contains($job->message, 'Cannot export') && str_contains($job->message, 'Export fails.')
            && str_contains($job->message, "https://tickets.example.com/ticket/{$ticket->id}/reply")
            && $job->connection === 'whatsapp' && $job->queue === 'whatsapp');
    }

    public function test_open_and_close_notify_owner_once_per_transition(): void
    {
        $owner = User::factory()->create(['status' => true, 'phone' => '501234567']);
        $admin = User::factory()->create(['type' => 'admin', 'status' => true]);
        $ticket = $owner->tickets()->create(['title' => 'Help', 'description' => 'Details']);
        $this->actingAs($admin)->get(route('ticket.reply', $ticket))->assertOk();
        $this->get(route('ticket.reply', $ticket))->assertOk();
        Queue::assertPushed(SendTicketWhatsApp::class, 1);
        Queue::assertPushed(SendTicketWhatsApp::class, fn ($job) => $job->phone === '966501234567'
            && str_contains($job->message, 'has opened your ticket') && str_contains($job->message, 'فتح المسؤول'));
        $this->post(route('ticket.update', $ticket))->assertRedirect();
        $this->post(route('ticket.update', $ticket))->assertRedirect();
        Queue::assertPushed(SendTicketWhatsApp::class, 2);
        Queue::assertPushed(SendTicketWhatsApp::class, fn ($job) => str_contains($job->message, 'has closed your ticket') && str_contains($job->message, 'أغلق المسؤول'));
    }

    public function test_owner_opening_ticket_does_not_trigger_admin_notification(): void
    {
        $owner = User::factory()->create(['status' => true, 'phone' => '501234567']);
        $ticket = $owner->tickets()->create(['title' => 'Help', 'description' => 'Details']);
        $this->actingAs($owner)->get(route('ticket.reply', $ticket))->assertOk();
        Queue::assertNothingPushed();
    }

    public function test_invalid_numbers_and_disabled_feature_do_not_queue_messages(): void
    {
        $owner = User::factory()->create(['phone' => '0']);
        $ticket = $owner->tickets()->create(['title' => 'Help', 'description' => 'Details']);
        $admin = User::factory()->create(['type' => 'admin', 'phone' => '0']);
        $notifier = app(TicketWhatsAppNotifier::class);
        $notifier->created($ticket);
        $notifier->statusChanged($ticket, $admin, 'closed');
        config(['services.whatsapp.enabled' => false]);
        $admin->update(['phone' => '501234567']);
        $notifier->created($ticket);
        Queue::assertNothingPushed();
    }

    public function test_bridge_uses_authenticated_requests_and_splits_long_messages(): void
    {
        Http::preventStrayRequests();
        Http::fake(['*' => Http::response(['sent' => true])]);
        app(WhatsAppService::class)->send('966501234567', str_repeat('a', 4000), 'notification-id');
        Http::assertSentCount(2);
        Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer test-secret')
            && $request['idempotencyKey'] === 'notification-id:0' && strlen($request['message']) === 3500);
    }

    public function test_whatsapp_connection_endpoints_are_admin_only(): void
    {
        $owner = User::factory()->create(['status' => true]);
        $this->actingAs($owner)->get('/whatsapp')->assertForbidden();
        $this->get('/whatsapp/status')->assertForbidden();
    }
}
