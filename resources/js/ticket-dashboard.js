/**
 * Ticket management dashboard — search, filter, sort, reply, and admin modal state.
 */
export default function ticketDashboard(initialTickets = [], isAdmin = false, i18n = {}) {
    return {
        tickets: initialTickets,
        isAdmin,
        i18n,
        search: '',
        dateFilter: '',
        statusFilter: '',
        sortColumn: 'date',
        sortDir: 'desc',
        openMenuId: null,
        showEdit: false,
        showDelete: false,
        showReply: false,
        editing: { id: null, title: '', description: '', status: 'pending', update_url: '' },
        deleting: null,
        replying: null,
        replyBody: '',

        get filteredTickets() {
            let list = [...this.tickets];
            const q = this.search.trim().toLowerCase();

            if (q) {
                list = list.filter((t) =>
                    String(t.id).includes(q) ||
                    t.title.toLowerCase().includes(q) ||
                    t.description.toLowerCase().includes(q) ||
                    (t.owner && t.owner.toLowerCase().includes(q))
                );
            }

            if (this.dateFilter) {
                list = list.filter((t) => t.date === this.dateFilter);
            }

            if (this.statusFilter) {
                list = list.filter((t) => t.status === this.statusFilter);
            }

            const statusOrder = { pending: 0, in_progress: 1, completed: 2 };
            const dir = this.sortDir === 'asc' ? 1 : -1;

            list.sort((a, b) => {
                if (this.sortColumn === 'date') {
                    return a.date.localeCompare(b.date) * dir;
                }
                if (this.sortColumn === 'status') {
                    return (statusOrder[a.status] - statusOrder[b.status]) * dir;
                }
                return 0;
            });

            return list;
        },

        clearFilters() {
            this.search = '';
            this.dateFilter = '';
            this.statusFilter = '';
        },

        toggleSort(column) {
            if (this.sortColumn === column) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = column;
                this.sortDir = 'asc';
            }
        },

        sortIndicator(column) {
            if (this.sortColumn !== column) return '↕';
            return this.sortDir === 'asc' ? '↑' : '↓';
        },

        truncateWords(text, count = 6) {
            if (!text) return '';
            const words = text.trim().split(/\s+/);
            if (words.length <= count) return text;
            return words.slice(0, count).join(' ') + '...';
        },

        statusLabel(status) {
            return this.i18n[status] || status;
        },

        statusBadgeClass(status) {
            return ({
                pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200',
                in_progress: 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                completed: 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
            })[status] || 'bg-gray-100 text-gray-800';
        },

        toggleMenu(id) {
            this.openMenuId = this.openMenuId === id ? null : id;
        },

        focusMenuItem(ticketId, target) {
            const menu = document.getElementById('ticket-menu-' + ticketId);
            if (!menu) return;
            const items = [...menu.querySelectorAll('[role="menuitem"]')];
            if (!items.length) return;

            const current = items.indexOf(document.activeElement);
            let next = 0;

            if (target === 'next') next = current < 0 ? 0 : (current + 1) % items.length;
            else if (target === 'prev') next = current <= 0 ? items.length - 1 : current - 1;
            else if (target === 'last') next = items.length - 1;
            else next = Number(target) || 0;

            items[next]?.focus();
        },

        openEdit(ticket) {
            if (!this.isAdmin) return;
            this.editing = { ...ticket };
            this.showEdit = true;
        },

        confirmDelete(ticket) {
            if (!this.isAdmin) return;
            this.deleting = ticket;
            this.showDelete = true;
        },

        openReply(ticket) {
            this.replying = ticket;
            this.replyBody = '';
            this.showReply = true;
        },
    };
}
