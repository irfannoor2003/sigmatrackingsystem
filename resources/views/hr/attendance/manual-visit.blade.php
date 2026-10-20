@extends('layouts.app')

@section('title','Manual Visit')

@section('content')
<div class="max-w-xl mx-auto mt-12 px-0">

    <div class="card !rounded-3xl p-8 shadow-card-lg">

        <div class="page-head !mb-6">
            <h2 class="page-title">
                <span class="page-ico"><i data-lucide="map-pin" class="w-6 h-6"></i></span>
                Manual Visit
            </h2>
        </div>

        <form method="POST" action="{{ route('admin.attendance.manual.store') }}" class="space-y-5">
            @csrf

            {{-- STAFF --}}
            <div>
                <label class="label mb-1">Staff</label>
                <select name="staff_id" class="select w-full">
                    @foreach($staff as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} ({{ ucfirst($user->role) }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- TYPE --}}
            <div>
                <label class="label mb-1">Visit Type</label>
                <select name="type" id="visitType"
                        onchange="toggleDates()"
                        class="select w-full">
                    <option value="single">Single Day</option>
                    <option value="range">Multiple Days</option>
                </select>
            </div>

            {{-- SINGLE DATE --}}
            <div id="singleDate">
                <input type="date" name="date" class="input w-full">
            </div>

            {{-- RANGE --}}
            <div id="rangeDate" class="hidden space-y-3">
                <div class="input-icon-wrap">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <input type="date" name="start_date" class="input w-full">
                </div>
                <div class="input-icon-wrap">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <input type="date" name="end_date" class="input w-full">
                </div>
            </div>

            {{-- NOTE --}}
            <textarea name="note" rows="3"
                      placeholder="Reason / Client / Location"
                      class="textarea w-full"></textarea>

            <button class="btn btn-primary w-full !py-3">
                <i data-lucide="save" class="w-4 h-4"></i>
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
</script>
@endsection