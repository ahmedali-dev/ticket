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
                        <span
                            style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-faint);">search</span>
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
                <button class="df-btn df-btn-primary" id="addTicketBtn">Add Ticket</button>
            </div>
            <div id="tableContainer"></div>
        </div>
    </div>
</x-app-layout>