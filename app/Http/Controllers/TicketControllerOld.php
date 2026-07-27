<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketReplyRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TicketController extends Controller
{
    /**
     * Display the ticket management dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = $user->isAdmin()
            ? Ticket::query()->with(['user', 'replies.user'])->latest()
            : $user->tickets()->with(['replies.user'])->latest();

        $tickets = $query->get()->map(fn (Ticket $ticket) => [
            'id' => $ticket->id,
            'title' => $ticket->title,
            'description' => $ticket->description,
            'status' => $ticket->status,
            'image_url' => $ticket->image_url,
            'owner' => $ticket->relationLoaded('user') ? $ticket->user?->name : null,
            'date' => $ticket->created_at->format('Y-m-d'),
            'display_date' => $ticket->created_at->translatedFormat('M d, Y'),
            'update_url' => route('ticket.update', $ticket),
            'delete_url' => route('ticket.destroy', $ticket),
            'reply_url' => route('ticket.reply', $ticket),
            'replies_count' => $ticket->replies->count(),
            'replies' => $ticket->replies
                ->sortBy('created_at')
                ->values()
                ->map(fn ($reply) => [
                    'id' => $reply->id,
                    'body' => $reply->body,
                    'author' => $reply->user?->name,
                    'is_admin' => (bool) $reply->user?->isAdmin(),
                    'date' => $reply->created_at->translatedFormat('M d, Y H:i'),
                ]),
        ]);

        return view('ticket.dashborad', [
            'tickets' => $tickets,
            'isAdmin' => $user->isAdmin(),
            'i18n' => [
                'pending' => __('ticket.status_pending'),
                'in_progress' => __('ticket.status_in_progress'),
                'completed' => __('ticket.status_completed'),
                'admin' => __('ticket.admin'),
                'user' => __('ticket.user'),
                'reply_empty' => __('ticket.reply_empty'),
                'original_message' => __('ticket.original_message'),
            ],
        ]);
    }

    /**
     * Show the create ticket form (regular users only).
     */
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()->isAdmin()) {
            return redirect()
                ->route('ticket.index')
                ->with('error', __('ticket.admin_cannot_create'));
        }

        return view('ticket.create');
    }

    /**
     * Store a newly created ticket.
     * Status is always forced to Pending — never accepted from the client.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['title', 'description']);
        $data['status'] = Ticket::STATUS_PENDING;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tickets', 'public');
        }

        $request->user()->tickets()->create($data);

        return redirect()
            ->route('ticket.index')
            ->with('success', __('ticket.created'));
    }

    /**
     * Admin reply to a user ticket.
     */
    public function reply(StoreTicketReplyRequest $request, Ticket $ticket): RedirectResponse
    {
        $ticket->replies()->create([
            'user_id' => $request->user()->id,
            'body' => $request->validated('body'),
        ]);

        // Move pending tickets to in progress when an admin first replies.
        if ($ticket->status === Ticket::STATUS_PENDING) {
            $ticket->update(['status' => Ticket::STATUS_IN_PROGRESS]);
        }

        return redirect()
            ->route('ticket.index')
            ->with('success', __('ticket.replied'));
    }

    /**
     * Update the specified ticket (administrators only).
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $ticket->update($request->validated());

        return redirect()
            ->route('ticket.index')
            ->with('success', __('ticket.updated'));
    }

    /**
     * Remove the specified ticket (administrators only).
     */
    public function destroy(Request $request, Ticket $ticket): RedirectResponse
    {
        abort_unless($request->user()->isAdmin(), 403);

        if ($ticket->image) {
            Storage::disk('public')->delete($ticket->image);
        }

        $ticket->delete();

        return redirect()
            ->route('ticket.index')
            ->with('success', __('ticket.deleted'));
    }
}
