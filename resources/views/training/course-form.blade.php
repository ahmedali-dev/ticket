@php
    $editing = isset($training);
    $heading = $editing ? __('training.edit_training') : __('training.add_new_course');
    $currentCover = $editing && isset($media) ? Storage::disk('public')->url($media->path) : null;
@endphp
<x-app-layout>
    <div class="course-editor">
        <nav class="course-editor-breadcrumb"><a href="{{ route('training.index') }}">{{ __('training.courses') }}</a><span aria-hidden="true">/</span><span>{{ $heading }}</span></nav>
        <header class="course-editor-heading"><div><h1>{{ $heading }}</h1><p>{{ __('training.editor_intro') }}</p></div><a class="df-btn df-btn-ghost" href="{{ route('training.index') }}">{{ __('training.cancel') }}</a></header>
        <form class="course-editor-form" action="{{ $editing ? route('training.update', $training) : route('training.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if ($editing) @method('PUT') @endif
            <section class="course-editor-main df-card">
                <div class="course-editor-section-title"><span>01</span><h2>{{ __('training.course_details') }}</h2></div>
                <label for="course-title">{{ __('training.course_title') }} <span class="df-req">*</span></label>
                <input class="df-input" id="course-title" name="title" type="text" required value="{{ old('title', $editing ? $training->title : '') }}" placeholder="{{ __('training.course_title_placeholder') }}" @error('title') aria-invalid="true" @enderror>
                @error('title') <p class="df-error-text">{{ $message }}</p> @enderror
                <div class="course-editor-section-title course-editor-cover-heading"><span>02</span><h2>{{ __('training.course_thumbnail') }}</h2></div>
                <p class="course-editor-help">{{ __('training.cover_help') }}</p>
                <div class="course-cover-preview" id="course-cover-preview">
                    <img id="course-cover-image" alt="{{ __('training.image_preview') }}" @if ($currentCover) src="{{ $currentCover }}" @else hidden @endif>
                    <div id="course-cover-placeholder" @if ($currentCover) hidden @endif>
                        <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.3" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8" cy="8" r="1.5"/><path d="m3 17 6-6 4 4 3-3 5 5"/></svg>
                        <span>{{ __('training.thumbnail_preview') }}</span>
                    </div>
                </div>
                <label for="course-cover-input" class="course-upload-label">{{ __('training.choose_cover') }}</label>
                <input id="course-cover-input" type="file" name="image" accept="image/png,image/jpeg,image/gif,image/svg+xml" class="course-file-input">
                <p class="course-editor-help">PNG, JPG, GIF, SVG · {{ __('training.cover_size') }}</p>
                @error('image') <p class="df-error-text">{{ $message }}</p> @enderror
                @if ($currentCover)
                    <label class="course-remove-cover"><input id="course-remove-cover" type="checkbox" name="remove_image" value="1" @checked(old('remove_image'))> {{ __('training.remove_current_image') }}</label>
                @endif
            </section>
            <aside class="course-publish-panel df-card">
                <div class="course-editor-section-title"><span>03</span><h2>{{ __('training.course_published') }}</h2></div>
                <p class="course-editor-help">{{ __('training.visibility_help') }}</p>
                <fieldset class="course-visibility"><legend class="sr-only">{{ __('training.course_published') }}</legend>
                    <label><input type="radio" name="active" value="1" @checked((string) old('active', $editing ? (int) $training->active : 1) === '1')><span><strong>{{ __('training.visible') }}</strong><small>{{ __('training.visible_help') }}</small></span></label>
                    <label><input type="radio" name="active" value="0" @checked((string) old('active', $editing ? (int) $training->active : 1) === '0')><span><strong>{{ __('training.hidden') }}</strong><small>{{ __('training.hidden_help') }}</small></span></label>
                </fieldset>
                @error('active') <p class="df-error-text">{{ $message }}</p> @enderror
                <div class="course-save-actions"><button class="df-btn df-btn-primary" type="submit">{{ $editing ? __('training.update') : __('training.create_course') }} <span aria-hidden="true">↗</span></button><a href="{{ route('training.index') }}">{{ __('training.cancel') }}</a></div>
            </aside>
        </form>
    </div>
    <script>
        (() => {
            const input = document.getElementById('course-cover-input');
            const image = document.getElementById('course-cover-image');
            const placeholder = document.getElementById('course-cover-placeholder');
            const remove = document.getElementById('course-remove-cover');
            const original = image.getAttribute('src');
            let previewUrl;
            const update = () => {
                if (previewUrl) URL.revokeObjectURL(previewUrl);
                const file = input.files[0];
                previewUrl = file ? URL.createObjectURL(file) : null;
                const src = previewUrl || (remove?.checked ? null : original);
                if (src) image.src = src;
                else image.removeAttribute('src');
                image.hidden = !src;
                placeholder.hidden = !!src;
            };
            input.addEventListener('change', () => { if (input.files.length && remove) remove.checked = false; update(); });
            remove?.addEventListener('change', () => { if (remove.checked) input.value = ''; update(); });
            window.addEventListener('pagehide', () => { if (previewUrl) URL.revokeObjectURL(previewUrl); });
            update();
        })();
    </script>
</x-app-layout>
