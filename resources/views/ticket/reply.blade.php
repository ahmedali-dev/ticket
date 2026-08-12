<x-app-layout>
    {{-- =========================================================
        HEADER
    ========================================================== --}}

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


            {{-- Back --}}
            <a
                href="{{ route('ticket.index') }}"
                class="inline-flex items-center gap-2 self-start rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >

                <svg
                    class="h-4 w-4 rtl:rotate-180"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>

                {{ __('ticket.back') }}

            </a>

        </div>

    </x-slot>


    @php

        $status = [
            'pending' =>
                'background:var(--amber-bg);color:var(--amber-text);',

            'in_progress' =>
                'background:var(--blue-bg);color:var(--blue-text);',

            'completed' =>
                'background:var(--green-bg);color:var(--green-text);',
        ];


        $status_dot = [
            'pending' =>
                'background:var(--amber-dot);',

            'in_progress' =>
                'background:var(--blue-dot);',

            'completed' =>
                'background:var(--green-dot);',
        ];


        $statuses = [
            'pending',
            'in_progress',
            'completed'
        ];

        $currentIndex =
            array_search(
                $ticket->status,
                $statuses
            );

        $currentIndex =
            $currentIndex === false
                ? 0
                : $currentIndex;

    @endphp


    {{-- =========================================================
        PAGE
    ========================================================== --}}

    <div
        class="df-page"
        style="max-width:820px;"
    >


        {{-- =====================================================
            TICKET DETAILS
        ====================================================== --}}

        <div
            class="df-card"
            style="
                padding:24px;
                margin-bottom:22px;
            "
        >

            <div
                style="
                    display:flex;
                    justify-content:space-between;
                    align-items:flex-start;
                    gap:12px;
                    flex-wrap:wrap;
                "
            >

                <div>

                    {{-- Ticket ID + Company --}}
                    <div class="flex items-center justify-center gap-2">

                        <span class="df-stub df-mono">
                            {{ $ticket->id }}
                        </span>

                        <span class="block mx-2">
                            {{ $ticket->user->company->name ?? '_' }}
                        </span>

                    </div>


                    {{-- Title --}}
                    <h1
                        class="df-display"
                        style="
                            font-size:22px;
                            font-weight:700;
                            margin:10px 0 6px;
                        "
                    >
                        {{ $ticket->titile }}
                    </h1>


                    {{-- Created --}}
                    <div
                        class="df-mono"
                        style="
                            color:var(--text-muted);
                            font-size:13px;
                        "
                    >
                        {{ __('ticket.created') }}
                        {{ $ticket->created_at->format('y/M/d h:m') }}
                    </div>

                </div>


                {{-- =================================================
                    STATUS BADGE
                ================================================== --}}

                <span
                    class="df-badge df-badge-open"
                    style="{{ $status[$ticket->status] ?? '' }}"
                >

                    <span
                        class="df-badge-dot"
                        style="{{ $status_dot[$ticket->status] ?? '' }}"
                    ></span>

                    {{ __('ticket.' . $ticket->status) }}

                </span>

            </div>


            {{-- =================================================
                PROGRESS
            ================================================== --}}

            <div id="stepperSlot" class="mt-6">

                <div class="df-stepper">

                    @foreach ($statuses as $index => $statusName)

                        @php
                            $isDone = $index < $currentIndex;
                            $isCurrent = $index === $currentIndex;
                            $isActive = $index <= $currentIndex;
                        @endphp

                        <div class="df-step">

                            <div
                                class="df-step-line
                    {{ $index > 0 && $isActive ? 'filled' : '' }}"
                            ></div>

                            <div
                                class="df-step-dot
                    {{ $isDone
                        ? 'done'
                        : ($isCurrent ? 'current' : '')
                    }}"
                            >

                                @if ($isDone)

                                    <svg
                                        class="ico"
                                        style="width:14px;height:14px"
                                        viewBox="0 0 24 24"
                                    >
                                        <path d="M5 12l5 5L20 7"></path>
                                    </svg>

                                @else

                                    {{ $index + 1 }}

                                @endif

                            </div>

                            <div
                                class="df-step-label
                    {{ $isActive ? 'active' : '' }}"
                            >
                                {{ __('ticket.' . $statusName) }}
                            </div>

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- =================================================
                DESCRIPTION
            ================================================== --}}

            <div
                style="
                    margin-top:16px;
                    padding-top:16px;
                    border-top:1px solid var(--border);
                    font-size:14.5px;
                    line-height:1.7;
                    color:var(--text);
                "
            >

                <p>
                    {{ $ticket->description }}
                </p>

            </div>


            {{-- =================================================
                UPLOADED IMAGES
            ================================================== --}}

            @if ($ticket->media->isNotEmpty())

                <div style="margin-top:20px;">

                    <div
                        class="df-field-label"
                        style="margin-bottom:10px;"
                    >
                        {{ __('ticket.uploaded_images') }}
                    </div>


                    <div
                        class="df-gallery"
                        style="
                            grid-template-columns:
                            repeat(
                                auto-fill,
                                minmax(120px,1fr)
                            );
                        "
                    >

                        @foreach ($ticket->media as $media)

                            <img
                                src="{{ Storage::url($media->path) }}"
                                alt="{{ __('ticket.uploaded_images') }}"
                                class="df-gallery-thumb lightbox-trigger"
                                data-src="{{ Storage::url($media->path) }}"
                                style="
                                    height:100px;
                                    border-radius:10px;
                                    cursor:zoom-in;
                                    border:1px solid var(--border);
                                "
                            >

                        @endforeach

                    </div>

                </div>

            @endif


            {{-- =================================================
                VIEWED BY
            ================================================== --}}

            <div class="df-viewlog">

                <div
                    class="df-field-label"
                    style="margin-bottom:10px;"
                >

                    <svg
                        class="ico"
                        style="
                            width:15px;
                            height:15px;
                        "
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"
                        ></path>

                        <circle
                            cx="12"
                            cy="12"
                            r="3"
                        ></circle>
                    </svg>

                    {{ __('ticket.viewed_by') }}

                </div>


                <div
                    class="df-viewlog-list"
                    id="viewLogList"
                >

                    @foreach ($ticket->ticketView as $view)

                        <div class="df-viewlog-item">

                            <span
                                class="df-viewlog-dot"
                                style="
                                    {{ $view->user->type == 'admin'
                                        ? 'background:var(--blue-dot)'
                                        : 'background:var(--green-dot);'
                                    }}
                                "
                            ></span>


                            <span>

                                <strong style="color:var(--text);">

                                    {{ Auth()->user()->id == $view->user->id
                                        ? __('ticket.my')
                                        : $view->user->name . ' (' . __('ticket.admin') . ')'
                                    }}

                                </strong>

                                {{ __('ticket.opened_this_ticket') }}

                                ·

                                {{ $view->created_at->format('M d, h:i A') }}

                            </span>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- =====================================================
            CONVERSATION
        ====================================================== --}}

        @if ($ticket->reply->isNotEmpty())

            <div
                class="df-card"
                style="
                    padding:20px;
                    margin-bottom:18px;
                    max-height: 40vh;
                "
                id="conversationBox"
            >

                @foreach ($ticket->reply as $reply)

                    <div
                        class="df-chat-row"
                        style="justify-content:flex-start;"
                        data-id="{{ $reply->id }}"
                    >

                        <div
                            class="df-chat-bubble
                            {{ $reply->user->type == 'admin'
                                ? 'df-chat-admin'
                                : 'df-chat-user'
                            }}"
                        >

                            <div class="df-chat-meta">

                                {{ $reply->user->name }}

                                ·

                                {{ $reply->created_at->format('Y-m-d H:i') }}

                            </div>


                            <div>

                                <p>
                                    {{ $reply->body }}
                                </p>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div
                class="df-card"
                style="
                    padding:20px;
                    margin-bottom:18px;
                "
                id="conversationBox"
            ></div>

        @endif


        {{-- =====================================================
            REPLY
        ====================================================== --}}

        <h3
            class="df-display"
            style="
                font-size:16px;
                margin:0 0 12px;
            "
        >
            {{ __('ticket.reply') }}
        </h3>


        <div
            class="df-card"
            style="padding:20px;"
        >

            {{-- Reply Description --}}
            <div style="margin-bottom:22px;">

                <label class="df-field-label">

                    {{ __('ticket.description') }}

                    <span class="df-req">
                        *
                    </span>

                </label>


                <textarea
                    class="df-input w-full h-[10rem]"
                    name="description"
                    id="reply"
                ></textarea>


                @error('description')

                <div class="df-error-text">

                    {{ $message }}

                </div>

                @enderror

            </div>


            {{-- Actions --}}
            <div
                style="
                    display:flex;
                    justify-content:space-between;
                    align-items:center;
                    gap:10px;
                    margin-top:14px;
                    flex-wrap:wrap;
                "
            >

                {{-- Close Ticket --}}
                @if (
                    auth()->user()->type === 'admin'
                    &&
                    $ticket->status !== 'completed'
                )

                    <form
                        action="{{ route('ticket.update', ['ticket' => $ticket]) }}"
                        method="POST"
                    >

                        @csrf

                        <button
                            class="df-btn df-btn-danger"
                            type="submit"
                            style="
                                color:var(--danger);
                                border-color:var(--border);
                            "
                        >
                            {{ __('ticket.close_ticket') }}
                        </button>

                    </form>

                @endif


                <div></div>


                <div
                    style="
                        display:flex;
                        gap:10px;
                    "
                >

                    {{-- Cancel --}}
                    <button
                        class="df-btn df-btn-ghost"
                        type="button"
                        id="cancelReply"
                    >
                        {{ __('ticket.cancel') }}
                    </button>


                    {{-- Send --}}
                    <button
                        class="df-btn df-btn-primary"
                        type="button"
                        id="sendReply"
                    >
                        📤 {{ __('ticket.send_reply') }}
                    </button>

                </div>

            </div>

        </div>

    </div>

        {{-- =====================================================
            JAVASCRIPT VARIABLES
        ====================================================== --}}

        <script>

            window.currentUserId =
                {{ auth()->id() }};

            window.ticketId =
                {{ $ticket->id }};

        </script>


        {{-- =====================================================
            JAVASCRIPT
        ====================================================== --}}

        <script>

            /*
            |--------------------------------------------------------------------------
            | Browser Notifications
            |--------------------------------------------------------------------------
            */

            if (
                'Notification' in window &&
                Notification.permission === 'default'
            ) {

                Notification.requestPermission();

            }


            document.addEventListener(
                'click',
                () => {

                    if (
                        'Notification' in window &&
                        Notification.permission === 'default'
                    ) {

                        Notification.requestPermission();

                    }

                },
                {
                    once: true
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Variables
            |--------------------------------------------------------------------------
            */

            const ticketId =
                {{ $ticket->id }};

            const currentUserName =
                @json(auth()->user()->name);

            const currentUserType =
                @json(auth()->user()->type);

            const csrfToken =
                document.querySelector(
                    'meta[name="csrf-token"]'
                ).content;


            const conversationBox =
                document.getElementById(
                    'conversationBox'
                );

            const replyTextarea =
                document.getElementById(
                    'reply'
                );

            const sendBtn =
                document.getElementById(
                    'sendReply'
                );

            const cancelBtn =
                document.getElementById(
                    'cancelReply'
                );


            /*
            |--------------------------------------------------------------------------
            | Translated JavaScript Text
            |--------------------------------------------------------------------------
            */

            const SEND_BTN_DEFAULT_TEXT =
                '📤 {{ __('ticket.send_reply') }}';

            const SENDING_TEXT =
                '{{ __('ticket.sending') }}';

            const FAILED_TEXT =
                '⚠️ {{ __('ticket.failed_retry') }}';

            const FAILED_MESSAGE =
                '{{ __('ticket.failed_to_send_reply') }}';

            const SOMETHING_WENT_WRONG =
                '{{ __('ticket.something_went_wrong') }}';

            const NEW_REPLY_FROM =
                '{{ __('ticket.new_reply_from') }}';


            /*
            |--------------------------------------------------------------------------
            | Escape HTML
            |--------------------------------------------------------------------------
            */

            function escapeHtml(str) {

                const div =
                    document.createElement('div');

                div.textContent =
                    str ?? '';

                return div.innerHTML;

            }


            /*
            |--------------------------------------------------------------------------
            | Format Date
            |--------------------------------------------------------------------------
            */

            function formatDate(isoString) {

                const d =
                    new Date(isoString);

                const pad =
                    n => String(n).padStart(2, '0');


                return `${d.getFullYear()}-${pad(
                    d.getMonth() + 1
                )}-${pad(
                    d.getDate()
                )} ${pad(
                    d.getHours()
                )}:${pad(
                    d.getMinutes()
                )}`;

            }


            /*
            |--------------------------------------------------------------------------
            | Append Reply
            |--------------------------------------------------------------------------
            */

            function appendReply({
                                     id,
                                     body,
                                     user_name,
                                     user_type,
                                     created_at
                                 }) {

                /*
                |--------------------------------------------------------------------------
                | Prevent Duplicate Messages
                |--------------------------------------------------------------------------
                */

                if (
                    document.querySelector(
                        `[data-id="${id}"]`
                    )
                ) {

                    return;

                }


                const bubbleClass =
                    user_type === 'admin'
                        ? 'df-chat-admin'
                        : 'df-chat-user';


                const row =
                    document.createElement('div');

                row.className =
                    'df-chat-row';

                row.style.justifyContent =
                    'flex-start';

                row.dataset.id =
                    id;


                row.innerHTML = `

                    <div class="df-chat-bubble ${bubbleClass}">

                        <div class="df-chat-meta">

                            ${escapeHtml(user_name)}

                            ·

                            ${created_at}

                        </div>

                        <div>

                            <p>
                                ${escapeHtml(body)}
                            </p>

                        </div>

                    </div>

                `;


                conversationBox.appendChild(row);

                conversationBox.scrollTop =
                    conversationBox.scrollHeight;

            }


            /*
            |--------------------------------------------------------------------------
            | Send Button State
            |--------------------------------------------------------------------------
            */

            function setSendState(state) {

                if (state === 'sending') {

                    sendBtn.disabled =
                        true;

                    sendBtn.textContent =
                        SENDING_TEXT;

                }

                else if (state === 'failed') {

                    sendBtn.disabled =
                        false;

                    sendBtn.textContent =
                        FAILED_TEXT;

                }

                else {

                    sendBtn.disabled =
                        false;

                    sendBtn.textContent =
                        SEND_BTN_DEFAULT_TEXT;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Send Reply
            |--------------------------------------------------------------------------
            */

            sendBtn.addEventListener(
                'click',
                async () => {

                    const body =
                        replyTextarea.value.trim();


                    if (!body) {

                        return;

                    }


                    setSendState('sending');


                    try {

                        const res =
                            await fetch(
                                `/reply/${ticketId}`,
                                {
                                    method: 'POST',

                                    headers: {
                                        'Content-Type':
                                            'application/json',

                                        'X-CSRF-TOKEN':
                                        csrfToken,

                                        'Accept':
                                            'application/json',
                                    },

                                    body:
                                        JSON.stringify({
                                            description:
                                            body
                                        })
                                }
                            );


                        if (!res.ok) {

                            const err =
                                await res
                                    .json()
                                    .catch(
                                        () => ({})
                                    );


                            setSendState(
                                'failed'
                            );


                            alert(
                                err.message ||
                                FAILED_MESSAGE
                            );


                            return;

                        }


                        const reply =
                            await res.json();


                        appendReply({

                            id:
                            reply.id,

                            body:
                            reply.body,

                            user_name:
                            currentUserName,

                            user_type:
                            currentUserType,

                            created_at:
                            reply.created_at,

                        });


                        replyTextarea.value =
                            '';


                        setSendState(
                            'idle'
                        );


                    }

                    catch (e) {

                        console.error(
                            'Send reply error:',
                            e
                        );


                        setSendState(
                            'failed'
                        );


                        alert(
                            SOMETHING_WENT_WRONG
                        );

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Cancel Reply
            |--------------------------------------------------------------------------
            */

            cancelBtn.addEventListener(
                'click',
                () => {

                    replyTextarea.value =
                        '';

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Laravel Echo
            |--------------------------------------------------------------------------
            */

            document.addEventListener(
                'DOMContentLoaded',
                () => {

                    window.Echo
                        .private(
                            `ticket.${window.ticketId}`
                        )
                        .listen(
                            '.send.message',
                            (e) => {

                                const reply =
                                    e.message;


                                if (
                                    !conversationBox
                                ) {

                                    return;

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Prevent Duplicate Messages
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    document.querySelector(
                                        `[data-id="${reply.id}"]`
                                    )
                                ) {

                                    return;

                                }


                                const bubbleClass =
                                    reply.user.type === 'admin'
                                        ? 'df-chat-admin'
                                        : 'df-chat-user';


                                const row =
                                    document.createElement(
                                        'div'
                                    );

                                row.className =
                                    'df-chat-row';

                                row.style.justifyContent =
                                    'flex-start';

                                row.dataset.id =
                                    reply.id;


                                row.innerHTML = `

                                    <div
                                        class="df-chat-bubble ${bubbleClass}"
                                    >

                                        <div class="df-chat-meta">

                                            ${escapeHtml(
                                    reply.user.name
                                )}

                                            ·

                                            ${formatDate(
                                    reply.created_at
                                )}

                                        </div>

                                        <div>

                                            <p>
                                                ${escapeHtml(
                                    reply.body
                                )}
                                            </p>

                                        </div>

                                    </div>

                                `;


                                conversationBox.appendChild(
                                    row
                                );


                                conversationBox.scrollTop =
                                    conversationBox.scrollHeight;


                                /*
                                |--------------------------------------------------------------------------
                                | Don't Notify Sender
                                |--------------------------------------------------------------------------
                                */

                                const currentUserId =
                                    window.currentUserId ??
                                    null;


                                if (
                                    currentUserId &&
                                    reply.user_id ===
                                    currentUserId
                                ) {

                                    return;

                                }


                                notify(reply);

                            }
                        );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Browser Notification
            |--------------------------------------------------------------------------
            */

            function notify(reply) {

                if (
                    !('Notification' in window) ||
                    Notification.permission !==
                    'granted'
                ) {

                    return;

                }


                if (
                    document.visibilityState ===
                    'visible'
                ) {

                    return;

                }


                const notification =
                    new Notification(
                        `${NEW_REPLY_FROM} ${reply.user.name}`,
                        {
                            body:
                                reply.body.length > 120
                                    ? reply.body.slice(
                                    0,
                                    120
                                ) + '…'
                                    : reply.body,

                            tag:
                                `reply-${reply.id}`,
                        }
                    );


                notification.onclick =
                    () => {

                        window.focus();

                        notification.close();

                    };

            }

        </script>

</x-app-layout>
