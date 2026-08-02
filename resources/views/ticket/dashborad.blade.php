@php
    $status = [
        'pending' => 'background:var(--amber-bg);color:var(--amber-text);',
        'in_progress' => 'background:var(--blue-bg);color:var(--blue-text);',
        'completed' => 'background:var(--green-bg);color:var(--green-text);',
    ];

    $status_dot = [
        'pending' => 'background:var(--amber-dot);',
        'in_progress' => 'background:var(--blue-dot);',
        'completed' => 'background:color:var(--green-dot);',
    ];

    $status_arr = ['all', 'pending', 'in_progress', 'completed'];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    Ticket Management
                </h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    Search, filter, and manage your tickets
                </p>
            </div>
        </div>
    </x-slot>


    <div id="pageRoot">
        <div class="df-page">
            <div
                style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:22px;gap:16px;flex-wrap:wrap;">
                <div>
                    <h1 class="df-display" style="font-size:26px;font-weight:700;margin:0;">Tickets</h1>
                    <p id="ticketCountLine" style="color:var(--text-muted);font-size:14px;margin:4px 0 0;"></p>
                </div>
            </div>
            <div class="df-filters-row"
                style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:18px;flex-wrap:wrap;">
                <form action="{{ route('ticket.search') }}" method="POST">
                    <div class="df-filters-left" style="display:flex;gap:10px;flex:1;flex-wrap:wrap;">
                        @csrf
                        <div style="position:relative;flex:1 1 220px;max-width:320px;">
                            {{-- <span
                            style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-faint);">search</span>
                        --}}
                            <input name="search" id="searchInput" class="df-input"
                                style="width:100%;padding-left:34px;" placeholder="Search by ID, title, or description"
                                value="{{ old('search', request('search')) }}" />
                        </div>
                        <div style="position:relative;">
                            <span
                                style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-faint);pointer-events:none;"></span>
                            <input name="date" id="dateInput" type="date" class="df-input"
                                style="padding-left:34px;" value="{{ old('date', request('date')) }}" />
                        </div>
                        <select id="statusSelect" name="status" class="df-select">
                            <option value="all"
                                {{ old('status', request('status', 'all')) === 'all' ? 'selected' : '' }}>All</option>
                            @foreach ($status_arr as $s)
                                <option value="{{ $s }}"
                                    {{ old('status', request('status')) === $s ? 'selected' : '' }}>
                                    {{ Str::replace('_', ' ', $s) }}
                                </option>
                            @endforeach
                        </select>

                        <button class="df-btn df-btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-search-icon lucide-search">
                                <path d="m21 21-4.34-4.34" />
                                <circle cx="11" cy="11" r="8" />
                            </svg>
                        </button>
                    </div>
                </form>
                @if (auth()->user()->type !== 'admin')
                    <a href="{{ route('ticket.create') }}" class="df-btn df-btn-primary" id="addTicketBtn">Add
                        Ticket</a>
                @endif
            </div>
            <div id="tableContainer">


                <div class="df-card df-table-wrap">
                    <table class="df-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th class="sortable" data-sort="date">
                                    <span style="display:inline-flex;align-items:center;gap:4px;">
                                        Created Date
                                        <!-- Sort Icon -->
                                    </span>
                                </th>
                                <th class="sortable" data-sort="status">
                                    <span style="display:inline-flex;align-items:center;gap:4px;">
                                        Status
                                        <!-- Sort Icon -->
                                    </span>
                                </th>
                                <th style="width:60px;"></th>
                            </tr>
                        </thead>
                        {{-- @dd($ticket) --}}
                        <tbody>
                            @foreach ($ticket as $t)
                                <tr>
                                    <td><span class="df-stub df-mono">{{ $t->id }}</span></td>
                                    <td style="font-weight:600;max-width:220px;">{{ $t->title }}</td>
                                    <td style="color:var(--text-muted);max-width:260px;">
                                        {{ Str::limit($t->description, 20, '...') }}

                                    </td>
                                    <td class="df-mono" style="color:var(--text-muted);font-size:13px;">
                                        {{ $t->created_at->format('d/m/y h:m') }}
                                        {{-- {{ dd($t) }} --}}
                                    </td>
                                    <td>
                                        <span class="df-badge" style="{{ $status[$t->status] }}">
                                            <span class="df-badge-dot" style="{{ $status_dot[$t->status] }}"></span>
                                            {{ Str::replace('_', ' ', $t->status) }}
                                        </span>
                                    </td>


                                    <td>
                                        <div class="df-dropdown" id="menu">
                                            <button onclick="openMenu(this)" class="df-icon-btn row-menu-btn" data-id="{{ $t->id }}"
                                                style="width:30px;height:30px;">
                                                ⋮
                                            </button>

                                            <div class="df-dropdown-menu" id='dropdown'
                                                data-menu-id="{{ $t->id }}">
                                                <a href="{{ route('ticket.reply', ['ticket' => $t]) }}"
                                                class="df-dropdown-item view-ticket-item">
                                                View Ticket
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            <!-- Repeat <tr> for additional rows -->
                        </tbody>
                    </table>

                    <div class="df-pagination" style="border-top:1px solid var(--border);">

                        <div class="df-pagination-info">
                            Showing {{ $ticket->firstItem() ?? 0 }}–{{ $ticket->lastItem() ?? 0 }}
                            of {{ $ticket->total() }}
                        </div>

                        <div class="df-pagination-controls">

                            {{-- Previous --}}
                            <a href="{{ $ticket->previousPageUrl() }}"
                                class="df-page-btn {{ $ticket->onFirstPage() ? 'disabled' : '' }}">
                                &laquo;
                            </a>

                            {{-- Page Numbers --}}
                            @foreach ($ticket->getUrlRange(1, $ticket->lastPage()) as $page => $url)
                                <a href="{{ $url }}"
                                    class="df-page-btn {{ $page == $ticket->currentPage() ? 'active' : '' }}">
                                    {{ $page }}
                                </a>
                            @endforeach

                            {{-- Next --}}
                            <a href="{{ $ticket->nextPageUrl() }}"
                                class="df-page-btn {{ $ticket->hasMorePages() ? '' : 'disabled' }}">
                                &raquo;
                            </a>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        const openMenu = (e) => {
            // Close all dropdowns

            // e.stopPropagation()
            document.querySelectorAll('.df-dropdown-menu').forEach(menu => {
                menu.classList.remove('open');
            });

            // Open the selected dropdown
            dropdown = document.querySelector(
                `#dropdown[data-menu-id="${e.dataset.id}"]`
            );
            console.log(dropdown)
            dropdown.classList.add('open');
        }
    </script>
</x-app-layout>
