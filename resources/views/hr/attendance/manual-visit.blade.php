@extends('layouts.app')

@section('title','Manual Visit')

@section('content')
<div class="max-w-xl mx-auto mt-12 px-0">

    <div class="glass p-8 rounded-3xl border border-white/20 shadow-2xl">

        <h2 class="text-2xl font-extrabold text-white mb-6 flex items-center gap-2">
            <i data-lucide="map-pin" class="w-6 h-6 text-pink-400"></i>
            Manual Visit
        </h2>

        @if(session('success'))
            <div class="mb-4 p-3 rounded-xl bg-green-500/20 text-green-300">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.attendance.manual.store') }}" class="space-y-5">
            @csrf

            {{-- STAFF --}}
            <div>
                <label class="text-xs text-white/50 mb-1 block">Staff</label>
                <select name="staff_id"
                        class="w-full px-4 py-3 rounded-xl bg-black/40 text-white border border-white/10">
                    @foreach($staff as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} ({{ ucfirst($user->role) }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- TYPE --}}
            <div>
                <label class="text-xs text-white/50 mb-1 block">Visit Type</label>
                <select name="type" id="visitType"
                        onchange="toggleDates()"
                        class="w-full px-4 py-3 rounded-xl bg-black/40 text-white border border-white/10">
                    <option value="single">Single Day</option>
                    <option value="range">Multiple Days</option>
                </select>
            </div>

            {{-- SINGLE DATE --}}
            <div id="singleDate">
                <input type="date" name="date"
                       class="w-full px-4 py-3 rounded-xl bg-black/40 text-white border border-white/10">
            </div>

            {{-- RANGE --}}
            <div id="rangeDate" class="hidden space-y-3">
                <input type="date" name="start_date"
                       class="w-full px-4 py-3 rounded-xl bg-black/40 text-white border border-white/10">
                <input type="date" name="end_date"
                       class="w-full px-4 py-3 rounded-xl bg-black/40 text-white border border-white/10">
            </div>

            {{-- NOTE --}}
            <textarea name="note" rows="3"
                      placeholder="Reason / Client / Location"
                      class="w-full px-4 py-3 rounded-xl bg-black/40 text-white border border-white/10"></textarea>

            <button
                class="w-full py-3 rounded-xl
                       bg-gradient-to-r from-[#ff2ba6] to-[#d41a8a]
                       text-white font-bold">
                Save Manual Visit
            </button>

        </form>
    </div>
</div>

<script>
function toggleDates() {
    const type = document.getElementById('visitType').value;
    document.getElementById('singleDate').classList.toggle('hidden', type === 'range');
    document.getElementById('rangeDate').classList.toggle('hidden', type !== 'range');
}
lucide.createIcons();
</script>
@endsection
