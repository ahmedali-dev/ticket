<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('ticket.reply') }}
                </h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('ticket.supporting') }}
                </p>
            </div>

            <a
                href="{{ route('ticket.index') }}"
                class="inline-flex items-center gap-2 self-start rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >
                <svg class="h-4 w-4 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ __('ticket.back') }}
            </a>
        </div>
    </x-slot>

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
        $whatsappNumber = $ticket->user->phone
            ? preg_replace('/\D/', '', $ticket->user->phone)
            : null;
    @endphp

    <div class="df-page" style="max-width:820px;">

        {{-- ============================= TICKET DETAILS ============================= --}}
        <div class="df-card" style="padding:24px; margin-bottom:22px;">

            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap;">
                <div>
                    <div class="flex items-center justify-start gap-2">
                        <span class="df-stub df-mono">{{ $ticket->id }}</span>

                    </div>
                    <h1 class="df-display" style="font-size:22px; font-weight:700; margin:10px 0 6px;">
                        {{ $ticket->title }}
                    </h1>
                    <div class="flex flex-wrap items-center justify-start gap-3 my-4">
                        <div class="flex flex-wrap gap-2 my-3.5">
                    <span
                        class="inline-flex items-center gap-1.5 text-sm bg-[var(--surface-sunken)] border border-[var(--border)] rounded-full py-1 pl-1 pr-3">
                        <span
                            class="inline-flex items-center justify-center h-5 rounded-full bg-[var(--brand-soft)] text-[var(--brand)] text-[11px] font-bold flex-shrink-0">{{__('ticket.username')}}</span>
                       {{$ticket->user->name}}
                    </span>

                            <span
                                class="inline-flex items-center gap-1.5 text-sm bg-[var(--surface-sunken)] border border-[var(--border)] rounded-full py-1 pl-1 pr-3">
                        <span class="text-[var(--text-faint)] font-medium">{{ __('ticket.phone') }}</span>
                        {{$ticket->user->phone}}
                    </span>

                            <span
                                class="inline-flex items-center gap-1.5 text-sm bg-[var(--surface-sunken)] border border-[var(--border)] rounded-full py-1 pl-1 pr-3">
                        <span class="text-[var(--text-faint)] font-medium">{{__('ticket.company')}}</span>
                        {{$ticket->user->company->name}}
                    </span>

                            <a href="https://wa.me/{{$ticket->user->phone}}"
                               class="inline-flex items-center gap-1.5 text-sm font-semibold text-green-700 bg-green-50 border border-green-200 rounded-full px-3 py-1 no-underline hover:brightness-95">
                                WhatsApp
                            </a>
                        </div>
                    </div>



                    <div class="df-mono" style="color:var(--text-muted); font-size:13px;">
                        {{ __('ticket.created') }}
                        {{ $ticket->created_at->format('y/M/d h:i') }}
                    </div>
                </div>

                <span class="df-badge df-badge-open" style="{{ $statusStyles[$ticket->status] ?? '' }}">
                    <span class="df-badge-dot" style="{{ $statusDotStyles[$ticket->status] ?? '' }}"></span>
                    {{ __('ticket.' . $ticket->status) }}
                </span>
            </div>

            {{-- Progress --}}
            <div class="mt-6">

                @include('ticket.stepper', ['status'=>$ticket->status])
            </div>

            {{-- Description --}}
            <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--border);">
                <h3 class="text-xl font-bold my-3">{{ __('ticket.description') }}</h3>
                <p class="rounded-md mx-3 border p-4 shadow-md"
                   style="font-size:14.5px; line-height:1.7; color:var(--text);">
                    {{ $ticket->description }}
                </p>
            </div>

            {{-- Uploaded images --}}
            @if ($ticket->media->isNotEmpty())
                <div style="margin-top:20px;">
                    <div class="df-field-label" style="margin-bottom:10px;">
                        {{ __('ticket.uploaded_images') }}
                    </div>

                    <div class="df-gallery" style="grid-template-columns:repeat(auto-fill,minmax(120px,1fr));">
                        @foreach ($ticket->media as $media)
                            <img
                                src="{{ Storage::url($media->path) }}"
                                alt="{{ __('ticket.uploaded_image_alt', ['n' => $loop->iteration]) }}"
                                class="df-gallery-thumb lightbox-trigger"
                                data-src="{{ Storage::url($media->path) }}"
                                loading="lazy"
                                style="height:100px; border-radius:10px; cursor:zoom-in; border:1px solid var(--border);"
                            >
                        @endforeach
                    </div>
                </div>
            @endif

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
                                style="{{ $view->user->type === 'admin' ? 'background:var(--blue-dot)' : 'background:var(--green-dot);' }}"
                            ></span>
                            <span>
                                <strong style="color:var(--text);">
                                    {{ auth()->id() === $view->user->id
                                        ? __('ticket.my')
                                        : $view->user->name . ' (' . __('ticket.admin') . ')' }}
                                </strong>
                                {{ __('ticket.opened_this_ticket') }}
                                &middot;
                                {{ $view->created_at->format('M d, h:i A') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ============================= CONVERSATION ============================= --}}
        {{--        <x-ticket-conversation :replies="$ticket->reply" />--}}
        @include('ticket.conversation', ['replies' => $ticket->reply])
        {{-- ============================= REPLY FORM ============================= --}}
        @include('ticket.reply-form', ['ticket' => $ticket])
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
                'sendReplyDefault'   => '📤 ' . __('ticket.send_reply'),
                'failedToSendReply'  => __('ticket.failed_to_send_reply'),
                'somethingWentWrong' => __('ticket.something_went_wrong'),
                'newReplyFrom'       => __('ticket.new_reply_from'),
            ],
        ]) !!}
    </script>

    @vite('resources/js/ticket-chat.js')

</x-app-layout>
