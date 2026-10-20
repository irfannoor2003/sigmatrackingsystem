@extends('layouts.app')

@section('title','My Attendance')

@section('content')

@include('partials.late-reason-modal', ['lateRecord' => $pendingLateRecord ?? null])

<div class="space-y-6" style="--brand: #eb268f">
    <div class="page-head">
        <div>
            <h1 class="page-title">
                <span class="page-ico"><i data-lucide="clock" class="w-6 h-6"></i></span>
                My Attendance
            </h1>
            <p class="mt-2 text-sm muted">Record your working hours and manage leave requests.</p>
        </div>
        <span class="badge badge-gray !px-3 !py-1.5">{{ now()->format('d M Y') }}</span>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 items-start">
        <div class="card p-5 sm:p-6">
            @php
                $isLeave   = $attendance && $attendance->status === 'leave';
                $isNonWorkingDay = $isNonWorkingDay ?? false;
                $nonWorkingReason = $nonWorkingReason ?? '';
            @endphp

            <div class="soft-panel p-4">
                <div class="flex flex-wrap items-center gap-3">
                    @if($isNonWorkingDay)
                        <span class="badge badge-violet !px-3 !py-1.5">Non-working Day — {{ $nonWorkingReason }}</span>
                    @elseif($isLeave)
                        <span class="badge badge-red !px-3 !py-1.5">On Leave</span>
                    @elseif(!$attendance || !$attendance->clock_in)
                        <span class="badge badge-amber !px-3 !py-1.5">Not Clocked In</span>
                    @elseif(!$attendance->clock_out)
                        <span class="badge badge-emerald !px-3 !py-1.5">Working</span>
                    @else
                        <span class="badge badge-sky !px-3 !py-1.5">Completed</span>
                    @endif
                </div>

                @if($isLeave && $attendance->note)
                    <p class="mt-3 text-sm text-[var(--text-soft)] flex items-start gap-2">
                        <i data-lucide="file-text" class="w-4 h-4 mt-0.5 shrink-0 text-[var(--text-muted)]"></i>
                        <span>Reason: {{ $attendance->note }}</span>
                    </p>
                @endif

                <div class="grid grid-cols-2 gap-3 mt-4">
                    <div class="card-flat p-3">
                        <p class="text-xs uppercase tracking-wider muted">Clock In</p>
                        <p class="mt-1 font-semibold text-[var(--text)]">{{ $attendance->clock_in ?? '--:--' }}</p>
                    </div>
                    <div class="card-flat p-3">
                        <p class="text-xs uppercase tracking-wider muted">Clock Out</p>
                        <p class="mt-1 font-semibold text-[var(--text)]">{{ $attendance->clock_out ?? '--:--' }}</p>
                    </div>
                </div>

                @if($attendance && $attendance->clock_in && !$isLeave)
                    <p class="mt-3 text-sm muted">Working Time: <strong class="text-[var(--text-soft)]">{{ $attendance->working_duration }}</strong></p>
                @endif
            </div>

            @if($isNonWorkingDay)
                <div class="alert alert-info mt-4">
                    <i data-lucide="calendar-off" class="w-5 h-5 shrink-0 mt-px"></i>
                    <span>Attendance actions are disabled: {{ $nonWorkingReason }}</span>
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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-5">
                <form id="clockinForm" method="POST" action="{{ $clockInRoute }}">
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
                        class="btn btn-success w-full">
                        <i data-lucide="scan-line" class="w-4 h-4"></i>
                        Scan and Clock In
                    </button>
                </form>

                <form id="clockoutForm" method="POST" action="{{ $clockOutRoute }}">
                    @csrf
                    <input type="hidden" name="lat" id="clockout_lat">
                    <input type="hidden" name="lng" id="clockout_lng">

                    <button type="button"
                        onclick="clockOut()"
                        @if($isNonWorkingDay || $isLeave || !$attendance || !$attendance->clock_in || $attendance->clock_out)
                            disabled
                        @endif
                        class="btn btn-danger w-full">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Clock Out
                    </button>
                </form>
            </div>

            @if(!$isNonWorkingDay && !$isLeave && (!$attendance || !$attendance->clock_in) && !$hideLeaveButton)
                <button
                    onclick="document.getElementById('leaveModal').classList.remove('hidden')"
                    class="btn btn-outline w-full mt-3">
                    <i data-lucide="calendar-plus" class="w-4 h-4"></i>
                    Request Leave
                </button>
            @endif

            @if($hideLeaveButton)
                <p class="text-xs text-red-500 text-center mt-3">Leave requests are disabled after 12:00 PM</p>
            @endif
        </div>

        <div class="space-y-4">
            <a href="{{ $historyRoute }}" class="card card-hover p-5 flex items-center gap-4">
                <span class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0">
                    <i data-lucide="calendar-days" class="w-5 h-5"></i>
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block font-semibold text-[var(--text)]">View Monthly Attendance History</span>
                    <span class="block text-sm muted mt-1">Review your daily status and hours.</span>
                </span>
                <i data-lucide="arrow-right" class="w-5 h-5 text-[var(--text-muted)]"></i>
            </a>

            <a href="http://portal.sessigmasoft.com/uploads/image.png" download class="btn btn-soft w-full">
                <i data-lucide="qr-code" class="w-4 h-4"></i>
                Download QR Code
            </a>
        </div>
    </div>
</div>

<div id="leaveModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-4">
    <div class="card p-6 w-full max-w-md">
        <div class="flex items-center gap-3 mb-5">
            <span class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                <i data-lucide="calendar-plus" class="w-5 h-5"></i>
            </span>
            <h3 class="text-xl font-display font-semibold text-[var(--text)]">Leave Reason</h3>
        </div>

        <form method="POST" action="{{ route('attendance.leave') }}">
            @csrf
            <textarea name="note" required rows="4" class="textarea resize-none" placeholder="Explain your reason"></textarea>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="document.getElementById('leaveModal').classList.add('hidden')" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-danger">Submit Leave</button>
            </div>
        </form>
    </div>
</div>

<div id="qrModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 p-4">
    <div class="card p-6 w-full max-w-md">
        <div class="flex items-center gap-3 mb-5">
            <span class="w-9 h-9 rounded-xl bg-[var(--accent-soft)] text-[var(--brand)] flex items-center justify-center">
                <i data-lucide="scan-line" class="w-5 h-5"></i>
            </span>
            <h3 class="text-xl font-display font-semibold text-[var(--text)]">Scan Office QR Code</h3>
        </div>

        <div id="qrPreview" class="w-full rounded-xl mb-4 overflow-hidden bg-black" style="min-height: 250px;"></div>
        <p id="scannerStatus" class="text-[var(--text)] text-center text-sm">Scanning QR...</p>
        <p class="text-sm text-center muted">GPS and QR are required</p>

        <button onclick="closeQRModal()" class="btn btn-danger w-full mt-4">Cancel</button>
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
