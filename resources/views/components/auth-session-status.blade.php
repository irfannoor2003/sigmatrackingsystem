@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success']) }}>
        <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
        <span>{{ $status }}</span>
    </div>
@endif