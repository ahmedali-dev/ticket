/**
 * Ticket reply page controller.
 *
 * Reads config from a `#ticketConfig` JSON script tag (see show.blade.php) instead of
 * inlining PHP values across multiple <script> blocks. Import/bundle this via Vite:
 *   import './ticket-chat';
 */
document.addEventListener('DOMContentLoaded', () => {
    const configEl = document.getElementById('ticketConfig');
    if (!configEl) return;

    const config = JSON.parse(configEl.textContent);
    const {
        ticketId,
        replyStoreUrl,
        currentUserId,
        currentUserName,
        currentUserType,
        i18n,
    } = config;

    const conversationBox = document.getElementById('conversationBox');
    const replyTextarea = document.getElementById('reply');
    const charCount = document.getElementById('replyCharCount');
    const sendBtn = document.getElementById('sendReply');
    const cancelBtn = document.getElementById('cancelReply');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    /* ------------------------------------------------------------------ */
    /* Notifications                                                       */
    /* ------------------------------------------------------------------ */

    const requestNotificationPermission = () => {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    };

    requestNotificationPermission();
    document.addEventListener('click', requestNotificationPermission, { once: true });

    function notify(reply) {
        if (!('Notification' in window) || Notification.permission !== 'granted') return;
        if (document.visibilityState === 'visible') return;

        const notification = new Notification(`${i18n.newReplyFrom} ${reply.user.name}`, {
            body: reply.body.length > 120 ? reply.body.slice(0, 120) + '…' : reply.body,
            tag: `reply-${reply.id}`,
        });

        notification.onclick = () => {
            window.focus();
            notification.close();
        };
    }

    /* ------------------------------------------------------------------ */
    /* Helpers                                                             */
    /* ------------------------------------------------------------------ */

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
    }

    function formatDate(isoString) {
        const d = new Date(isoString);
        const pad = (n) => String(n).padStart(2, '0');
        return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}`;
    }

    /**
     * Single source of truth for rendering a reply bubble.
     * Used by BOTH the optimistic fetch response and the Echo broadcast,
     * so timestamps and markup never drift between the two paths.
     */
    function renderReply({ id, body, userName, userType, createdAt }) {
        if (document.querySelector(`[data-id="${id}"]`)) return; // no dupes

        const bubbleClass = userType === 'admin' ? 'df-chat-admin' : 'df-chat-user';

        const row = document.createElement('div');
        row.className = 'df-chat-row';
        row.style.justifyContent = 'flex-start';
        row.dataset.id = id;

        row.innerHTML = `
            <div class="df-chat-bubble ${bubbleClass}">
                <div class="df-chat-meta">${escapeHtml(userName)} &middot; ${formatDate(createdAt)}</div>
                <div><p>${escapeHtml(body)}</p></div>
            </div>
        `;

        conversationBox.appendChild(row);
        conversationBox.scrollTop = conversationBox.scrollHeight;
    }

    /* ------------------------------------------------------------------ */
    /* Reply textarea state                                                */
    /* ------------------------------------------------------------------ */

    const MAX_LEN = replyTextarea.maxLength > 0 ? replyTextarea.maxLength : 2000;

    replyTextarea.addEventListener('input', () => {
        const len = replyTextarea.value.length;
        if (charCount) charCount.textContent = `${len} / ${MAX_LEN}`;
        sendBtn.disabled = replyTextarea.value.trim().length === 0;
    });

    cancelBtn.addEventListener('click', () => {
        replyTextarea.value = '';
        replyTextarea.dispatchEvent(new Event('input'));
    });

    /* ------------------------------------------------------------------ */
    /* Send reply                                                          */
    /* ------------------------------------------------------------------ */

    function setSendState(state) {
        if (state === 'sending') {
            sendBtn.disabled = true;
            sendBtn.textContent = i18n.sending;
        } else if (state === 'failed') {
            sendBtn.disabled = false;
            sendBtn.textContent = i18n.failedRetry;
        } else {
            sendBtn.disabled = replyTextarea.value.trim().length === 0;
            sendBtn.textContent = i18n.sendReplyDefault;
        }
    }

    sendBtn.addEventListener('click', async () => {
        const body = replyTextarea.value.trim();
        if (!body) return;

        setSendState('sending');

        try {
            const res = await fetch(replyStoreUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                body: JSON.stringify({ description: body }),
            });

            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                setSendState('failed');
                alert(err.message || i18n.failedToSendReply);
                return;
            }

            const reply = await res.json();

            renderReply({
                id: reply.id,
                body: reply.body,
                userName: currentUserName,
                userType: currentUserType,
                createdAt: reply.created_at,
            });

            replyTextarea.value = '';
            replyTextarea.dispatchEvent(new Event('input'));
            setSendState('idle');
        } catch (e) {
            console.error('Send reply error:', e);
            setSendState('failed');
            alert(i18n.somethingWentWrong);
        }
    });

    /* ------------------------------------------------------------------ */
    /* Realtime (Laravel Echo)                                             */
    /* ------------------------------------------------------------------ */

    if (window.Echo) {
        window.Echo.private(`ticket.${ticketId}`).listen('.send.message', (e) => {
            const reply = e.message;

            renderReply({
                id: reply.id,
                body: reply.body,
                userName: reply.user.name,
                userType: reply.user.type,
                createdAt: reply.created_at,
            });

            if (currentUserId && reply.user_id === currentUserId) return; // don't notify sender
            notify(reply);
        });
    }

    /* ------------------------------------------------------------------ */
    /* Close-ticket confirmation                                           */
    /* ------------------------------------------------------------------ */

    const closeForm = document.getElementById('closeTicketForm');
    if (closeForm) {
        closeForm.addEventListener('submit', (e) => {
            const message = closeForm.querySelector('[data-confirm]')?.dataset.confirm;
            if (message && !confirm(message)) {
                e.preventDefault();
            }
        });
    }

    /* ------------------------------------------------------------------ */
    /* Lightbox (referenced in markup previously, never implemented)       */
    /* ------------------------------------------------------------------ */

    document.querySelectorAll('.lightbox-trigger').forEach((img) => {
        img.setAttribute('role', 'button');
        img.setAttribute('tabindex', '0');

        const open = () => openLightbox(img.dataset.src, img.alt);
        img.addEventListener('click', open);
        img.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                open();
            }
        });
    });

    function openLightbox(src, alt) {
        const overlay = document.createElement('div');
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-modal', 'true');
        overlay.style.cssText =
            'position:fixed;inset:0;background:rgba(0,0,0,.85);display:flex;align-items:center;justify-content:center;z-index:9999;cursor:zoom-out;';

        const img = document.createElement('img');
        img.src = src;
        img.alt = alt || '';
        img.style.cssText = 'max-width:90vw;max-height:90vh;border-radius:8px;';

        overlay.appendChild(img);
        document.body.appendChild(overlay);

        const close = () => overlay.remove();
        overlay.addEventListener('click', close);
        document.addEventListener('keydown', function onKey(e) {
            if (e.key === 'Escape') {
                close();
                document.removeEventListener('keydown', onKey);
            }
        });
    }
});
