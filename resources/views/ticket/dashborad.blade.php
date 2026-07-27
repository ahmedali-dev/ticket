{{--
Ticket Management Dashboard
Client-side search, filter, and sort via Alpine.js.
Create lives on /tickets/create. Update / Delete / status changes are admin-only.
--}}
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
                <div class="df-filters-left" style="display:flex;gap:10px;flex:1;flex-wrap:wrap;">
                    <div style="position:relative;flex:1 1 220px;max-width:320px;">
                        {{-- <span
                            style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-faint);">search</span>
                        --}}
                        <input id="searchInput" class="df-input" style="width:100%;padding-left:34px;"
                            placeholder="Search by ID, title, or description" value="" />
                    </div>
                    <div style="position:relative;">
                        <span
                            style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-faint);pointer-events:none;"></span>
                        <input id="dateInput" type="date" class="df-input" style="padding-left:34px;" value="" />
                    </div>
                    <select id="statusSelect" class="df-select">
                        <option value="">hello</option>
                    </select>
                </div>
                <a href="{{ route('ticket.create') }}" class="df-btn df-btn-primary" id="addTicketBtn">Add Ticket</a>
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

                        <tbody>
                            <tr>
                                <td><span class="df-stub df-mono">12345</span></td>
                                <td style="font-weight:600;max-width:220px;">Sample Ticket Title</td>
                                <td style="color:var(--text-muted);max-width:260px;">
                                    Sample ticket description...
                                </td>
                                <td class="df-mono" style="color:var(--text-muted);font-size:13px;">
                                    Jan 15, 2026
                                </td>
                                <td>
                                    <span class="badge badge-success">Open</span>
                                </td>
                                <td>
                                    <div class="df-dropdown">
                                        <button class="df-icon-btn row-menu-btn" style="width:30px;height:30px;"
                                            data-id="12345">
                                            ⋮
                                        </button>

                                        <div class="df-dropdown-menu" data-menu-for="12345">
                                            <a href="{{ route('ticket.reply', ['ticket' => 'asdfas']) }}"
                                                class="df-dropdown-item view-ticket-item" data-id="12345">
                                                View Ticket
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Repeat <tr> for additional rows -->
                        </tbody>
                    </table>

                    <div class="df-pagination" style="border-top:1px solid var(--border);">
                        <div class="df-pagination-info">
                            Showing 1–10 of 50
                        </div>

                        <div class="df-pagination-controls" id="paginationControls">


                            <button class="df-page-btn" data-page="prev" disabled>&laquo;</button>
                            <button class="df-page-btn active" data-page="1">1</button>
                            <button class="df-page-btn " data-page="1">2</button>
                            <button class="df-page-btn " data-page="1">3</button>
                            <button class="df-page-btn" data-page="next" disabled>&raquo;</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('tableContainer');

        container.querySelectorAll('.row-menu-btn').forEach(btn => btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const id = btn.getAttribute('data-id');
            const menu = container.querySelector(`.df-dropdown-menu[data-menu-for="${id}"]`);
            const wasOpen = menu.classList.contains('open');
            container.querySelectorAll('.df-dropdown-menu').forEach(m => m.classList.remove('open'));
            if (!wasOpen) menu.classList.add('open');
        }));
    </script>
</x-app-layout>