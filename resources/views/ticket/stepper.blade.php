{{--
    Reusable status stepper.
    Usage: <x-ticket-stepper :status="$ticket->status" />
--}}
@php
    $statuses = ['pending', 'in_progress', 'completed'];
    $currentIndex = array_search($status, $statuses);
    $currentIndex = $currentIndex === false ? 0 : $currentIndex;
@endphp

<div class="df-stepper">
    @foreach ($statuses as $index => $statusName)
        @php
            $isDone = $index < $currentIndex;
            $isCurrent = $index === $currentIndex;
            $isActive = $index <= $currentIndex;
        @endphp

        <div class="df-step">
            <div class="df-step-line {{ $index > 0 && $isActive ? 'filled' : '' }}"></div>

            <div class="df-step-dot {{ $isDone ? 'done' : ($isCurrent ? 'current' : '') }}">
                @if ($isDone)
                    <svg class="ico" style="width:14px;height:14px" viewBox="0 0 24 24">
                        <path d="M5 12l5 5L20 7"></path>
                    </svg>
                @else
                    {{ $index + 1 }}
                @endif
            </div>

            <div class="df-step-label {{ $isActive ? 'active' : '' }}">
                {{ __('ticket.' . $statusName) }}
            </div>
        </div>
    @endforeach
</div>
