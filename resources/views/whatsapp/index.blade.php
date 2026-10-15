<x-app-layout>
    <div class="df-page" style="max-width:760px">
        <h1 class="text-2xl font-bold mb-4">{{ __('whatsapp.connection') }}</h1>
        <section class="df-card p-6">
            @if ($adminsMissingPhone)
                <p role="alert" style="padding:12px;margin-bottom:16px;background:#fff7ed;color:#9a3412;border:1px solid #fed7aa;border-radius:8px">
                    {{ __('whatsapp.missing_admin_phones', ['count' => $adminsMissingPhone]) }}
                </p>
            @endif
            <p>{{ __('whatsapp.pairing_help') }}</p>
            <p id="whatsapp-state" class="my-4 font-semibold" role="status">{{ __('whatsapp.checking') }}</p>
            <img id="whatsapp-qr" hidden width="280" height="280" style="margin:24px auto" alt="{{ __('whatsapp.qr_alt') }}">
            <p class="text-sm text-gray-500">{{ __('whatsapp.notification_help') }}</p>
        </section>
    </div>
    <script>
        (() => {
            const labels = @json(trans('whatsapp.states'));
            const image = document.getElementById('whatsapp-qr');
            const state = document.getElementById('whatsapp-state');
            async function refresh() {
                if (document.hidden) return;
                try {
                    const response = await fetch(@json(route('whatsapp.status')), { headers: { Accept: 'application/json' } });
                    if (!response.ok) throw new Error();
                    const data = await response.json();
                    state.textContent = labels[data.state] || labels.unavailable;
                    image.hidden = !data.qr;
                    if (data.qr) image.src = data.qr;
                    else image.removeAttribute('src');
                } catch { state.textContent = labels.unavailable; image.hidden = true; image.removeAttribute('src'); }
            }
            let timer;
            const poll = async () => { await refresh(); timer = setTimeout(poll, 5000); };
            poll();
            window.addEventListener('pagehide', () => clearTimeout(timer));
        })();
    </script>
</x-app-layout>
