@extends('layouts.app')

@section('title', 'Attendance Reports')

@section('content')

    <style>
        /* Month picker icon */
        input[type="month"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.9;
            cursor: pointer;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            /* Makes the calendar icon white */
            cursor: pointer;
        }

        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        table th,
        table td {
            vertical-align: middle;
        }
    </style>

    @php
        use Carbon\Carbon;
        $displayMonth = Carbon::createFromFormat('Y-m', $monthInput ?? now()->format('Y-m'))->format('F Y');
    @endphp

    <div class="max-w-6xl mx-auto mt-12 p-0">

        {{-- HEADER --}}
        <div class="relative glass p-8 rounded-2xl border border-white/20 shadow-2xl mb-8 overflow-hidden">
            <!-- Decorative blurred circle -->
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-pink-400/20 blur-3xl rounded-full hidden lg:block"></div>

            <div class="relative flex flex-col lg:flex-row lg:items-center gap-4">

                <div class="bg-white/10 p-3 rounded-2xl">
                    <i data-lucide="clipboard-list" class="w-8 h-8 text-[#ff2ba6]"></i>
                </div>

                <div>
                    <h2 class="text-3xl font-extrabold text-white">Attendance Reports</h2>
                    <p class="text-sm text-white/40">
                        {{ $displayMonth }} overview
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 lg:ml-auto w-full lg:w-auto">

                    @if (auth()->user()->role === 'admin')
                                        <button onclick="openHolidayModal()" class="w-full sm:w-auto sm:ml-auto
                        flex items-center justify-center gap-2
                        px-6 py-3 rounded-2xl
                        bg-gradient-to-r from-[#ff2ba6] to-[#d41a8a]
                        hover:scale-105 transition
                        text-white font-bold shadow-xl
                        ">
                                            <i data-lucide="calendar-plus" class="w-5 h-5"></i>
                                            Mark Holiday
                                        </button>
                    @endif
                    <a href="{{ route('admin.attendance.export.all', ['month' => $monthInput]) }}" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl
           bg-green-600 hover:bg-green-700
           text-white font-semibold text-sm shadow">
                        Export All (Excel)
                    </a>
                </div>
            </div>
        </div>

        {{-- DATE RANGE EXPORT --}}
        <div class="glass p-6 md:p-8 rounded-2xl border border-white/20 shadow-2xl mb-8">

            <div class="flex items-center gap-3 mb-5">
                <div class="bg-white/10 p-3 rounded-xl">
                    <i data-lucide="file-spreadsheet" class="w-6 h-6 text-emerald-400"></i>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-white">
                        Export Attendance by Date Range
                    </h3>
                    <p class="text-sm text-white/50">
                        Select a start date and end date to export attendance records.
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.attendance.export.range') }}">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                    {{-- Start Date --}}
                    <div>
                        <label class="text-xs text-white/50 mb-1 block">
                            Start Date
                        </label>

                        <input type="date" name="start_date" required class="w-full px-4 py-3 rounded-2xl
                                  bg-black/40 border border-white/10
                                  text-white">
                    </div>

                    {{-- End Date --}}
                    <div>
                        <label class="text-xs text-white/50 mb-1 block">
                            End Date
                        </label>

                        <input type="date" name="end_date" required class="w-full px-4 py-3 rounded-2xl
                                  bg-black/40 border border-white/10
                                  text-white">
                    </div>

                    {{-- Optional Staff --}}
                    <div>
                        <label class="text-xs text-white/50 mb-1 block">
                            Staff (Optional)
                        </label>

                        <select name="staff"
                            class="bg-white/10 text-white border border-white/20 p-3 rounded-xl w-full focus:ring-2 focus:ring-[#ff2ba6]/50 transition outline-none">

                            <option value="">All Staff</option>

                            @foreach ($allStaff as $user)
                                <option value="{{ $user->id }}" class="text-black">
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Export Button --}}
                    <div>
                        <button type="submit" class="w-full px-6 py-3 rounded-2xl
                               bg-gradient-to-r from-emerald-500 to-green-600
                               hover:scale-105 transition
                               text-white font-bold shadow-xl">

                            Export Excel
                        </button>
                    </div>

                </div>

            </form>
        </div>

        {{-- FILTERS --}}
        <form method="GET" action="{{ route('admin.attendance.index') }}"
            class="glass p-6 md:p-8 rounded-2xl border border-white/20 shadow-2xl mb-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">

                {{-- Month --}}
                <div>
                    <label class="text-xs text-white/50 mb-1 block">Month</label>
                    <div class="relative">
                        <i data-lucide="calendar" class="absolute left-4 top-3 w-5 h-5 text-white/40"></i>
                        <input type="month" name="month" value="{{ $monthInput }}" class="w-full pl-12 px-4 py-3 rounded-2xl
                                   bg-black/40 border border-white/10 text-white">
                    </div>
                </div>

                {{-- Staff --}}
                <div>
                    <label class="text-xs text-white/50 mb-1 block">Staff</label>
                    <div class="relative">
                        <i data-lucide="users" class="absolute left-4 top-3 w-5 h-5 text-white/40"></i>
                        <select name="staff"
                            class="bg-white/10 text-white border border-white/20 p-3 pl-10 rounded-xl w-full focus:ring-2 focus:ring-[#ff2ba6]/50 transition outline-none">

                            <option value="" class="text-black">All Staff</option>
                            @foreach ($allStaff as $user)
                                <option value="{{ $user->id }}" {{ ($staffId ?? '') == $user->id ? 'selected' : '' }}
                                    class="text-black">
                                    {{ $user->name }} ({{ ucfirst($user->role) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button class="px-6 py-3 rounded-2xl font-bold text-white
                               bg-gradient-to-r from-[#ff2ba6] to-[#d41a8a]">
                        Apply
                    </button>

                    <a href="{{ route('admin.attendance.index') }}"
                        class="px-6 py-3 rounded-2xl bg-white/5 text-white border border-white/10">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- INSIGHTS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

            <div class="relative glass p-4 rounded-2xl border border-emerald-400/30 hover:scale-[1.03] transition-all duration-300 overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 to-emerald-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider">Star Performer</p>
                        <div class="bg-emerald-500/10 p-1.5 rounded-lg">
                            <i data-lucide="trophy" class="w-3.5 h-3.5 text-emerald-400"></i>
                        </div>
                    </div>
                    <h3 class="text-sm text-white font-bold mb-1.5">
                        {{ $bestAttendance?->salesman?->name ?? '--' }}
                    </h3>
                    <div class="flex items-center gap-1.5">
                        <span class="text-2xl font-extrabold text-emerald-400">{{ $bestAttendance->presents ?? 0 }}</span>
                        <span class="text-[10px] text-emerald-300/70 font-medium uppercase">days</span>
                    </div>
                </div>
            </div>

            <div class="relative glass p-4 rounded-2xl border border-rose-400/30 hover:scale-[1.03] transition-all duration-300 overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-rose-500/20 to-rose-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-[10px] text-rose-400 font-bold uppercase tracking-wider">Most Leaves</p>
                        <div class="bg-rose-500/10 p-1.5 rounded-lg">
                            <i data-lucide="x-circle" class="w-3.5 h-3.5 text-rose-400"></i>
                        </div>
                    </div>
                    <h3 class="text-sm text-white font-bold mb-1.5">
                        {{ $mostLeaves?->salesman?->name ?? '--' }}
                    </h3>
                    <div class="flex items-center gap-1.5">
                        <span class="text-2xl font-extrabold text-rose-400">{{ $mostLeaves->leaves ?? 0 }}</span>
                        <span class="text-[10px] text-rose-300/70 font-medium uppercase">leaves</span>
                    </div>
                </div>
            </div>

            <div class="relative glass p-4 rounded-2xl border border-sky-400/30 hover:scale-[1.03] transition-all duration-300 overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-sky-500/20 to-sky-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-[10px] text-sky-400 font-bold uppercase tracking-wider">Hardest Worker</p>
                        <div class="bg-sky-500/10 p-1.5 rounded-lg">
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-sky-400"></i>
                        </div>
                    </div>
                    <h3 class="text-sm text-white font-bold mb-1.5">
                        {{ $hardestWorker?->salesman?->name ?? '--' }}
                    </h3>
                    <div class="flex items-center gap-1.5">
                        <span class="text-2xl font-extrabold text-sky-400">{{ number_format(($hardestWorker->minutes ?? 0) / 60, 1) }}</span>
                        <span class="text-[10px] text-sky-300/70 font-medium uppercase">hours</span>
                    </div>
                </div>
            </div>

            <div class="relative glass p-4 rounded-2xl border border-purple-400/30 hover:scale-[1.03] transition-all duration-300 overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/20 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-[10px] text-purple-400 font-bold uppercase tracking-wider">Attendance Health</p>
                        <div class="bg-purple-500/10 p-1.5 rounded-lg">
                            <i data-lucide="heart-pulse" class="w-3.5 h-3.5 text-purple-400"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 mb-1.5">
                        <span class="text-2xl font-extrabold text-purple-400">{{ $attendanceRate }}%</span>
                    </div>
                    <div class="w-full h-1 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-purple-500/40 to-purple-400/20" style="width: {{ $attendanceRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="hidden md:block glass rounded-3xl border border-white/20 shadow-2xl overflow-hidden">

            <div class="overflow-x-auto">
                <table class="min-w-full text-white text-sm">
                    <thead class="bg-white/5 text-white/60 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center">Present</th>
                            <th class="text-center">Leaves</th>
                            <th class="text-center">Short</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/5">
                        @forelse($staff as $i => $user)
                            <tr class="hover:bg-white/10 transition">
                                <td class="px-6 py-4 text-white/60">{{ $i + 1 }}</td>

                                <td class="font-semibold flex items-center gap-2 mt-4">

                                    {{ $user->name }}
                                </td>

                                <td class="text-white/50">{{ $user->email }}</td>

                                <td>
                                    <span class="px-3 py-1 text-xs rounded-full bg-white/10">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 font-bold">
                                        {{ $user->monthAttendance }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="px-3 py-1 rounded-full bg-rose-500/20 text-rose-300 font-bold">
                                        {{ $user->monthLeaves }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="px-3 py-1 rounded-full bg-orange-500/20 text-orange-300 font-bold">
                                        {{ $user->shortLeaves }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('admin.attendance.staff', ['id' => $user->id, 'month' => $monthInput]) }}"
                                        class="inline-flex items-center gap-1 px-4 py-2 rounded-xl
                                              bg-gradient-to-r from-[#ff2ba6] to-[#d41a8a]
                                              hover:scale-105 transition text-white text-xs font-bold">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-white/40">
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
            <div class="p-4 bg-white/10 rounded-xl border border-white/10 shadow">

                {{-- Name --}}
                <div class="text-lg font-semibold text-white flex items-center">
                    <i data-lucide="user" class="w-5 h-5 mr-2 text-pink-300"></i>
                    {{ $u->name }}
                </div>

                {{-- Email --}}
                <div class="text-white/70 text-sm flex items-center">
                    <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                    {{ $u->email }}
                </div>

                {{-- Stats --}}
                <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">

                    <div class="bg-white/5 p-3 rounded-lg">
                        <div class="text-white/60 flex items-center">
                            <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                            Present
                        </div>
                        <div class="text-emerald-300 font-semibold text-lg">
                            {{ $u->monthAttendance }}
                        </div>
                    </div>

                    <div class="bg-white/5 p-3 rounded-lg">
                        <div class="text-white/60 flex items-center">
                            <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i>
                            Leaves
                        </div>
                        <div class="text-rose-300 font-semibold text-lg">
                            {{ $u->monthLeaves }}
                        </div>
                    </div>

                    <div class="bg-white/5 p-3 rounded-lg">
                        <div class="text-white/60 flex items-center">
                            <i data-lucide="clock" class="w-4 h-4 mr-2"></i>
                            Short
                        </div>
                        <div class="text-orange-300 font-semibold text-lg">
                            {{ $u->shortLeaves }}
                        </div>
                    </div>

                    <div class="bg-white/5 p-3 rounded-lg col-span-2 sm:col-span-3">
                        <div class="text-white/60 flex items-center">
                            <i data-lucide="badge-check" class="w-4 h-4 mr-2"></i>
                            Role
                        </div>
                        <div class="text-white font-semibold">
                            {{ ucfirst($u->role) }}
                        </div>
                    </div>

                </div>

                {{-- Action --}}
                <div class="mt-4">
                    <a href="{{ route('admin.attendance.staff', ['id' => $u->id, 'month' => $monthInput]) }}" class="block text-center py-2 rounded-lg
                                  bg-gradient-to-r from-[#ff2ba6] to-[#d41a8a]
                                  text-white text-sm font-semibold">
                        <i data-lucide="eye" class="inline w-4 h-4 mr-1"></i>
                        View Attendance
                    </a>
                </div>

            </div>

        @empty
            <div class="text-center text-white/40 py-10">
                No records found
            </div>
        @endforelse

    </div>

    {{-- HOLIDAY MODAL --}}
    <div id="holidayModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">

        <div class="glass w-full max-w-md p-6 rounded-2xl border border-white/20 shadow-2xl relative">
            <button onclick="closeHolidayModal()" class="absolute top-4 right-4 text-white/60">✕</button>

            <h3 class="text-2xl font-bold text-white mb-4">📅 Mark Company Holiday</h3>

            <form method="POST" action="{{ route('admin.holiday.store') }}">
                @csrf

                {{-- ERROR DISPLAY (CRITICAL) --}}
                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-red-500/20 text-red-300 text-sm">
                        @foreach ($errors->all() as $error)
                            <div>• {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                {{-- SUCCESS --}}
                @if (session('success'))
                    <div class="mb-4 p-3 rounded-xl bg-green-500/20 text-green-300 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <input type="text" name="title" required
                    class="w-full mb-3 px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white"
                    placeholder="Holiday Title">

                <label class="text-xs text-white/60">Start Date</label>
                <input type="date" name="start_date" required
                    class="w-full mb-3 px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white">

                <label class="text-xs text-white/60">End Date (optional)</label>
                <input type="date" name="end_date"
                    class="w-full mb-4 px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white">

                {{-- SUBMIT BUTTON --}}
                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-pink-500 to-fuchsia-600
                   text-white font-bold">
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
        lucide.createIcons();
    </script>

@endsection
