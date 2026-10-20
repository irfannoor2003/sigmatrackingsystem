@props(['action' => null])

<form @if ($action) action="{{ $action }}" @endif method="GET"
    {{ $attributes->merge(['class' => 'card filter-bar']) }}>
    {{ $slot }}
</form>
