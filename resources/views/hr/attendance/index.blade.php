@extends('layouts.app')

@section('title', 'Attendance Reports')

@section('content')

    <style>
        /* Month picker icon */
        input[type="month"]::-webkit-calendar-picker-indicator,
        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
        }
        .dark input[type="month"]::-webkit-calendar-picker-indicator,
        .dark input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.9;
        }
    </style>

    @php
        use Carbon\Carbon;
        $displayMonth = Carbon::createFromFormat('Y-m', $monthInput ?? now()->format('Y-m'))->format('F Y');
    @endphp

    <div>

        {{-- HEADER --}}
        <div class="page-head">
            <div>
                <h1 class="page-title">
                    <span class="page-ico"><i data-lucide="clipboard-list" class="w-6 h-6"></i></span>
                    Attendance Reports
                </h1>
                <p class="mt-1 muted">{{ $displayMonth }} overview</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                @if (auth()->user()->role === 'hr')
                    <button onclick="openHolidayModal()" class="btn btn-primary">
                        <i data-lucide="calendar-plus" class="w-5 h-5"></i>
                        Mark Holiday
                    </button>
                @endif
                <a href="{{ route('hr.attendance.export.all', ['month' => $monthInput]) }}" class="btn btn-success">
                    <i data-lucide="file-down" class="w-5 h-5"></i>
                    Export All (Excel)
                </a>
            </div>
        </div>

        {{-- DATE RANGE EXPORT --}}
        <div class="card p-6 md:p-8 mb-8">

            <div class="flex items-center gap-3 mb-5">
                <div class="stat-ico !w-11 !h-11">
                    <i data-lucide="file-spreadsheet" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="font-display font-semibold text-lg text-[var(--text)]">
                        Export Attendance by Date Range
                    </h3>
                    <p class="text-sm muted">
                        Select a start date and end date to export attendance records.
                    </p>
                </div>
            </div>

            <form action="{{ route('hr.attendance.export.range') }}" method="GET">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                    <x-filter-field icon="calendar" label="Start Date">
                        <input type="date" name="start_date" required class="input">
                    </x-filter-field>

                    <x-filter-field icon="calendar" label="End Date">
                        <input type="date" name="end_date" required class="input">
                    </x-filter-field>

                    <x-filter-field icon="users" label="Staff (Optional)">
                        <select name="staff" class="select">
                            <option value="">All Staff</option>
                            @foreach ($allStaff as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </x-filter-field>

                    <div>
                        <button type="submit" class="btn btn-success w-full">
                            <i data-lucide="file-down" class="w-5 h-5"></i>
                            Export Excel
                        </button>
                    </div>

                </div>

            </form>
        </div>

        {{-- FILTERS --}}
        <x-filter-bar :action="route('hr.attendance.index')">
            <x-filter-field icon="calendar" label="Month">
                <input type="month" name="month" value="{{ $monthInput }}" class="input">
            </x-filter-field>

            <x-filter-field icon="users" label="Staff">
                <select name="staff" class="select">
                    <option value="">All Staff</option>
                    @foreach ($allStaff as $user)
                        <option value="{{ $user->id }}" {{ ($staffId ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ ucfirst($user->role) }})
                        </option>
                    @endforeach
                </select>
            </x-filter-field>

            <x-filter-actions submit="Apply" :reset="route('hr.attendance.index')" />
        </x-filter-bar>

        {{-- INSIGHTS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

            <div class="stat-card tint-magenta">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[.68rem] font-bold uppercase tracking-wider muted">Star Performer</p>
                    </div>
                    <span class="stat-ico"><i data-lucide="trophy" class="w-5 h-5"></i></span>
                </div>
                <h3 class="text-sm font-bold text-[var(--text)] mt-2">{{ $bestAttendance?->salesman?->name ?? '--' }}</h3>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-2xl font-display font-bold text-[var(--text)]">{{ $bestAttendance->presents ?? 0 }}</span>
                    <span class="text-[10px] muted font-medium uppercase">days</span>
                </div>
            </div>

            <div class="stat-card tint-rose">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[.68rem] font-bold uppercase tracking-wider muted">Most Leaves</p>
                    </div>
                    <span class="stat-ico"><i data-lucide="x-circle" class="w-5 h-5"></i></span>
                </div>
                <h3 class="text-sm font-bold text-[var(--text)] mt-2">{{ $mostLeaves?->salesman?->name ?? '--' }}</h3>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-2xl font-display font-bold text-[var(--text)]">{{ $mostLeaves->leaves ?? 0 }}</span>
                    <span class="text-[10px] muted font-medium uppercase">leaves</span>
                </div>
            </div>

            <div class="stat-card tint-cyan">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[.68rem] font-bold uppercase tracking-wider muted">Hardest Worker</p>
                    </div>
                    <span class="stat-ico"><i data-lucide="clock" class="w-5 h-5"></i></span>
                </div>
                <h3 class="text-sm font-bold text-[var(--text)] mt-2">{{ $hardestWorker?->salesman?->name ?? '--' }}</h3>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-2xl font-display font-bold text-[var(--text)]">{{ number_format(($hardestWorker->minutes ?? 0) / 60, 1) }}</span>
                    <span class="text-[10px] muted font-medium uppercase">hours</span>
                </div>
            </div>

            <div class="stat-card tint-violet">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[.68rem] font-bold uppercase tracking-wider muted">Attendance Health</p>
                    </div>
                    <span class="stat-ico"><i data-lucide="heart-pulse" class="w-5 h-5"></i></span>
                </div>
                <div class="flex items-baseline gap-1.5 mt-2">
                    <span class="text-2xl font-display font-bold text-[var(--text)]">{{ $attendanceRate }}%</span>
                </div>
                <div class="stat-bar mt-3 relative"><div style="width: {{ $attendanceRate }}%"></div></div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="tbl-wrap hidden md:block overflow-x-auto">
            <table class="tbl min-w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-center">Present</th>
                        <th class="text-center">Leaves</th>
                        <th class="text-center">Short</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($staff as $i => $user)
                        <tr>
                            <td class="muted">{{ $i + 1 }}</td>

                            <td class="font-semibold">{{ $user->name }}</td>

                            <td class="muted">{{ $user->email }}</td>

                            <td>
                                <span class="badge badge-gray uppercase">{{ ucfirst($user->role) }}</span>
                            </td>

                            <td class="text-center">
                                <span class="badge badge-emerald font-bold">{{ $user->monthAttendance }}</span>
                            </td>

                            <td class="text-center">
                                <span class="badge badge-rose font-bold">{{ $user->monthLeaves }}</span>
                            </td>

                            <td class="text-center">
                                <span class="badge badge-orange font-bold">{{ $user->shortLeaves }}</span>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('hr.attendance.staff', ['id' => $user->id, 'month' => $monthInput]) }}"
                                    class="btn btn-primary btn-sm">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center muted">
                                No records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MOBILE VIEW (CARDS) --}}
    <div class="md:hidden space-y-4 mb-6">

        @forelse($staff as $u)

            <div class="card p-4">

                {{-- Name --}}
                <div class="text-lg font-semibold text-[var(--text)] flex items-center">
                    <i data-lucide="user" class="w-5 h-5 mr-2 text-[var(--accent)]"></i>
                    {{ $u->name }}
                </div>

                {{-- Email --}}
                <div class="muted text-sm flex items-center">
                    <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                    {{ $u->email }}
                </div>

                {{-- Stats --}}
                <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">

                    <div class="soft-panel p-3 rounded-lg">
                        <div class="muted flex items-center">
                            <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                            Present
                        </div>
                        <div class="text-emerald-500 font-semibold text-lg">
                            {{ $u->monthAttendance }}
                        </div>
                    </div>

                    <div class="soft-panel p-3 rounded-lg">
                        <div class="muted flex items-center">
                            <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i>
                            Leaves
                        </div>
                        <div class="text-rose-500 font-semibold text-lg">
                            {{ $u->monthLeaves }}
                        </div>
                    </div>

                    <div class="soft-panel p-3 rounded-lg">
                        <div class="muted flex items-center">
                            <i data-lucide="clock" class="w-4 h-4 mr-2"></i>
                            Short
                        </div>
                        <div class="text-orange-500 font-semibold text-lg">
                            {{ $u->shortLeaves }}
                        </div>
                    </div>

                    <div class="soft-panel p-3 rounded-lg col-span-2 sm:col-span-3">
                        <div class="muted flex items-center">
                            <i data-lucide="badge-check" class="w-4 h-4 mr-2"></i>
                            Role
                        </div>
                        <div class="text-[var(--text)] font-semibold uppercase">
                            {{ ucfirst($u->role) }}
                        </div>
                    </div>

                </div>

                {{-- Action --}}
                <div class="mt-4">
                    <a href="{{ route('hr.attendance.staff', ['id' => $u->id, 'month' => $monthInput]) }}"
                       class="btn btn-primary w-full">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        View Attendance
                    </a>
                </div>

            </div>

        @empty
            <div class="text-center muted py-10">
                No records found
            </div>
        @endforelse

    </div>

    {{-- HOLIDAY MODAL --}}
    <div id="holidayModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center overflow-y-auto p-4 z-[10000] backdrop-blur-sm">

        <div class="card !rounded-2xl p-6 w-full max-w-md relative my-auto">
            <button onclick="closeHolidayModal()" class="icon-btn absolute top-4 right-4">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>

            <h3 class="font-display font-semibold text-lg text-[var(--text)] mb-4 flex items-center gap-2">
                <i data-lucide="calendar-plus" class="w-5 h-5 text-[var(--accent)]"></i>
                Mark Company Holiday
            </h3>

            <form method="POST" action="{{ route('hr.holiday.store') }}">
                @csrf

                {{-- ERROR DISPLAY (CRITICAL) --}}
                @if ($errors->any())
                    <div class="alert alert-error mb-4">
                        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-px"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>• {{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- SUCCESS --}}
                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <label class="label">Holiday Title</label>
                <input type="text" name="title" required class="input mb-3" placeholder="Holiday Title">

                <label class="label">Start Date</label>
                <input type="date" name="start_date" required class="input mb-3">

                <label class="label">End Date (optional)</label>
                <input type="date" name="end_date" class="input mb-4">

                {{-- SUBMIT BUTTON --}}
                <button type="submit" class="btn btn-primary w-full">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    Save Holiday
                </button>
            </form>

        </div>
    </div>

    <script>
        function openHolidayModal() {
            document.getElementById('holidayModal').classList.remove('hidden');
        }

        function closeHolidayModal() {
            document.getElementById('holidayModal').classList.add('hidden');
        }

        function toggleHolidayRange() {
            const type = document.getElementById('holidayType').value;
            document.getElementById('singleDate').classList.toggle('hidden', type === 'range');
            document.getElementById('rangeDate').classList.toggle('hidden', type !== 'range');
        }
    </script>

@endsection