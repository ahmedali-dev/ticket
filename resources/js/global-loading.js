/**
 * Global loading indicator.
 *
 * - Shows a top progress bar for normal navigation (link clicks, form submits).
 * - Shows the same bar for any fetch()/axios request, unless opted out.
 * - Exposes window.Loading.show()/hide() for manual control (e.g. inside a
 *   feature's own JS if you need finer-grained handling than the defaults give you).
 *
 * Opt a specific link/form out with: data-no-spinner
 * Opt a specific fetch() call out by passing { noSpinner: true } as a fetch option
 * (it's stripped before the real fetch call goes out).
 *
 * Import once globally, e.g. in resources/js/app.js:
 *   import './global-loading';
 */
(() => {
    const bar = () => document.getElementById('global-loading-bar');
    const overlay = () => document.getElementById('global-loading-overlay');

    let activeRequests = 0;
    let barTimer = null;

    function showBar() {
        const el = bar();
        if (!el) return;
        el.style.width = '0%';
        el.classList.add('is-visible');
        // Force reflow so the width transition actually animates from 0
        void el.offsetWidth;
        el.style.width = '75%';

        clearTimeout(barTimer);
    }

    function finishBar() {
        const el = bar();
        if (!el) return;
        el.style.width = '100%';
        barTimer = setTimeout(() => {
            el.classList.remove('is-visible');
            el.style.width = '0%';
        }, 250);
    }

    function showOverlay() {
        overlay()?.classList.add('is-visible');
        overlay()?.setAttribute('aria-hidden', 'false');
    }

    function hideOverlay() {
        overlay()?.classList.remove('is-visible');
        overlay()?.setAttribute('aria-hidden', 'true');
    }

    function requestStarted() {
        activeRequests++;
        showBar();
    }

    function requestFinished() {
        activeRequests = Math.max(0, activeRequests - 1);
        if (activeRequests === 0) finishBar();
    }

    window.Loading = {
        show: showOverlay,
        hide: hideOverlay,
        start: requestStarted,
        stop: requestFinished,
    };

    /* -------------------------------------------------------------- */
    /* Full-page navigation: normal links and form submits             */
    /* -------------------------------------------------------------- */

    document.addEventListener('click', (e) => {
        const link = e.target.closest('a[href]');
        if (!link) return;
        if (link.hasAttribute('data-no-spinner')) return;
        if (link.target === '_blank') return;
        if (link.hasAttribute('download')) return;
        if (link.getAttribute('href')?.startsWith('#')) return;
        if (e.metaKey || e.ctrlKey || e.shiftKey) return; // opening in new tab

        showBar();
    });

    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (form.hasAttribute('data-no-spinner')) return;
        showBar();
    });

    // If the user navigates back via bfcache, make sure nothing is left stuck visible.
    window.addEventListener('pageshow', (e) => {
        if (e.persisted) {
            hideOverlay();
            bar()?.classList.remove('is-visible');
        }
    });

    /* -------------------------------------------------------------- */
    /* fetch() — wraps window.fetch once, globally                     */
    /* -------------------------------------------------------------- */

    const originalFetch = window.fetch;

    window.fetch = function (...args) {
        const opts = args[1] || {};
        const skip = opts.noSpinner === true;
        if (skip) delete opts.noSpinner;

        if (!skip) requestStarted();

        return originalFetch.apply(this, args).finally(() => {
            if (!skip) requestFinished();
        });
    };

    /* -------------------------------------------------------------- */
    /* axios (if you're using Laravel's default bootstrap.js axios)    */
    /* -------------------------------------------------------------- */

    if (window.axios) {
        window.axios.interceptors.request.use((config) => {
            if (!config.noSpinner) requestStarted();
            return config;
        });

        window.axios.interceptors.response.use(
            (response) => {
                if (!response.config.noSpinner) requestFinished();
                return response;
            },
            (error) => {
                if (!error.config?.noSpinner) requestFinished();
                return Promise.reject(error);
            }
        );
    }
})();
