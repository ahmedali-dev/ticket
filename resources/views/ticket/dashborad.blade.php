@php
    $status = [
        'pending' => 'background:var(--amber-bg);color:var(--amber-text);',
        'in_progress' => 'background:var(--blue-bg);color:var(--blue-text);',
        'completed' => 'background:var(--green-bg);color:var(--green-text);',
    ];

    $status_dot = [
        'pending' => 'background:var(--amber-dot);',
        'in_progress' => 'background:var(--blue-dot);',
        'completed' => 'background:var(--green-dot);',
    ];

    $status_arr = ['all', 'pending', 'in_progress', 'completed'];
@endphp

<x-app-layout>

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <x-slot name="header">

        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('ticket.ticket_management') }}
                </h2>

                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('ticket.search_filter_manage_tickets') }}
                </p>

            </div>

        </div>

    </x-slot>


    {{-- =========================================================
        PAGE
    ========================================================== --}}

    <div id="pageRoot">

        <div class="df-page">

            {{-- =================================================
                PAGE TITLE
            ================================================== --}}

            <div
                style="
                    display:flex;
                    justify-content:space-between;
                    align-items:flex-end;
                    margin-bottom:22px;
                    gap:16px;
                    flex-wrap:wrap;
                "
            >

                <div>

                    <h1
                        class="df-display"
                        style="
                            font-size:26px;
                            font-weight:700;
                            margin:0;
                        "
                    >
                        {{ __('ticket.tickets') }}
                    </h1>

                    <p
                        id="ticketCountLine"
                        style="
                            color:var(--text-muted);
                            font-size:14px;
                            margin:4px 0 0;
                        "
                    ></p>

                </div>

            </div>


            {{-- =================================================
                FILTERS
            ================================================== --}}

            <div
                class="df-filters-row"
                style="
                    display:flex;
                    justify-content:space-between;
                    align-items:center;
                    gap:12px;
                    margin-bottom:18px;
                    flex-wrap:wrap;
                "
            >

                <form
                    action="{{ route('ticket.search') }}"
                    method="POST"
                >

                    @csrf

                    <div
                        class="df-filters-left"
                        style="
                            display:flex;
                            gap:10px;
                            flex:1;
                            flex-wrap:wrap;
                        "
                    >

                        {{-- Search --}}
                        <div
                            style="
                                position:relative;
                                flex:1 1 220px;
                                max-width:320px;
                            "
                        >

                            <input
                                name="search"
                                id="searchInput"
                                class="df-input"
                                style="
                                    width:100%;
                                    padding-inline-start:34px;
                                "
                                placeholder="{{ __('ticket.search_by_id_title_description') }}"
                                value="{{ old('search', request('search')) }}"
                            />

                        </div>


                        {{-- Date --}}
                        <div
                            style="position:relative;"
                        >

                            <input
                                name="date"
                                id="dateInput"
                                type="date"
                                class="df-input"
                                style="
                                    padding-inline-start:34px;
                                "
                                value="{{ old('date', request('date')) }}"
                            />

                        </div>


                        {{-- Status --}}
                        <select
                            id="statusSelect"
                            name="status"
                            class="df-select"
                        >

                            <option
                                value="all"
                                {{ old('status', request('status', 'all')) === 'all' ? 'selected' : '' }}
                            >
                                {{ __('ticket.all') }}
                            </option>

                            @foreach ($status_arr as $s)

                                @if ($s !== 'all')

                                    <option
                                        value="{{ $s }}"
                                        {{ old('status', request('status')) === $s ? 'selected' : '' }}
                                    >
                                        {{ __('ticket.' . $s) }}
                                    </option>

                                @endif

                            @endforeach

                        </select>


                        {{-- Search Button --}}
                        <button
                            type="submit"
                            class="df-btn df-btn-primary"
                            title="{{ __('ticket.search') }}"
                            aria-label="{{ __('ticket.search') }}"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-search-icon lucide-search"
                            >
                                <path d="m21 21-4.34-4.34"/>
                                <circle cx="11" cy="11" r="8"/>
                            </svg>

                        </button>

                    </div>

                </form>


                {{-- Add Ticket --}}
                @if (auth()->user()->type !== 'admin')

                    <a
                        href="{{ route('ticket.create') }}"
                        class="df-btn df-btn-primary"
                        id="addTicketBtn"
                    >
                        {{ __('ticket.add_ticket') }}
                    </a>

                @endif

            </div>


            {{-- =================================================
                TABLE
            ================================================== --}}

            <div id="tableContainer">

                <div class="df-card df-table-wrap">

                    <table class="df-table">

                        <thead>

                        <tr>

                            <th>
                                {{ __('ticket.id') }}
                            </th>

                            <th>
                                {{ __('ticket.company') }}
                            </th>

                            <th>
                                {{ __('ticket.title') }}
                            </th>

                            <th>
                                {{ __('ticket.description') }}
                            </th>


                            {{-- Created Date --}}
                            <th
                                class="sortable"
                                data-sort="date"
                            >

                                    <span
                                        style="
                                            display:inline-flex;
                                            align-items:center;
                                            gap:4px;
                                        "
                                    >

                                        {{ __('ticket.created_date') }}

                                    </span>

                            </th>


                            {{-- Status --}}
                            <th
                                class="sortable"
                                data-sort="status"
                            >

                                    <span
                                        style="
                                            display:inline-flex;
                                            align-items:center;
                                            gap:4px;
                                        "
                                    >

                                        {{ __('ticket.status') }}

                                    </span>

                            </th>


                            <th style="width:60px;"></th>

                        </tr>

                        </thead>


                        {{-- =================================================
                            TABLE BODY
                        ================================================== --}}

                        <tbody>

                        @forelse ($ticket as $t)

                            <tr>

                                {{-- ID --}}
                                <td>

                                        <span class="df-stub df-mono">
                                            {{ $t->id }}
                                        </span>

                                </td>


                                {{-- Company --}}
                                <td>
                                    {{ $t->user->company->name ?? '_' }}
                                </td>


                                {{-- Title --}}
                                <td
                                    style="
                                            font-weight:600;
                                            max-width:220px;
                                        "
                                >
                                    {{ $t->title }}
                                </td>


                                {{-- Description --}}
                                <td
                                    style="
                                            color:var(--text-muted);
                                            max-width:260px;
                                        "
                                >

                                    {{ Str::limit($t->description, 20, '...') }}

                                </td>


                                {{-- Created Date --}}
                                <td
                                    class="df-mono"
                                    style="
                                            color:var(--text-muted);
                                            font-size:13px;
                                        "
                                >

                                    {{ $t->created_at->format('d/m/y h:m') }}

                                </td>


                                {{-- Status --}}
                                <td>

                                        <span
                                            class="df-badge"
                                            style="{{ $status[$t->status] ?? '' }}"
                                        >

                                            <span
                                                class="df-badge-dot"
                                                style="{{ $status_dot[$t->status] ?? '' }}"
                                            ></span>

                                            {{ __('ticket.' . $t->status) }}

                                        </span>

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div
                                        class="df-dropdown"
                                        id="menu-{{ $t->id }}"
                                    >

                                        <button
                                            type="button"
                                            onclick="openMenu(this)"
                                            class="df-icon-btn row-menu-btn"
                                            data-id="{{ $t->id }}"
                                            style="
                                                    width:30px;
                                                    height:30px;
                                                "
                                            aria-label="{{ __('ticket.actions') }}"
                                        >
                                            ⋮
                                        </button>


                                        <div
                                            class="df-dropdown-menu"
                                            data-menu-id="{{ $t->id }}"
                                        >

                                            <a
                                                href="{{ route('ticket.reply', ['ticket' => $t]) }}"
                                                class="df-dropdown-item view-ticket-item"
                                            >
                                                {{ __('ticket.view_ticket') }}
                                            </a>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    style="
                                            text-align:center;
                                            padding:30px;
                                            color:var(--text-muted);
                                        "
                                >
                                    {{ __('ticket.no_tickets_found') }}
                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>


                    {{-- =================================================
                        PAGINATION
                    ================================================== --}}

                    <div
                        class="df-pagination"
                        style="
                            border-top:1px solid var(--border);
                        "
                    >

                        <div class="df-pagination-info">

                            {{ __('ticket.showing') }}

                            {{ $ticket->firstItem() ?? 0 }}

                            –

                            {{ $ticket->lastItem() ?? 0 }}

                            {{ __('ticket.of') }}

                            {{ $ticket->total() }}

                        </div>


                        <div class="df-pagination-controls">

                            {{-- Previous --}}
                            <a
                                href="{{ $ticket->previousPageUrl() }}"
                                class="df-page-btn {{ $ticket->onFirstPage() ? 'disabled' : '' }}"
                                aria-label="{{ __('ticket.previous') }}"
                            >
                                &laquo;
                            </a>


                            {{-- Page Numbers --}}
                            @foreach ($ticket->getUrlRange(1, $ticket->lastPage()) as $page => $url)

                                <a
                                    href="{{ $url }}"
                                    class="df-page-btn {{ $page == $ticket->currentPage() ? 'active' : '' }}"
                                >
                                    {{ $page }}
                                </a>

                            @endforeach


                            {{-- Next --}}
                            <a
                                href="{{ $ticket->nextPageUrl() }}"
                                class="df-page-btn {{ $ticket->hasMorePages() ? '' : 'disabled' }}"
                                aria-label="{{ __('ticket.next') }}"
                            >
                                &raquo;
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}

    <script>

        /*
        |--------------------------------------------------------------------------
        | Open Ticket Row Menu
        |--------------------------------------------------------------------------
        */

        function openMenu(button) {

            // Close all dropdowns
            document
                .querySelectorAll('.df-dropdown-menu')
                .forEach(menu => {

                    menu.classList.remove('open');

                });


            // Get ticket ID
            const ticketId =
                button.dataset.id;


            // Find corresponding dropdown
            const dropdown =
                document.querySelector(
                    `.df-dropdown-menu[data-menu-id="${ticketId}"]`
                );


            // Open dropdown
            if (dropdown) {

                dropdown.classList.add('open');

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Close Dropdown When Clicking Outside
        |--------------------------------------------------------------------------
        */

        document.addEventListener('click', function (event) {

            if (
                !event.target.closest('.row-menu-btn') &&
                !event.target.closest('.df-dropdown-menu')
            ) {

                document
                    .querySelectorAll('.df-dropdown-menu')
                    .forEach(menu => {

                        menu.classList.remove('open');

                    });

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Ticket Count
        |--------------------------------------------------------------------------
        */

        const ticketCountLine =
            document.getElementById('ticketCountLine');


        if (ticketCountLine) {

            const total =
                {{ $ticket->total() }};

            ticketCountLine.textContent =
                total + ' {{ __('ticket.tickets') }}';

        }

    </script>

</x-app-layout>
