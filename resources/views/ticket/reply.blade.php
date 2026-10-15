<x-app-layout>


    @php
        $statusStyles = [
            'pending'     => 'background:var(--amber-bg);color:var(--amber-text);',
            'in_progress' => 'background:var(--blue-bg);color:var(--blue-text);',
            'completed'   => 'background:var(--green-bg);color:var(--green-text);',
        ];

        $statusDotStyles = [
            'pending'     => 'background:var(--amber-dot);',
            'in_progress' => 'background:var(--blue-dot);',
            'completed'   => 'background:var(--green-dot);',
        ];

        // Build once here instead of string-concatenating in the view.
        $whatsappNumber = $ticket->user?->phone
            ? preg_replace('/\D/', '', $ticket->user->phone)
            : null;
    @endphp

    <div class="df-page ticket-detail-page">

        <nav class="ticket-detail-breadcrumb" aria-label="{{ __('ticket.back') }}">
            <a href="{{ route('ticket.index') }}">{{ __('ticket.tickets') }}</a>
            <span aria-hidden="true"> / </span><span>#{{ $ticket->id }}</span>
        </nav>

        <header class="ticket-workspace-heading">
            <div>
                <p class="ticket-workspace-kicker">{{ __('ticket.ticket_details') }} · #{{ $ticket->id }}</p>
                <h1>{{ $ticket->title }}</h1>
                <p>{{ __('ticket.created') }} {{ $ticket->created_at->format('M d, Y · H:i') }}</p>
            </div>
            <span class="df-badge" style="{{ $statusStyles[$ticket->status] ?? '' }}">
                <span class="df-badge-dot" style="{{ $statusDotStyles[$ticket->status] ?? '' }}"></span>
                {{ __('ticket.' . $ticket->status) }}
            </span>
        </header>

        {{-- ============================= TICKET DETAILS ============================= --}}
        <section class="df-card ticket-details-panel" aria-label="{{ __('ticket.ticket_details') }}">

            <div class="ticket-panel-heading"><h2>{{ __('ticket.ticket_details') }}</h2><span>#{{ $ticket->id }}</span></div>
            <dl class="ticket-owner-grid">
                <div><dt>{{ __('ticket.username') }}</dt><dd>{{ $ticket->user?->name ?? '—' }}</dd></div>
                <div><dt>{{ __('ticket.company') }}</dt><dd>{{ $ticket->user?->company?->name ?? '—' }}</dd></div>
                <div><dt>{{ __('ticket.phone') }}</dt><dd>{{ $ticket->user?->phone ?? '—' }}</dd></div>
                <div><dt>{{ __('ticket.created') }}</dt><dd>{{ $ticket->created_at->format('M d · H:i') }}</dd></div>
            </dl>
            @if ($whatsappNumber)
                <a href="https://wa.me/{{ $whatsappNumber }}" class="ticket-whatsapp-link">WhatsApp ↗</a>
            @endif


            {{-- Description --}}
            <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--border);">
                <h3 class="text-xl font-bold my-3">{{ __('ticket.description') }}</h3>
                <p class="ticket-detail-description">{{ $ticket->description }}</p>
            </div>

            {{-- Uploaded images --}}
            @if ($ticket->media->isNotEmpty())
                <div style="margin-top:20px;">
                    <div class="df-field-label" style="margin-bottom:10px;">
                        {{ __('ticket.uploaded_images') }}
                    </div>

                    <div class="df-gallery" style="grid-template-columns:repeat(auto-fill,minmax(120px,1fr));">
                        @foreach ($ticket->media as $media)
                            <div class="ticket-attachment-row">
                                <span class="ticket-attachment-type">{{ strtoupper(pathinfo($media->path, PATHINFO_EXTENSION)) }}</span>
                                <div class="ticket-attachment-copy">
                                    <a href="{{ Storage::disk('public')->url($media->path) }}" target="_blank" rel="noopener">{{ __('ticket.attachments') }} {{ $loop->iteration }} ↗</a>
                                    <small>{{ $media->size }} · {{ $media->type }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <ol class="ticket-compact-progress" aria-label="{{ __('ticket.progress') }}">
                @foreach (['pending', 'in_progress', 'completed'] as $stage)
                    <li class="{{ $ticket->status === 'completed' ? 'is-complete' : ($ticket->status === $stage ? 'is-current' : '') }}">
                        <span aria-hidden="true">{{ $ticket->status === 'completed' ? '✓' : ($ticket->status === $stage ? '●' : '○') }}</span>
                        {{ __('ticket.' . $stage) }}
                    </li>
                @endforeach
            </ol>

            {{-- Viewed by --}}
            <div class="df-viewlog">
                <div class="df-field-label" style="margin-bottom:10px;">
                    <svg class="ico" style="width:15px; height:15px;" viewBox="0 0 24 24">
                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    {{ __('ticket.viewed_by') }}
                </div>

                <div class="df-viewlog-list">
                    @foreach ($ticket->ticketView as $view)
                        <div class="df-viewlog-item">
                            <span
                                class="df-viewlog-dot"
                                style="{{ $view->user?->type === 'admin' ? 'background:var(--blue-dot)' : 'background:var(--green-dot);' }}"
                            ></span>
                            <span>
                                <strong style="color:var(--text);">
                                    {{ auth()->id() === $view->user?->id
                                        ? __('ticket.my')
                                        : ($view->user?->name ?? '—') }}
                                </strong>
                                {{ __('ticket.opened_this_ticket') }}
                                &middot;
                                {{ $view->created_at->format('M d, h:i A') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ============================= CONVERSATION ============================= --}}
        <section class="ticket-discussion-panel df-card" aria-labelledby="ticket-conversation-title">
        <div class="ticket-panel-heading"><h2 id="ticket-conversation-title">{{ __('ticket.conversation') }}</h2><span id="ticket-message-count">{{ $ticket->reply->count() }} {{ __('ticket.messages') }}</span></div>
        @include('ticket.conversation', ['replies' => $ticket->reply])
        {{-- ============================= REPLY FORM ============================= --}}
        @include('ticket.reply-form', ['ticket' => $ticket])
        </section>
    </div>

    {{-- Config passed to the JS module as data, not scattered inline <script> blocks --}}
    <script id="ticketConfig" type="application/json">
        {!! json_encode([
            'ticketId'        => $ticket->id,
            'replyStoreUrl'   => route('reply.store', $ticket),
            'currentUserId'   => auth()->id(),
            'currentUserName' => auth()->user()->name,
            'currentUserType' => auth()->user()->type,
            'i18n' => [
                'sending'            => __('ticket.sending'),
                'failedRetry'        => '⚠️ ' . __('ticket.failed_retry'),
                'sendReplyDefault'   => __('ticket.send_reply') . ' ↗',
                'failedToSendReply'  => __('ticket.failed_to_send_reply'),
                'somethingWentWrong' => __('ticket.something_went_wrong'),
                'newReplyFrom'       => __('ticket.new_reply_from'),
            ],
        ]) !!}
    </script>

    @vite('resources/js/ticket-chat.js')

</x-app-layout>
