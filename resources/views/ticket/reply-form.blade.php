{{-- Usage: <x-ticket-reply-form :ticket="$ticket" /> --}}
<div class="df-card ticket-reply-composer" style="padding:20px;">

    <div style="margin-bottom:22px;">
        <label class="df-field-label" for="reply">
            {{ __('ticket.your_reply') }}
        </label>

        <textarea
            class="df-input w-full h-[3rem]"
            name="description"
            id="reply"
            maxlength="2000"
            required
            placeholder="{{ __('ticket.reply_placeholder') }}"
        ></textarea>

        <div id="replyCharCount" class="df-mono" style="color:var(--text-muted); font-size:12px; margin-top:4px; text-align:right;">
            0 / 2000
        </div>

        @error('description')
        <div class="df-error-text">{{ $message }}</div>
        @enderror
    </div>

    <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; margin-top:14px; flex-wrap:wrap;">

        {{-- Close ticket: fixed to actually send a status, plus a confirm step --}}
        @if (auth()->user()->type === 'admin' && $ticket->status !== 'completed')
            <form
                action="{{ route('ticket.update', $ticket) }}"
                method="POST"
                id="closeTicketForm"
            >
                @csrf
                <input type="hidden" name="status" value="completed">

                <button
                    class="df-btn df-btn-danger"
                    type="submit"
                    style="color:var(--danger); border-color:var(--border);"
                    aria-haspopup="dialog" aria-controls="close-ticket-dialog"
                >
                    {{ __('ticket.close_ticket') }}
                </button>
            </form>
        @else
            <div></div>
        @endif

        <div style="display:flex; gap:10px;">
            <button class="df-btn df-btn-ghost" type="button" id="cancelReply">
                {{ __('ticket.cancel') }}
            </button>

            <button class="df-btn df-btn-primary" type="button" id="sendReply" disabled>
                {{ __('ticket.send_reply') }} ↗
            </button>
        </div>
    </div>
</div>

@if (auth()->user()->type === 'admin' && $ticket->status !== 'completed')
    <dialog id="close-ticket-dialog" class="ticket-close-dialog" aria-labelledby="close-ticket-title" aria-describedby="close-ticket-description">
        <div class="ticket-close-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 3 3 5-6"/></svg>
        </div>
        <h2 id="close-ticket-title">{{ __('ticket.close_dialog_title') }}</h2>
        <p id="close-ticket-description">{{ __('ticket.close_dialog_description') }}</p>
        <div class="ticket-close-summary"><span>#{{ $ticket->id }}</span><strong>{{ $ticket->title }}</strong></div>
        <div class="ticket-close-actions">
            <button id="cancelCloseTicket" class="df-btn df-btn-ghost" type="button" autofocus>{{ __('ticket.cancel') }}</button>
            <button id="confirmCloseTicket" class="df-btn df-btn-primary" type="button" data-closing-label="{{ __('ticket.closing') }}">{{ __('ticket.close_ticket') }}</button>
        </div>
    </dialog>
@endif
