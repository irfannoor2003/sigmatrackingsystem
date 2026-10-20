@props(['reset' => null, 'submit' => 'Filter'])

<div class="filter-bar__actions">
    <button type="submit" class="btn btn-primary w-full sm:w-auto">
        <i data-lucide="filter" class="w-4 h-4"></i>
        {{ $submit }}
    </button>

    @if ($reset)
        <a href="{{ $reset }}" class="btn btn-outline w-full sm:w-auto">
            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
            Reset
        </a>
    @endif

    {{ $slot }}
</div>
