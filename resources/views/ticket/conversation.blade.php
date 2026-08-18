{{--
    Usage: <x-ticket-conversation :replies="$ticket->reply" />
    Renders the message thread and exposes #conversationBox for the JS module to append into.
--}}
<div
    class="df-card"
    id="conversationBox"
    style="padding:20px; margin-bottom:18px; max-height:40vh; overflow-y:auto;"
>
    @forelse ($replies as $reply)
        <div class="df-chat-row" style="justify-content:flex-start;" data-id="{{ $reply->id }}">
            <div class="df-chat-bubble {{ $reply->user->type === 'admin' ? 'df-chat-admin' : 'df-chat-user' }}">
                <div class="df-chat-meta">
                    {{ $reply->user->name }} &middot; {{ $reply->created_at->format('Y-m-d H:i') }}
                </div>
                <div>
                    <p>{{ $reply->body }}</p>
                </div>
            </div>
        </div>
    @empty
        <p class="df-mono" style="color:var(--text-muted); font-size:13px; text-align:center; padding:24px 0;">
            {{ __('ticket.no_replies_yet') }}
        </p>
    @endforelse
</div>
