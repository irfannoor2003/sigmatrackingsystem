@extends('layouts.app')

@section('title','My Attendance')

@section('content')

@include('partials.late-reason-modal', ['lateRecord' => $pendingLateRecord ?? null])

<div class="max-w-xl mx-auto mt-10 px-0">
<div class="card !rounded-3xl border border-[var(--border)] p-6 shadow-card-lg">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-[var(--text)] tracking-wide flex items-center">
            <i data-lucide="clock" class="w-6 h-6 mr-2 text-[var(--accent)]"></i>
            My Attendance
        </h2>
        <span class="text-sm muted">
            {{ now()->format('d M Y') }}
        </span>
    </div>

    @php
        $isLeave   = $attendance && $attendance->status === 'leave';
        $isNonWorkingDay = $isNonWorkingDay ?? false;
        $nonWorkingReason = $nonWorkingReason ?? '';
    @endphp

    {{-- Status --}}
    <div class="mb-6 p-4 rounded-2xl soft-panel border border-[var(--border)]">
        @if($isNonWorkingDay)
            <span class="badge badge-violet">
                {{ $nonWorkingReason }}
            </span>

        @elseif($isLeave)
            <span class="badge badge-red">
                On Leave
            </span>
            @if($attendance->note)
                <p class="mt-2 text-sm text-[var(--text-soft)]">
                    Reason: {{ $attendance->note }}
                </p>
            @endif

        @elseif(!$attendance || !$attendance->clock_in)
            <span class="badge badge-amber">
                Not Clocked In
            </span>

        @elseif(!$attendance->clock_out)
            <span class="badge badge-emerald">
                Working
            </span>

        @else
            <span class="badge badge-sky">
                Completed
            </span>
        @endif

        {{-- Clock In / Out Times --}}
        <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
            <div class="bg-[var(--surface-3)] p-3 rounded-xl">
                <p class="text-[var(--text-muted)]">Clock In</p>
                <p class="text-[var(--text)] font-semibold">{{ $attendance->clock_in ?? '--:--' }}</p>
            </div>

            <div class="bg-[var(--surface-3)] p-3 rounded-xl">
                <p class="text-[var(--text-muted)]">Clock Out</p>
                <p class="text-[var(--text)] font-semibold">{{ $attendance->clock_out ?? '--:--' }}</p>
            </div>
        </div>

        @if($attendance && $attendance->clock_in && !$isLeave)
            <div class="mt-3 text-sm text-[var(--text-soft)]">
                Working Time: <strong class="text-[var(--text)]">{{ $attendance->working_duration }}</strong>
            </div>
        @endif
    </div>

    {{-- Non-working day banner --}}
    @if($isNonWorkingDay)
        <div class="mb-4 p-3 rounded-xl alert alert-info text-sm">
            Attendance actions are disabled: {{ $nonWorkingReason }}
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
                class="btn btn-success w-full !py-3
                    disabled:opacity-40 disabled:cursor-not-allowed">
                <i data-lucide="camera" class="w-5 h-5"></i>
                Scan & Clock In
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
                class="btn btn-danger w-full !py-3
                    disabled:opacity-40 disabled:cursor-not-allowed">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                Clock Out
            </button>
        </form>
    </div>

    {{-- Request Leave --}}
    @if(!$isNonWorkingDay && !$isLeave && (!$attendance || !$attendance->clock_in) && !$hideLeaveButton)
        <button
            onclick="document.getElementById('leaveModal').classList.remove('hidden')"
            class="w-full mb-4 !py-3 rounded-xl font-semibold text-white
                bg-gradient-to-r from-orange-500 to-red-600 hover:opacity-90 transition">
            <i data-lucide="thermometer" class="w-5 h-5"></i>
            Request Leave
        </button>
    @endif
    @if($hideLeaveButton)
        <p class="text-xs text-red-500 text-center mb-0 mt-3">
            Leave requests are disabled after 12:00 PM
        </p>
    @endif

    {{-- Monthly History --}}
    <div class="mt-4 text-center">
        <a href="{{ $historyRoute }}"
           class="text-[var(--accent)] hover:underline text-sm font-semibold">
            View Monthly Attendance History
        </a>
    </div>

    {{-- Download QR Code --}}
    <div class="mt-3 text-center">
        <a href="http://portal.sessigmasoft.com/uploads/image.png" download
           class="btn btn-soft btn-sm">
            <i data-lucide="qr-code" class="w-4 h-4"></i>
            Download QR Code
        </a>
    </div>

</div>
</div>

{{-- Leave Modal --}}
<div id="leaveModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center
        bg-black/70 backdrop-blur-xl p-4">
    <div class="relative w-full max-w-md card !rounded-3xl shadow-card-lg p-6">

        <h3 class="text-2xl font-bold text-[var(--text)] mb-6">Leave Reason</h3>

        <form method="POST" action="{{ route('attendance.leave') }}">
            @csrf
            <textarea name="note" required rows="4"
                class="textarea w-full resize-none"
                placeholder="Explain your reason"></textarea>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button"
                    onclick="document.getElementById('leaveModal').classList.add('hidden')"
                    class="btn btn-ghost">
                    Cancel
                </button>

                <button type="submit" class="btn btn-danger">
                    Submit Leave
                </button>
            </div>
        </form>
    </div>
</div>

{{-- QR SCANNER MODAL --}}
<div id="qrModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center
        bg-black/80 backdrop-blur-xl p-4">

    <div class="relative w-full max-w-md card !rounded-3xl shadow-card-lg p-6">

        <h3 class="text-xl font-bold text-[var(--text)] mb-4 text-center">
            Scan Office QR Code
        </h3>

        <div id="qrPreview" class="w-full rounded-xl mb-4 overflow-hidden bg-black" style="min-height: 250px;"></div>
        <p id="scannerStatus" class="text-[var(--text)] text-center text-sm">Scanning QR...</p>
        <p class="text-sm text-center muted">GPS + QR required</p>

        <button onclick="closeQRModal()" class="btn btn-danger w-full mt-4">
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