@extends('layouts.app')

@section('title', 'Staff Management')

@section('content')
    <div class="p-4 px-0 sm:p-6">

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-wide">
                <i data-lucide="users-2" class="w-7 h-7 inline mr-2 text-[var(--hf-magenta-light)]"></i>
                Staff Management
            </h1>

            <a href="{{ route('hr.staff.create') }}"
                class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] text-white font-semibold shadow hover:opacity-90 w-full sm:w-auto transition transform hover:-translate-y-0.5">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                Add New Staff
            </a>
        </div>

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('hr.staff.index') }}"
            class="glass mb-6 p-4 sm:p-6 rounded-2xl border border-white/20 grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">

            <div class="relative flex items-center sm:col-span-4">
                <i data-lucide="shield-check" class="absolute left-3 w-5 h-5 text-white/50 pointer-events-none"></i>
                <select name="role" class="bg-white/10 text-white border border-white/20 p-3 pl-10 rounded-xl w-full focus:ring-2 focus:ring-[#ff2ba6]/50 transition outline-none">

                    <option value="" class="text-black">All Roles</option>
<option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }} class="text-black">Admin</option>
<option value="salesman" {{ request('role') == 'salesman' ? 'selected' : '' }} class="text-black"> Salesman</option>
<option value="account" {{ request('role') == 'account' ? 'selected' : '' }} class="text-black">Accounts</option>
<option value="it" {{ request('role') == 'it' ? 'selected' : '' }} class="text-black">IT</option>
<option value="store" {{ request('role') == 'store' ? 'selected' : '' }} class="text-black">Store</option>
<option value="office_boy" {{ request('role') == 'office_boy' ? 'selected' : '' }} class="text-black">Office_Boy</option>
<option value="hr" {{ request('role') == 'hr' ? 'selected' : '' }} class="text-black">Hr</option>
<option value="saleshead" {{ request('saleshead') == 'saleshead' ? 'selected' : '' }} class="text-black">Saleshead</option>

                </select>
            </div>

            <div class="relative flex items-center sm:col-span-5">
                <i data-lucide="search" class="absolute left-3 w-5 h-5 text-white/50 pointer-events-none"></i>
                <input type="text" name="search" placeholder="Search name or email..." value="{{ request('search') }}"
                    class="bg-white/10 text-white border border-white/20 p-3 pl-10 rounded-xl w-full focus:ring-2 focus:ring-[#ff2ba6]/50 transition outline-none">
            </div>

            <div class="flex gap-3 sm:col-span-3">
                <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] text-white font-semibold shadow hover:opacity-90 transition">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    Filter
                </button>

                <a href="{{ route('hr.staff.index') }}"
                    class="flex items-center justify-center px-4 py-3 rounded-xl bg-white/20 border border-white/30 text-white hover:bg-white/30 transition"
                    title="Reset Filters">
                    <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
                </a>
            </div>
        </form>

        {{-- Desktop Table View --}}
        <div class="glass rounded-2xl border border-white/20 overflow-hidden shadow-xl hidden md:block">
            <table class="w-full min-w-[600px]">
                <thead class="bg-white/10 backdrop-blur-xl">
                    <tr class="text-left text-white/70 text-xs sm:text-sm  tracking-wider">
                        <th class="p-4 w-20"><div class="flex items-center gap-2"><i data-lucide="hash" class="w-4 h-4 text-white/50"></i>Id</div></th>
                        <th class="p-4"><div class="flex items-center gap-2"><i data-lucide="user" class="w-4 h-4 text-white/50"></i>Staff Member</div></th>
                        <th class="p-4"><div class="flex items-center gap-2"><i data-lucide="mail" class="w-4 h-4 text-white/50"></i>Email</div></th>
                        <th class="p-4 text-center"><div class="flex items-center justify-center gap-2"><i data-lucide="shield" class="w-4 h-4 text-white/50"></i>Role</div></th>
                        <th class="p-4 text-center"><div class="flex items-center justify-center gap-2"><i data-lucide="settings" class="w-4 h-4 text-white/50"></i>Actions</div></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($staff as $user)
                        <tr class="border-t border-white/10 hover:bg-white/10 transition">
                            <td class="p-4 text-white/40 font-mono text-xs">#{{ $user->id }}</td>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold uppercase shadow-lg">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <span class="font-semibold text-white/90">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-white/70 text-sm italic">{{ $user->email }}</td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase border
                                    {{ $user->role === 'admin' ? 'bg-rose-500/20 text-rose-300 border-rose-500/40' : 'bg-blue-500/20 text-blue-300 border-blue-500/40' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-2">
                                    @if($user->role !== 'admin')
                                        <a href="{{ route('hr.attendance.staff', $user->id) }}"
                                           class="p-2 rounded-lg bg-blue-500/20 border border-blue-400/30 text-blue-200 hover:bg-blue-500/40 transition"
                                           title="Attendance">
                                            <i data-lucide="calendar-check" class="w-4 h-4"></i>
                                        </a>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center text-white/40 italic">No staff members found matching your filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card View --}}
        <div class="md:hidden space-y-4 mt-4">
            @forelse ($staff as $user)
                <div class="glass border border-white/20 rounded-2xl p-5 shadow-lg relative">
                    <div class="absolute top-4 right-4">
                         <span class="px-2 py-1 rounded-md text-[9px] font-bold uppercase border
                            {{ $user->role === 'admin' ? 'bg-rose-500/20 text-rose-300 border-rose-500/40' : 'bg-blue-500/20 text-blue-300 border-blue-500/40' }}">
                            {{ $user->role }}
                        </span>
                    </div>

                    <div class="flex items-center gap-4 mb-4">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="text-white font-bold">{{ $user->name }}</h3>
                            <p class="text-white/50 text-xs">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 pt-4 border-t border-white/10">
                        @if($user->role !== 'admin')
                            <a href="{{ route('hr.attendance.staff', $user->id) }}"
                                class="flex items-center justify-center gap-2 py-2.5 rounded-xl bg-blue-500/20 border border-blue-400/30 text-blue-200 text-xs font-semibold hover:bg-blue-500/30 transition">
                                <i data-lucide="calendar-check" class="w-4 h-4"></i>
                                Attendance
                            </a>
                        @endif

                    </div>
                </div>
            @empty
                <p class="text-center text-white/50 py-10">No staff members found matching your filters.</p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $staff->appends(request()->query())->links() }}
        </div>

    </div>
@endsection
