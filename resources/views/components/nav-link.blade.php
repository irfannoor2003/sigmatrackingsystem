@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold text-[var(--accent)] bg-[var(--accent-softer)] transition'
            : 'inline-flex items-center px-3 py-2 rounded-xl text-sm font-medium text-[var(--text-soft)] hover:text-[var(--accent)] hover:bg-[var(--accent-softer)] transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>