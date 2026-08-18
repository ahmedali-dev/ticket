{{--
    Global loading overlay.
    Include ONCE in your main layout (e.g. resources/views/layouts/app.blade.php
    or the app-layout component), right before </body>.

    Controlled entirely by resources/js/global-loading.js — you normally never
    touch this markup again.
--}}
<div id="global-loading-overlay" aria-hidden="true">
    <div class="global-loading-spinner" role="status" aria-live="polite">
        <span class="sr-only">{{ __('ticket.loading') ?? 'Loading…' }}</span>
    </div>
</div>

<style>
    #global-loading-overlay {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.55);
        backdrop-filter: blur(1px);
    }

    html.dark #global-loading-overlay {
        background: rgba(17, 24, 39, 0.55);
    }

    #global-loading-overlay.is-visible {
        display: flex;
    }

    .global-loading-spinner {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 3px solid rgba(99, 102, 241, 0.25);
        border-top-color: #6366f1; /* indigo-500, matches your back-button focus ring */
        animation: global-spin 0.7s linear infinite;
    }

    @keyframes global-spin {
        to { transform: rotate(360deg); }
    }

    /* Thin top-of-page progress bar variant, shown for fast requests instead of the full overlay */
    #global-loading-bar {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        width: 0%;
        background: #6366f1;
        z-index: 100000;
        transition: width 0.2s ease, opacity 0.3s ease;
        opacity: 0;
    }

    #global-loading-bar.is-visible {
        opacity: 1;
    }
</style>

<div id="global-loading-bar"></div>
