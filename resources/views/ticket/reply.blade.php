<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    reply
                </h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    supporting
                </p>
            </div>
            <a href="{{ route('ticket.index') }}"
                class="inline-flex items-center gap-2 self-start rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>


    @php
        $status = [
            'pending' => 'background:var(--amber-bg);color:var(--amber-text);',
            'in_progress' => 'background:var(--blue-bg);color:var(--blue-text);',
            'completed' => 'background:var(--green-bg);color:var(--green-text);'
        ];

        $status_dot = [
            'pending' => 'background:var(--amber-dot);',
            'in_progress' => 'background:var(--blue-dot);',
            'completed' => 'background:color:var(--green-dot);'
        ];
    @endphp

    <div class="df-page" style="max-width:820px;">


        <!-- Ticket Details -->
        <div class="df-card" style="padding:24px;margin-bottom:22px;">

            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;">

                <div>
                    <span class="df-stub df-mono">{{ $ticket->id }}</span>

                    <h1 class="df-display" style="font-size:22px;font-weight:700;margin:10px 0 6px;">
                        {{ $ticket->titile }}
                    </h1>

                    <div class="df-mono" style="color:var(--text-muted);font-size:13px;">
                        Created {{ $ticket->created_at->format('y/M/d h:m') }}
                    </div>
                </div>

                <!-- Status Badge -->
                <span class="df-badge df-badge-open" style="{{ $status[$ticket->status] }}">
                    <span class="df-badge-dot" style="{{ $status_dot[$ticket->status] }}"></span>
                    {{ Str::replace('_', " ", $ticket->status) }}
                </span>



            </div>

            <!-- Progress -->
            @php
                $statuses = ['pending', 'in_progress', 'completed'];
                $currentIndex = array_search($ticket->status, $statuses);
                $currentIndex = $currentIndex === false ? 0 : $currentIndex;
            @endphp

            <div id="stepperSlot">
                <div class="df-stepper">
                    @foreach ($statuses as $index => $status)
                        @php
                            $isDone = $index < $currentIndex;
                            $isCurrent = $index === $currentIndex;
                            $isActive = $index <= $currentIndex; // used for label styling
                        @endphp

                        <div class="df-step">
                            <div class="df-step-line {{ $index > 0 && $isActive ? 'filled' : '' }}"></div>

                            <div class="df-step-dot {{ $isDone ? 'done' : ($isCurrent ? 'current' : '') }}">
                                @if ($isDone)
                                    <svg class="ico" style="width:14px;height:14px" viewBox="0 0 24 24">
                                        <path d="M5 12l5 5L20 7"></path>
                                    </svg>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </div>

                            <div class="df-step-label {{ $isActive ? 'active' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @dd($ticket->ticketView)

            <!-- Description -->
            <div
                style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);font-size:14.5px;line-height:1.7;color:var(--text);">
                <p> {{ $ticket->description }}.</p>
            </div>



            <!-- Uploaded Images -->
            <div style="margin-top:20px;">
                <div class="df-field-label" style="margin-bottom:10px;">Uploaded Images</div>
                <div class="df-gallery" style="grid-template-columns:repeat(auto-fill, minmax(120px,1fr));">
                    @foreach ($ticket->media as $media)
                        <img src="{{ Storage::url($media->path) }}" alt="screenshot-error.png"
                            class="df-gallery-thumb lightbox-trigger" data-src="https://picsum.photos/seed/t1042a/400/300"
                            style="height:100px;border-radius:10px;cursor:zoom-in;border:1px solid var(--border);">
                    @endforeach
                </div>
            </div>

            <div class="df-viewlog">
                <div class="df-field-label" style="margin-bottom:10px;"><svg class="ico" style="width:15px;height:15px"
                        viewBox="0 0 24 24">
                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg> Viewed By</div>
                <div class="df-viewlog-list" id="viewLogList">
                    <div class="df-viewlog-item">
                        <span class="df-viewlog-dot" style="background:var(--green-dot);"></span>
                        <span><strong style="color:var(--text);">Customer</strong> opened this ticket · Jul 27, 10:22
                            PM</span>
                    </div>
                    <div class="df-viewlog-item">
                        <span class="df-viewlog-dot" style="background:var(--green-dot);"></span>
                        <span><strong style="color:var(--text);">Customer</strong> opened this ticket · Jul 27, 10:12
                            PM</span>
                    </div>
                    <div class="df-viewlog-item">
                        <span class="df-viewlog-dot" style="background:var(--blue-dot);"></span>
                        <span><strong style="color:var(--text);">Admin</strong> opened this ticket · Jul 27, 10:12
                            PM</span>
                    </div>
                    <div class="df-viewlog-item">
                        <span class="df-viewlog-dot" style="background:var(--green-dot);"></span>
                        <span><strong style="color:var(--text);">Customer</strong> opened this ticket · Jul 27, 10:12
                            PM</span>
                    </div>
                    <div class="df-viewlog-item">
                        <span class="df-viewlog-dot" style="background:var(--green-dot);"></span>
                        <span><strong style="color:var(--text);">Customer</strong> opened this ticket · Jul 27, 06:46
                            PM</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="df-card" style="padding:20px;margin-bottom:18px;" id="conversationBox">

            <div class="df-chat-row" style="justify-content:flex-end;">
                <div class="df-chat-bubble df-chat-user">
                    <div class="df-chat-meta">User · 2026-07-22 09:14</div>
                    <div>
                        <p>Any update on this? It's blocking me from logging in at all.</p>
                    </div>
                </div>
            </div>
            <div class="df-chat-row" style="justify-content:flex-start;">
                <div class="df-chat-bubble df-chat-admin">
                    <div class="df-chat-meta">Admin · 2026-07-22 11:02</div>
                    <div>
                        <p>Thanks for flagging — we've reproduced the issue on iOS 18 and are pushing a fix. Should be
                            resolved within 24 hours.</p>
                    </div>
                </div>
            </div>
            <div class="df-chat-row" style="justify-content:flex-end;">
                <div class="df-chat-bubble df-chat-user">
                    <div class="df-chat-meta">You · Jul 27, 10:12 PM</div>
                    <div>asdfasdf</div>
                </div>
            </div>
        </div>

        <!-- Reply -->
        <h3 class="df-display" style="font-size:16px;margin:0 0 12px;">
            Reply
        </h3>

        <div class="df-card" style="padding:20px;">


            <!-- Editor -->
            <!-- Description -->
            <div style="margin-bottom:22px;">
                <label class="df-field-label">
                    Description <span class="df-req">*</span>
                </label>

                <textarea class="df-input w-full h-[10rem]" name="description" id=""></textarea>
                {{-- <div name="description" id="editor"></div> --}}
                @error('description')
                    <div class="df-error-text" id="fileError">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Buttons -->
            <div
                style="display:flex;justify-content:space-between;align-items:center;gap:10px;margin-top:14px;flex-wrap:wrap;">

                <button class="df-btn df-btn-danger" type="button"
                    style="color:var(--danger);border-color:var(--border);">
                    Close Ticket
                </button>

                <div style="display:flex;gap:10px;">

                    <button class="df-btn df-btn-ghost" type="button">
                        Cancel
                    </button>

                    <button class="df-btn df-btn-primary" type="button">
                        📤 Send Reply
                    </button>

                </div>

            </div>

        </div>



    </div>

    <script>

        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
</x-app-layout>