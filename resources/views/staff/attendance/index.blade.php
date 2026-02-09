@extends('layouts.app')

@section('title','My Attendance')

@section('content')
<div class="max-w-xl mx-auto mt-10 px-0">
<div class="glass border border-white/20 p-6 rounded-3xl shadow-2xl">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-white tracking-wide">
            ⏱ My Attendance
        </h2>
        <span class="text-sm text-white/70">
            {{ now()->format('d M Y') }}
        </span>
    </div>

    @php
        $isLeave   = $attendance && $attendance->status === 'leave';
        $isNonWorkingDay = $isNonWorkingDay ?? false;
        $nonWorkingReason = $nonWorkingReason ?? '';
    @endphp

    {{-- Status --}}
    <div class="mb-6 p-4 rounded-2xl bg-white/10 border border-white/20">
        @if($isNonWorkingDay)
            <span class="px-3 py-1 rounded-full bg-purple-500/20 text-purple-300 text-sm font-semibold">
                🎉 Non-working Day — {{ $nonWorkingReason }}
            </span>

        @elseif($isLeave)
            <span class="px-3 py-1 rounded-full bg-red-500/20 text-red-300 text-sm font-semibold">
                🚫 On Leave
            </span>
            @if($attendance->note)
                <p class="mt-2 text-sm text-white/80">
                    📝 Reason: {{ $attendance->note }}
                </p>
            @endif

        @elseif(!$attendance || !$attendance->clock_in)
            <span class="px-3 py-1 rounded-full bg-yellow-500/20 text-yellow-300 text-sm font-semibold">
                ⚠ Not Clocked In
            </span>

        @elseif(!$attendance->clock_out)
            <span class="px-3 py-1 rounded-full bg-green-500/20 text-green-300 text-sm font-semibold">
                🟢 Working
            </span>

        @else
            <span class="px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-sm font-semibold">
                ✅ Completed
            </span>
        @endif

        {{-- Clock In / Out Times --}}
        <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
            <div class="bg-black/20 p-3 rounded-xl">
                <p class="text-white/60">Clock In</p>
                <p class="text-white font-semibold">{{ $attendance->clock_in ?? '--:--' }}</p>
            </div>

            <div class="bg-black/20 p-3 rounded-xl">
                <p class="text-white/60">Clock Out</p>
                <p class="text-white font-semibold">{{ $attendance->clock_out ?? '--:--' }}</p>
            </div>
        </div>

        @if($attendance && $attendance->clock_in && !$isLeave)
            <div class="mt-3 text-sm text-white/70">
                ⏳ Working Time: <strong>{{ $attendance->working_duration }}</strong>
            </div>
        @endif
    </div>

    {{-- Non-working day banner --}}
    @if($isNonWorkingDay)
        <div class="mb-4 p-3 rounded-xl bg-purple-500/10 border border-purple-500/30 text-purple-300 text-sm">
            🎉 Attendance actions are disabled: {{ $nonWorkingReason }}
        </div>
    @endif

    @php
        $role = auth()->user()->role;

        $clockInRoute = $role === 'salesman'
            ? route('salesman.attendance.clockin')
            : route('staff.attendance.clockin');

        $clockOutRoute = $role === 'salesman'
            ? route('salesman.attendance.clockout')
            : route('staff.attendance.clockout');

        $historyRoute = $role === 'salesman'
            ? route('salesman.attendance.history')
            : route('staff.attendance.history');
    @endphp

    {{-- Actions --}}
    <div class="flex flex-col gap-3 md:flex-row md:gap-4 w-full mb-2">

        {{-- Clock In --}}
        <form id="clockinForm" method="POST" action="{{ $clockInRoute }}" class="w-full md:flex-1">
            @csrf
            <input type="hidden" name="lat" id="clockin_lat">
            <input type="hidden" name="lng" id="clockin_lng">
            <input type="hidden" name="qr_token" id="qr_token">

            <button type="button"
                onclick="openQRModal()"
                title="{{ $hideClockInButton ? 'Clock-in not allowed after 3:00 PM' : '' }}"
                @if($isNonWorkingDay || $isLeave || $hideClockInButton || ($attendance && $attendance->clock_in))
                    disabled
                @endif
                class="w-full py-3 rounded-xl font-semibold text-white
                    bg-gradient-to-r from-green-500 to-emerald-600
                    hover:opacity-90 transition shadow-lg
                    disabled:opacity-40 disabled:cursor-not-allowed">
                📷 Scan & Clock In
            </button>
        </form>

        {{-- Clock Out --}}
        <form id="clockoutForm" method="POST" action="{{ $clockOutRoute }}" class="w-full md:flex-1">
            @csrf
            <input type="hidden" name="lat" id="clockout_lat">
            <input type="hidden" name="lng" id="clockout_lng">

            <button type="button"
                onclick="clockOut()"
                @if($isNonWorkingDay || $isLeave || !$attendance || !$attendance->clock_in || $attendance->clock_out)
                    disabled
                @endif
                class="w-full py-3 rounded-xl font-semibold text-white
                    bg-gradient-to-r from-red-500 to-pink-600
                    hover:opacity-90 transition shadow-lg
                    disabled:opacity-40 disabled:cursor-not-allowed">
                🚪 Clock Out
            </button>
        </form>
    </div>

    {{-- Request Leave --}}
    @if(!$isNonWorkingDay && !$isLeave && (!$attendance || !$attendance->clock_in) && !$hideLeaveButton)
        <button
            onclick="document.getElementById('leaveModal').classList.remove('hidden')"
            class="w-full mb-4 py-3 rounded-xl font-semibold text-white
                bg-gradient-to-r from-orange-500 to-red-600 hover:opacity-90">
            🤒 Request Leave
        </button>
    @endif
    @if($hideLeaveButton)
        <p class="text-xs text-red-400 text-center mb-0 mt-3">
            ⏰ Leave requests are disabled after 12:00 PM
        </p>
    @endif

    {{-- Monthly History --}}
    <div class="mt-4 text-center">
        <a href="{{ $historyRoute }}"
           class="text-indigo-300 hover:underline text-sm font-semibold">
            📅 View Monthly Attendance History
        </a>
    </div>

</div>
</div>

{{-- Leave Modal --}}
<div id="leaveModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center
        bg-black/70 backdrop-blur-xl p-4">
    <div class="relative w-full max-w-md bg-white/10 backdrop-blur-2xl
                border border-white/20 rounded-3xl shadow-2xl p-6">

        <h3 class="text-2xl font-bold text-white mb-6">Leave Reason</h3>

        <form method="POST" action="{{ route('attendance.leave') }}">
            @csrf
            <textarea name="note" required rows="4"
                class="w-full px-4 py-3 rounded-2xl bg-black/40 border border-white/10
                    text-white resize-none"
                placeholder="Explain your reason"></textarea>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button"
                    onclick="document.getElementById('leaveModal').classList.add('hidden')"
                    class="px-5 py-2 rounded-xl bg-white/10 text-white">
                    Cancel
                </button>

                <button type="submit"
                    class="px-5 py-2 rounded-xl bg-red-600 text-white font-semibold">
                    Submit Leave
                </button>
            </div>
        </form>
    </div>
</div>

{{-- QR SCANNER MODAL --}}
<div id="qrModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center
        bg-black/80 backdrop-blur-xl p-4">

    <div class="relative w-full max-w-md bg-white/10 backdrop-blur-2xl
                border border-white/20 rounded-3xl shadow-2xl p-6">

        <h3 class="text-xl font-bold text-white mb-4 text-center">
            📷 Scan Office QR Code
        </h3>

        <div id="qrPreview" class="w-full rounded-xl mb-4 overflow-hidden bg-black" style="min-height: 250px;"></div>
        <p id="scannerStatus" class="text-white text-center text-sm">Scanning QR...</p>
        <p class="text-sm text-center text-white/60">GPS + QR required</p>

        <button onclick="closeQRModal()"
            class="w-full mt-4 py-2 rounded-xl bg-red-600 text-white font-semibold">
            Cancel
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let qrScanner = null;

function openQRModal() {
    if (!navigator.geolocation) {
        alert('GPS not supported');
        return;
    }

    navigator.geolocation.getCurrentPosition(pos => {
        document.getElementById('clockin_lat').value = pos.coords.latitude;
        document.getElementById('clockin_lng').value = pos.coords.longitude;

        document.getElementById('qrModal').classList.remove('hidden');
        document.getElementById('scannerStatus').innerText = "Initializing camera...";

        if (!qrScanner) {
            qrScanner = new Html5Qrcode("qrPreview");
        }

        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        qrScanner.start(
            { facingMode: "environment" },
            config,
            qrCodeMessage => {
                document.getElementById('qr_token').value = qrCodeMessage;
                stopScanner();
                document.getElementById('clockinForm').submit();
            }
        ).catch(err => {
            console.error("Camera error:", err);
            document.getElementById('scannerStatus').innerText = "Camera error: " + err;
        });

    }, err => alert("GPS Error: " + err.message), { enableHighAccuracy: true });
}

function stopScanner() {
    if (qrScanner && qrScanner.isScanning) {
        qrScanner.stop().then(() => { qrScanner.clear(); }).catch(err => console.log("Stop error", err));
    }
}

function closeQRModal() {
    stopScanner();
    document.getElementById('qrModal').classList.add('hidden');
}

// Clock Out GPS only
function geoSubmit(formId, latId, lngId) {
    navigator.geolocation.getCurrentPosition(
        pos => {
            document.getElementById(latId).value = pos.coords.latitude;
            document.getElementById(lngId).value = pos.coords.longitude;
            document.getElementById(formId).submit();
        },
        err => alert(err.message),
        { enableHighAccuracy: true }
    );
}
function clockOut() { geoSubmit('clockoutForm','clockout_lat','clockout_lng'); }
</script>
@endsection
