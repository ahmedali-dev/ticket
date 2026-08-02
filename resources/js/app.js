import './bootstrap';
import './echo';
import Alpine from 'alpinejs';
import ticketDashboard from './ticket-dashboard';
import createTicketForm from './create-ticket-form';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('ticketDashboard', ticketDashboard);
    Alpine.data('createTicketForm', createTicketForm);
});

Alpine.start();
