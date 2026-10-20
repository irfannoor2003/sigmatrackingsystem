@extends('layouts.app')

@section('title', 'Attendance Reports')

@section('content')

    @php
        use Carbon\Carbon;
        $displayMonth = Carbon::createFromFormat('Y-m', $monthInput ?? now()->format('Y-m'))->format('F Y');
    @endphp

    <div class="p-0">

        {{-- HEADER --}}
        <div class="page-head !mb-8 !flex-col !items-start sm:!flex-row sm:!items-center gap-4 !p-0">
            <h1 class="page-title">
                <span class="page-ico"><i data-lucide="clipboard-list" class="w-6 h-6"></i></span>
                Attendance Reports
            </h1>
            <span class="badge badge-gray">{{ $displayMonth }} overview</span>

            <div class="flex flex-col sm:flex-row gap-3 lg:ml-auto w-full lg:w-auto">
                @if (auth()->user()->role === 'admin')
                    <button onclick="openHolidayModal()" class="btn btn-primary w-full sm:w-auto">
                        <i data-lucide="calendar-plus" class="w-5 h-5"></i>
                        Mark Holiday
                    </button>
                @endif
                <a href="{{ route('admin.attendance.export.all', ['month' => $monthInput]) }}" class="btn btn-success w-full sm:w-auto">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Export All (Excel)
                </a>
            </div>
        </div>

        {{-- DATE RANGE EXPORT --}}
        <div class="card !rounded-xl p-6 md:p-8 mb-8">

            <div class="flex items-center gap-3 mb-5">
                <div class="stat-ico !w-11 !h-11 !text-emerald-500">
                    <i data-lucide="file-spreadsheet" class="w-6 h-6"></i>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-[var(--text)]">
                        Export Attendance by Date Range
                    </h3>
                    <p class="text-sm muted">
                        Select a start date and end date to export attendance records.
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.attendance.export.range') }}">

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
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </x-filter-field>

                    <div>
                        <button type="submit" class="btn btn-success w-full !py-3">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Export Excel
                        </button>
                    </div>

                </div>

            </form>
        </div>

        {{-- FILTERS --}}
        <x-filter-bar :action="route('admin.attendance.index')">
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

            <x-filter-actions submit="Apply" :reset="route('admin.attendance.index')" />
        </x-filter-bar>

        {{-- INSIGHTS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

            <div class="stat-card tint-emerald">
                <div>
                    <p class="text-[.68rem] font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-1">Star Performer</p>
                    <p class="text-sm muted font-medium mb-1.5">{{ $bestAttendance?->salesman?->name ?? '--' }}</p>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-display font-extrabold text-[var(--text)]">{{ $bestAttendance->presents ?? 0 }}</span>
                        <span class="text-[10px] text-emerald-500/80 font-medium uppercase">days</span>
                    </div>
                </div>
                <span class="stat-ico"><i data-lucide="trophy" class="w-6 h-6"></i></span>
                <div class="stat-bar"><div style="width: 100%"></div></div>
            </div>

            <div class="stat-card tint-red">
                <div>
                    <p class="text-[.68rem] font-bold uppercase tracking-widest text-red-600 dark:text-red-400 mb-1">Most Leaves</p>
                    <p class="text-sm muted font-medium mb-1.5">{{ $mostLeaves?->salesman?->name ?? '--' }}</p>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-display font-extrabold text-[var(--text)]">{{ $mostLeaves->leaves ?? 0 }}</span>
                        <span class="text-[10px] text-red-500/80 font-medium uppercase">leaves</span>
                    </div>
                </div>
                <span class="stat-ico"><i data-lucide="x-circle" class="w-6 h-6"></i></span>
                <div class="stat-bar"><div style="width: 100%"></div></div>
            </div>

            <div class="stat-card tint-cyan">
                <div>
                    <p class="text-[.68rem] font-bold uppercase tracking-widest text-sky-600 dark:text-sky-400 mb-1">Hardest Worker</p>
                    <p class="text-sm muted font-medium mb-1.5">{{ $hardestWorker?->salesman?->name ?? '--' }}</p>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-display font-extrabold text-[var(--text)]">{{ number_format(($hardestWorker->minutes ?? 0) / 60, 1) }}</span>
                        <span class="text-[10px] text-sky-500/80 font-medium uppercase">hours</span>
                    </div>
                </div>
                <span class="stat-ico"><i data-lucide="clock" class="w-6 h-6"></i></span>
                <div class="stat-bar"><div style="width: 100%"></div></div>
            </div>

            <div class="stat-card tint-violet">
                <div>
                    <p class="text-[.68rem] font-bold uppercase tracking-widest text-violet-600 dark:text-violet-400 mb-1">Attendance Health</p>
                    <p class="text-sm muted font-medium mb-1.5">Overall rate</p>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-display font-extrabold text-[var(--text)]">{{ $attendanceRate }}%</span>
                    </div>
                    <div class="w-full h-1 bg-[var(--accent-softer)] rounded-full overflow-hidden mt-2">
                        <div class="h-full rounded-full gradient-surface" style="width: {{ $attendanceRate }}%"></div>
                    </div>
                </div>
                <span class="stat-ico"><i data-lucide="heart-pulse" class="w-6 h-6"></i></span>
                <div class="stat-bar"><div style="width: {{ $attendanceRate }}%"></div></div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="hidden md:block card !rounded-xl overflow-hidden">
            <div class="tbl-wrap !border-0 !shadow-none">
                <table class="tbl">
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
                                <td class="font-semibold text-[var(--text)]">{{ $user->name }}</td>
                                <td class="text-[var(--text-soft)]">{{ $user->email }}</td>
                                <td>
                                    <span class="badge badge-gray capitalize">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-emerald font-bold">{{ $user->monthAttendance }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-red font-bold">{{ $user->monthLeaves }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-orange font-bold">{{ $user->shortLeaves }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.attendance.staff', ['id' => $user->id, 'month' => $monthInput]) }}"
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
    </div>
    {{-- ▌▌ MOBILE VIEW (CARDS) --}}
    <div class="md:hidden space-y-4 mb-6">

        @forelse($staff as $u)
            <div class="card !rounded-xl p-4">

                {{-- Name --}}
                <div class="text-lg font-semibold text-[var(--text)] flex items-center">
                    <i data-lucide="user" class="w-5 h-5 mr-2 text-[var(--accent)]"></i>
                    {{ $u->name }}
                </div>

                {{-- Email --}}
                <div class="text-[var(--text-soft)] text-sm flex items-center">
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
                        <div class="text-emerald-600 dark:text-emerald-400 font-semibold text-lg">
                            {{ $u->monthAttendance }}
                        </div>
                    </div>

                    <div class="soft-panel p-3 rounded-lg">
                        <div class="muted flex items-center">
                            <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i>
                            Leaves
                        </div>
                        <div class="text-red-600 dark:text-red-400 font-semibold text-lg">
                            {{ $u->monthLeaves }}
                        </div>
                    </div>

                    <div class="soft-panel p-3 rounded-lg">
                        <div class="muted flex items-center">
                            <i data-lucide="clock" class="w-4 h-4 mr-2"></i>
                            Short
                        </div>
                        <div class="text-orange-600 dark:text-orange-400 font-semibold text-lg">
                            {{ $u->shortLeaves }}
                        </div>
                    </div>

                    <div class="soft-panel p-3 rounded-lg col-span-2 sm:col-span-3">
                        <div class="muted flex items-center">
                            <i data-lucide="badge-check" class="w-4 h-4 mr-2"></i>
                            Role
                        </div>
                        <div class="text-[var(--text)] font-semibold">
                            {{ ucfirst($u->role) }}
                        </div>
                    </div>

                </div>

                {{-- Action --}}
                <div class="mt-4">
                    <a href="{{ route('admin.attendance.staff', ['id' => $u->id, 'month' => $monthInput]) }}" class="btn btn-primary btn-sm block w-full text-center">
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
    <div id="holidayModal" class="hidden fixed inset-0 z-[10000] flex items-center justify-center overflow-y-auto bg-black/65 p-4 backdrop-blur-sm" onclick="closeHolidayModal()">

        <div class="card relative w-full max-w-md my-auto p-6 shadow-card-lg" onclick="event.stopPropagation()">
            <button type="button" onclick="closeHolidayModal()" class="icon-btn absolute right-4 top-4" title="Close">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>

            <h3 class="mb-5 flex items-center gap-2 pr-10 font-display text-xl font-semibold text-[var(--text)]">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[var(--accent-soft)] text-[var(--accent)]">
                    <i data-lucide="calendar-plus" class="w-4 h-4"></i>
                </span>
                Mark Company Holiday
            </h3>

            <form method="POST" action="{{ route('admin.holiday.store') }}">
                @csrf

                {{-- ERROR DISPLAY --}}
                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-xl alert alert-error text-sm">
                        @foreach ($errors->all() as $error)
                            <div>• {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                {{-- SUCCESS --}}
                @if (session('success'))
                    <div class="mb-4 p-3 rounded-xl alert alert-success text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="input-icon-wrap mb-3">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                    <input type="text" name="title" required class="input" placeholder="Holiday Title">
                </div>

                <label class="label">Start Date</label>
                <div class="input-icon-wrap mb-3">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <input type="date" name="start_date" required class="input">
                </div>

                <label class="label">End Date (optional)</label>
                <div class="input-icon-wrap mb-4">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <input type="date" name="end_date" class="input">
                </div>

                {{-- SUBMIT BUTTON --}}
                <button type="submit" class="btn btn-primary w-full !py-3">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Save Holiday
                </button>
            </form>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('holidayModal');
            const host = document.getElementById('modal-host');

            if (modal && host) {
                host.appendChild(modal);
            }
        });

        function openHolidayModal() {
            const modal = document.getElementById('holidayModal');
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            modal.querySelector('input')?.focus();
        }

        function closeHolidayModal() {
            const modal = document.getElementById('holidayModal');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !document.getElementById('holidayModal')?.classList.contains('hidden')) {
                closeHolidayModal();
            }
        });
    </script>

@endsection