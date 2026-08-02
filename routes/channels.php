<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


// use App\Models\Ticket;

// Broadcast::channel('ticket.{ticketId}', function ($user, $ticketId) {
//     $ticket = Ticket::find($ticketId);

//     if (! $ticket) {
//         return false;
//     }

//     // Admins can listen to any ticket; users only their own
//     return $user->type === 'admin' || $ticket->user_id === $user->id;
// });


use App\Models\Ticket;

Broadcast::channel('ticket.{ticketId}', function ($user, $ticketId) {
    $ticket = Ticket::find($ticketId);

    if (! $ticket) {
        return false;
    }

    return $user->type === 'admin' || $ticket->user_id === $user->id;
});

Broadcast::channel('ticket.{ticketId}', function ($user, $ticketId) {
    \Log::info('Channel auth attempt', ['user' => $user?->id, 'ticketId' => $ticketId]);

    $ticket = Ticket::find($ticketId);

    if (! $ticket) {
        return false;
    }

    return $user->type === 'admin' || $ticket->user_id === $user->id;
});
