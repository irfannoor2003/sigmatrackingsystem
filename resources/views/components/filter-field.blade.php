@props(['icon' => 'search', 'label' => null])

<div {{ $attributes }}>
    @if ($label)
        <label class="label">{{ $label }}</label>
    @endif
    <div class="input-icon-wrap">
        <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
        {{ $slot }}
    </div>
</div>
