@extends('layouts.app')

@section('title', 'My Lates')

@section('content')

@php
    use Carbon\Carbon;
    $unresolved = collect($lates->items())->filter(fn ($l) => empty($l->late_reason))->count();
@endphp

<div class="pb-12">

    {{-- Header --}}
    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="alarm-clock" class="w-6 h-6"></i></span>
            My Late Records
        </h1>
        @if ($unresolved > 0)
            <span class="badge badge-amber !py-1.5 !px-3.5 !text-sm">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                {{ $unresolved }} reason{{ $unresolved > 1 ? 's' : '' }} pending
            </span>
        @endif
    </div>

    <p class="text-sm muted mb-6 -mt-3">{{ now()->format('F Y') }} — Add or review reasons for your late attendance.</p>

    @if($lates->isEmpty())
        <div class="card !rounded-3xl p-12 text-center">
            <div class="mx-auto w-16 h-16 rounded-full bg-emerald-500/10 flex items-center justify-center mb-4">
                <i data-lucide="check-circle" class="w-8 h-8 text-emerald-500"></i>
            </div>
            <p class="text-lg font-semibold text-[var(--text)]">No late records found.</p>
            <p class="text-sm muted mt-1">Great job! You haven&rsquo;t been late.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($lates as $late)
                @php
                    $hasReason = !empty($late->late_reason);
                    $lateMinutes = $late->clock_in
                        ? (int) abs(Carbon::parse($late->clock_in)->diffInMinutes(Carbon::parse($late->date)->setTime(10, 16), false))
                        : 0;
                @endphp

                <div class="card card-hover !rounded-2xl p-5 {{ $hasReason ? '' : '!border-amber-500/30' }}">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-display font-semibold text-[var(--text)]">
                                    {{ Carbon::parse($late->date)->format('d M Y, l') }}
                                </span>
                                <span class="badge {{ $hasReason ? 'badge-emerald' : 'badge-amber' }}">
                                    {{ $hasReason ? 'Reason given' : 'Reason required' }}
                                </span>
                            </div>
                            <p class="text-sm muted mt-1">
                                Clocked in at
                                <span class="font-semibold text-[var(--text)]">{{ optional($late->clock_in)->format('h:i A') }}</span>
                            </p>
                        </div>

                        <div class="text-right shrink-0">
                            <span class="text-[11px] muted">min late</span>
                            <p class="text-2xl font-display font-bold text-amber-500 leading-tight">
                                {{ $lateMinutes }}
                            </p>
                        </div>
                    </div>

                    @if($hasReason)
                        <div class="mt-3 soft-panel p-3.5 rounded-xl">
                            <p class="text-[.65rem] uppercase tracking-widest muted mb-1">Late reason</p>
                            <p class="text-sm text-[var(--text-soft)] break-words">{{ $late->late_reason }}</p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('attendance.late-reason') }}" class="mt-4">
                            @csrf
                            <input type="hidden" name="attendance_id" value="{{ $late->id }}">

                            <label class="label" for="late_reason_{{ $late->id }}">
                                Why were you late on {{ Carbon::parse($late->date)->format('d M') }}?
                            </label>

                            <div class="input-icon-wrap mb-3">
                                <i data-lucide="message-square-text" class="w-4 h-4"></i>
                                <textarea name="late_reason" id="late_reason_{{ $late->id }}" rows="2" maxlength="255"
                                    required
                                    placeholder="e.g. Traffic on the main road…"
                                    class="textarea !pl-9 resize-none"></textarea>
                            </div>

                            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2">
                                <span class="text-[11px] muted sm:mr-auto">Keep it short and factual.</span>
                                <button type="submit" class="btn btn-primary w-full sm:w-auto">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                    Submit Reason
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $lates->links() }}
    </div>

</div>

@endsection
