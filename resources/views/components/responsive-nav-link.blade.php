@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-s-2 border-[var(--accent)] text-start text-base font-semibold text-[var(--accent)] bg-[var(--accent-softer)] transition'
            : 'block w-full ps-3 pe-4 py-2 border-s-2 border-transparent text-start text-base font-medium text-[var(--text-soft)] hover:bg-[var(--accent-softer)] hover:text-[var(--accent)] transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>