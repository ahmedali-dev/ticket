{{-- Usage: <x-ticket-reply-form :ticket="$ticket" /> --}}
<h3 class="df-display" style="font-size:16px; margin:0 0 12px;">
    {{ __('ticket.reply') }}
</h3>

<div class="df-card" style="padding:20px;">

    <div style="margin-bottom:22px;">
        <label class="df-field-label" for="reply">
            {{ __('ticket.description') }}
            <span class="df-req">*</span>
        </label>

        <textarea
            class="df-input w-full h-[3rem]"
            name="description"
            id="reply"
            maxlength="2000"
            required
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
                @method('PATCH')
                <input type="hidden" name="status" value="completed">

                <button
                    class="df-btn df-btn-danger"
                    type="submit"
                    style="color:var(--danger); border-color:var(--border);"
                    data-confirm="{{ __('ticket.confirm_close') }}"
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
                📤 {{ __('ticket.send_reply') }}
            </button>
        </div>
    </div>
</div>
