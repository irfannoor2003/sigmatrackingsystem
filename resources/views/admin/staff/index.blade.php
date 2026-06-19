@extends('layouts.app')

@section('title', 'Staff Management')

@section('content')
    <div class="p-0">

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-wide">
                <i data-lucide="users-2" class="w-7 h-7 inline mr-2 text-[var(--hf-magenta-light)]"></i>
                Staff Management
            </h1>

            <a href="{{ route('admin.staff.create') }}"
                class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] text-white font-semibold shadow hover:opacity-90 w-full sm:w-auto transition transform hover:-translate-y-0.5">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                Add New Staff
            </a>
        </div>

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('admin.staff.index') }}"
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

                <a href="{{ route('admin.staff.index') }}"
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
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-white/90">{{ $user->name }}</span>
                                            @if($user->is_blocked)
                                                <span class="inline-block px-2 py-0.5 text-xs rounded-full bg-red-600 text-white font-semibold">Blocked</span>
                                            @endif
                                        </div>
                                        <div class="text-white/60 text-xs">{{ $user->email }}</div>
                                    </div>
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
        <a href="{{ route('admin.attendance.staff', $user->id) }}"
           class="p-2 rounded-lg bg-blue-500/20 border border-blue-400/30 text-blue-200 hover:bg-blue-500/40 transition"
           title="Attendance">
            <i data-lucide="calendar-check" class="w-4 h-4"></i>
        </a>
    @endif

    @if($user->role !== 'admin')
          <form method="POST" action="{{ $user->is_blocked ? route('admin.staff.unblock', $user->id) : route('admin.staff.block', $user->id) }}"
              onsubmit="return confirm('{{ $user->is_blocked ? 'Are you sure you want to unblock this staff member?' : 'Are you sure you want to block this staff member?' }}')">
            @csrf
            <button type="submit"
                    class="p-2 rounded-lg transition {{ $user->is_blocked ? 'bg-green-500/20 border border-green-400/30 text-green-200 hover:bg-green-500/30' : 'bg-yellow-500/20 border border-yellow-400/30 text-yellow-200 hover:bg-yellow-500/40' }}"
                    title="Block/Unblock">
                @if($user->is_blocked)
                    <!-- Unlock SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <rect x="5" y="11" width="14" height="10" rx="2" stroke-width="2"/>
    <path d="M16 11V7a4 4 0 00-8 0" stroke-width="2"/>
</svg>
                @else
                    <!-- Lock SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <rect x="5" y="11" width="14" height="10" rx="2" stroke-width="2"/>
    <path d="M8 11V7a4 4 0 118 0v4" stroke-width="2"/>
</svg>
                @endif
            </button>
        </form>
    @endif

    <a href="{{ route('admin.staff.edit', $user->id) }}"
       class="p-2 rounded-lg bg-amber-500/20 border border-amber-400/30 text-amber-200 hover:bg-amber-500/40 transition"
       title="Edit">
        <i data-lucide="edit-3" class="w-4 h-4"></i>
    </a>

    @if($user->role !== 'admin' && auth()->id() !== $user->id)
        <form action="{{ route('admin.staff.destroy', $user->id) }}"
              method="POST"
              onsubmit="return confirm('Are you sure you want to delete this staff member?')">
            @csrf
            @method('DELETE')

            <button type="submit"
                class="p-2 rounded-lg bg-red-500/20 border border-red-400/30 text-red-200 hover:bg-red-500/40 transition"
                title="Delete">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        </form>
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

                    <div class="flex flex-col gap-2 pt-4 border-t border-white/10">
                        @if($user->role !== 'admin')
                            <a href="{{ route('admin.attendance.staff', $user->id) }}"
                                class="flex items-center justify-center gap-2 py-2.5 rounded-xl bg-blue-500/20 border border-blue-400/30 text-blue-200 text-xs font-semibold hover:bg-blue-500/30 transition w-full">
                                <i data-lucide="calendar-check" class="w-4 h-4"></i>
                                Attendance
                            </a>
                        @endif
                        <a href="{{ route('admin.staff.edit', $user->id) }}"
                            class="flex items-center justify-center gap-2 py-2.5 rounded-xl bg-amber-500/20 border border-amber-400/30 text-amber-200 text-xs font-semibold hover:bg-amber-500/30 transition w-full">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                            Edit Staff
                        </a>
                        @if($user->role !== 'admin')
                            <form method="POST" action="{{ $user->is_blocked ? route('admin.staff.unblock', $user->id) : route('admin.staff.block', $user->id) }}"
                                onsubmit="return confirm('{{ $user->is_blocked ? 'Are you sure you want to unblock this staff member?' : 'Are you sure you want to block this staff member?' }}')">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl transition {{ $user->is_blocked ? 'bg-green-500/20 border border-green-400/30 text-green-200 hover:bg-green-500/30' : 'bg-yellow-500/20 border border-yellow-400/30 text-yellow-200 hover:bg-yellow-500/30' }} text-xs font-semibold">
                                    @if($user->is_blocked)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <rect x="5" y="11" width="14" height="10" rx="2" stroke-width="2"/>
                                            <path d="M16 11V7a4 4 0 00-8 0" stroke-width="2"/>
                                        </svg>
                                        Unblock
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <rect x="5" y="11" width="14" height="10" rx="2" stroke-width="2"/>
                                            <path d="M8 11V7a4 4 0 118 0v4" stroke-width="2"/>
                                        </svg>
                                        Block
                                    @endif
                                </button>
                            </form>
                        @endif
                        @if($user->role !== 'admin' && auth()->id() !== $user->id)
                            <form action="{{ route('admin.staff.destroy', $user->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this staff member?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-red-500/20 border border-red-400/30 text-red-200 text-xs font-semibold hover:bg-red-500/40 transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    Delete
                                </button>
                            </form>
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
