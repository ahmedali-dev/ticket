@props(['label' => '', 'error' => [], 'disable' => false, 'id' => ''])

<div class="flex items-start justify-center flex-col gap-1 mt-3">

    {{--  label if found  --}}
    @if($label)
        <label for="{{$id}}">{{ __($label) }}</label>
    @endif

    {{$slot}}

    <input @disabled($disable)
           id="{{$id}}"
        {{$attributes->merge([
            'class' => 'rounded border-blue-200 focus:border-blue-200 focus:outline-blue-400 ' .  (count($error) > 0
                ? 'border-red-200 text-red-600'
                : '')
        ])}}
        {{$attributes}}
    />


    @if($error)

        <small class="text-[14px] text-red-600">
            @foreach((array) $error as $e)
                {{$e}}
            @endforeach
        </small>
    @endif
</div>
