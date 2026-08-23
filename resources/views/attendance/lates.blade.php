@extends('layouts.app')

@section('title','My Lates')

@section('content')

@php
    use Carbon\Carbon;
    $unresolved = collect($lates->items())->filter(fn ($l) => empty($l->late_reason))->count();
@endphp

<div class="max-w-4xl mx-auto pb-24 text-white">

    {{-- Header --}}
    <div class="glass p-6 rounded-3xl border border-white/20 shadow-xl mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold flex items-center gap-2">
                <i data-lucide="alarm-clock" class="w-6 h-6 text-amber-400"></i>
                My Late Records
            </h2>
            <p class="text-sm text-white/50 mt-1">{{ now()->format('F Y') }} — Add or review reasons for your late attendance.</p>
        </div>

        @if($unresolved > 0)
            <span class="px-4 py-2 rounded-xl bg-amber-500/20 border border-amber-400/30 text-amber-300 text-sm font-semibold">
                {{ $unresolved }} reason{{ $unresolved > 1 ? 's' : '' }} pending
            </span>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-2xl bg-green-500/20 border border-green-400/40 text-green-100">
            {{ session('success') }}
        </div>
    @endif

    @if($lates->isEmpty())
        <div class="glass p-12 rounded-3xl border border-white/10 text-center">
            <i data-lucide="check-circle" class="w-12 h-12 text-emerald-400/50 mx-auto mb-4"></i>
            <p class="text-white/60 text-lg">No late records found.</p>
            <p class="text-white/30 text-sm mt-1">Great job! You haven't been late.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($lates as $late)
                @php
                    $hasReason = !empty($late->late_reason);
                @endphp
                <div class="glass rounded-3xl border {{ $hasReason ? 'border-white/10' : 'border-amber-400/30' }} p-5 shadow">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-base font-bold text-white">{{ Carbon::parse($late->date)->format('d M Y, l') }}</span>
                                <span @if($hasReason) class="badge bg-emerald-500/30 text-emerald-300" @else class="badge bg-amber-500/30 text-amber-300" @endif
                                      style="font-size:9px;padding:2px 6px;border-radius:6px;">
                                    @if($hasReason) Reason given @else Reason required @endif
                                </span>
                            </div>
                            <p class="text-sm text-white/60 mt-1">
                                Clocked in at
                                <span class="text-white font-semibold">{{ optional($late->clock_in)->format('h:i A') }}</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] text-white/40">min late</span><br>
                            <span class="text-xl font-extrabold text-amber-400">
                                {{ (int) optional($late->clock_in)->diffInMinutes(Carbon::parse($late->date)->setTime(10,16), false) }}
                            </span>
                        </div>
                    </div>

                    @if($hasReason)
                        <div class="mt-2 p-3 rounded-xl bg-white/5 border border-white/10">
                            <p class="text-[10px] uppercase tracking-wider text-white/40 mb-1">Late reason</p>
                            <p class="text-white/85 text-sm">{{ $late->late_reason }}</p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('attendance.late-reason') }}" class="mt-3 space-y-3">
                            @csrf
                            <input type="hidden" name="attendance_id" value="{{ $late->id }}">
                            <textarea name="late_reason" rows="2" required
                                placeholder="Add the reason you were late on this day…"
                                class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white resize-none"></textarea>
                            <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 text-white font-semibold">
                                Submit Reason
                            </button>
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

<style>
    .glass { background: rgba(255,255,255,.06); backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px); }
</style>
<script>lucide.createIcons();</script>
@endsection