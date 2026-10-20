@extends('layouts.app')

@section('title','Manual Visit')

@section('content')
<div class="max-w-xl mx-auto">

    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="map-pin" class="w-6 h-6"></i></span>
            Manual Visit
        </h1>
    </div>

    <div class="card !rounded-2xl p-8">

        <form method="POST" action="{{ route('admin.attendance.manual.store') }}" class="space-y-5">
            @csrf

            {{-- STAFF --}}
            <div>
                <label class="label">Staff</label>
                <select name="staff_id" class="select">
                    @foreach($staff as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} ({{ ucfirst($user->role) }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- TYPE --}}
            <div>
                <label class="label">Visit Type</label>
                <select name="type" id="visitType" onchange="toggleDates()" class="select">
                    <option value="single">Single Day</option>
                    <option value="range">Multiple Days</option>
                </select>
            </div>

            {{-- SINGLE DATE --}}
            <div id="singleDate">
                <input type="date" name="date" class="input">
            </div>

            {{-- RANGE --}}
            <div id="rangeDate" class="hidden space-y-3">
                <input type="date" name="start_date" class="input">
                <input type="date" name="end_date" class="input">
            </div>

            {{-- NOTE --}}
            <div>
                <label class="label">Note</label>
                <textarea name="note" rows="3" placeholder="Reason / Client / Location" class="textarea"></textarea>
            </div>

            <button class="btn btn-primary w-full">
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