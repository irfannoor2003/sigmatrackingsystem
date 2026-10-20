@extends('layouts.app')

@section('title', 'Staff Management')

@section('content')
    <div class="p-0">

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <div class="page-head !mb-0">
                <h1 class="page-title">
                    <span class="page-ico"><i data-lucide="users-2" class="w-6 h-6"></i></span>
                    Staff Management
                </h1>
            </div>

            <a href="{{ route('admin.staff.create') }}" class="btn btn-primary w-full sm:w-auto">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                Add New Staff
            </a>
        </div>

        {{-- Status Filter Tabs --}}
        <div class="flex gap-2 mb-4">
            <a href="{{ route('admin.staff.index', array_merge(request()->query(), ['status' => 'active'])) }}"
                class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ ($status ?? 'active') === 'active' ? 'btn-primary' : 'btn btn-ghost' }}">
                Active
            </a>
            <a href="{{ route('admin.staff.index', array_merge(request()->query(), ['status' => 'left'])) }}"
                class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ ($status ?? 'active') === 'left' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow' : 'btn btn-ghost' }}">
                Left
            </a>
            <a href="{{ route('admin.staff.index', array_merge(request()->query(), ['status' => 'all'])) }}"
                class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ ($status ?? 'active') === 'all' ? 'bg-gradient-to-r from-gray-500 to-gray-600 text-white shadow' : 'btn btn-ghost' }}">
                All
            </a>
        </div>

        {{-- Filter Bar --}}
        <x-filter-bar :action="route('admin.staff.index')">
            <input type="hidden" name="status" value="{{ $status ?? 'active' }}">

            <x-filter-field icon="shield-check">
                <select name="role" class="select">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="salesman" {{ request('role') == 'salesman' ? 'selected' : '' }}>Salesman</option>
                    <option value="account" {{ request('role') == 'account' ? 'selected' : '' }}>Accounts</option>
                    <option value="it" {{ request('role') == 'it' ? 'selected' : '' }}>IT</option>
                    <option value="store" {{ request('role') == 'store' ? 'selected' : '' }}>Store</option>
                    <option value="office_boy" {{ request('role') == 'office_boy' ? 'selected' : '' }}>Office_Boy</option>
                    <option value="hr" {{ request('role') == 'hr' ? 'selected' : '' }}>Hr</option>
                    <option value="saleshead" {{ request('saleshead') == 'saleshead' ? 'selected' : '' }}>Saleshead</option>
                </select>
            </x-filter-field>

            <x-filter-field icon="search">
                <input type="text" name="search" placeholder="Search name or email..." value="{{ request('search') }}" class="input">
            </x-filter-field>

            <x-filter-actions :reset="route('admin.staff.index', ['status' => $status ?? 'active'])" />
        </x-filter-bar>

        {{-- Desktop Table View --}}
        <div class="card !rounded-2xl overflow-hidden shadow-card-lg hidden md:block">
            <div class="tbl-wrap !border-0 !shadow-none max-h-none">
            <table class="tbl w-full min-w-[600px]">
                <thead>
                    <tr>
                        <th class="w-20">Id</th>
                        <th>Staff Member</th>
                        <th>Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($staff as $user)
                        <tr class="{{ $user->left_at ? 'opacity-70' : '' }}">
                            <td class="muted font-mono text-xs">#{{ $user->id }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar avatar-md gradient-surface !text-white font-bold uppercase">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-[var(--text)]">{{ $user->name }}</span>
                                            @if($user->is_blocked)
                                                <span class="badge badge-red text-[10px]">Blocked</span>
                                            @endif
                                            @if($user->left_at)
                                                <span class="badge badge-orange text-[10px]">Left</span>
                                            @endif
                                        </div>
                                        @if($user->left_at)
                                            <div class="muted text-xs">Left on {{ $user->left_at->format('d M Y') }}</div>
                                        @endif
                                        <div class="text-[var(--text-soft)] text-xs">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-[var(--text-soft)] text-sm italic">{{ $user->email }}</td>
                            <td class="text-center">
                                <span class="badge {{ $user->role === 'admin' ? 'badge-rose' : 'badge-sky' }} uppercase !text-[10px]">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-2">

    @if($user->role !== 'admin')
        <a href="{{ route('admin.attendance.staff', $user->id) }}"
           class="p-2 rounded-lg bg-blue-500/15 text-blue-600 dark:text-blue-400 hover:bg-blue-500/30 transition"
           title="Attendance">
            <i data-lucide="calendar-check" class="w-4 h-4"></i>
        </a>
    @endif

    @if($user->role !== 'admin')
          <form method="POST" action="{{ $user->is_blocked ? route('admin.staff.unblock', $user->id) : route('admin.staff.block', $user->id) }}"
              onsubmit="return confirm('{{ $user->is_blocked ? 'Are you sure you want to unblock this staff member?' : 'Are you sure you want to block this staff member?' }}')">
            @csrf
            <button type="submit"
                    class="p-2 rounded-lg transition {{ $user->is_blocked ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/30' : 'bg-amber-500/15 text-amber-600 dark:text-amber-400 hover:bg-amber-500/40' }}"
                    title="Block/Unblock">
                <i data-lucide="{{ $user->is_blocked ? 'unlock' : 'lock' }}" class="w-4 h-4"></i>
            </button>
        </form>
    @endif

    @if($user->role !== 'admin' && auth()->id() !== $user->id)
        @if($user->left_at)
            <form method="POST" action="{{ route('admin.staff.rejoin', $user->id) }}"
                onsubmit="return confirm('Are you sure you want to re-add this staff member as active?')">
                @csrf
                <button type="submit"
                        class="p-2 rounded-lg bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/40 transition"
                        title="Rejoin">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                </button>
            </form>
        @else
            <form method="POST" action="{{ route('admin.staff.left', $user->id) }}"
                onsubmit="confirmLeft(event, this)">
                @csrf
                <input type="hidden" name="left_reason" value="">
                <input type="hidden" name="left_at" value="">
                <button type="submit"
                        class="p-2 rounded-lg bg-orange-500/15 text-orange-600 dark:text-orange-400 hover:bg-orange-500/40 transition"
                        title="Mark as Left">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <circle cx="12" cy="12" r="10" stroke-width="2"/>
    <path d="M12 6v6l4 2" stroke-width="2"/>
</svg>
                </button>
            </form>
        @endif
    @endif

    <a href="{{ route('admin.staff.edit', $user->id) }}"
       class="p-2 rounded-lg bg-amber-500/15 text-amber-600 dark:text-amber-400 hover:bg-amber-500/40 transition"
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
                class="p-2 rounded-lg bg-red-500/15 text-red-600 dark:text-red-400 hover:bg-red-500/40 transition"
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
                            <td colspan="5" class="p-10 text-center muted italic">No staff members found matching your filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        {{-- Mobile Card View --}}
        <div class="md:hidden space-y-4 mt-4">
            @forelse ($staff as $user)
                <div class="card !rounded-2xl p-5 relative {{ $user->left_at ? 'opacity-80' : '' }}">
                    <div class="absolute top-4 right-4">
                         <span class="badge {{ $user->role === 'admin' ? 'badge-rose' : 'badge-sky' }} uppercase !text-[9px]">
                            {{ $user->role }}
                        </span>
                    </div>

                    <div class="flex items-center gap-4 mb-4">
                        <div class="avatar avatar-lg gradient-surface !text-white font-bold">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="text-[var(--text)] font-bold">
                                {{ $user->name }}
                                @if($user->is_blocked)
                                    <span class="badge badge-red text-[9px] align-middle">Blocked</span>
                                @endif
                                @if($user->left_at)
                                    <span class="badge badge-orange text-[9px] align-middle">Left</span>
                                @endif
                            </h3>
                            <p class="muted text-xs">{{ $user->email }}</p>
                            @if($user->left_at)
                                <p class="text-orange-500/80 text-[10px]">Left on {{ $user->left_at->format('d M Y') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 pt-4 border-t border-[var(--border)]">
                        @if($user->role !== 'admin')
                            <a href="{{ route('admin.attendance.staff', $user->id) }}"
                                class="btn btn-soft btn-sm w-full">
                                <i data-lucide="calendar-check" class="w-4 h-4"></i>
                                Attendance
                            </a>
                        @endif
                        <a href="{{ route('admin.staff.edit', $user->id) }}"
                            class="btn btn-soft btn-sm w-full">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                            Edit Staff
                        </a>
                        @if($user->role !== 'admin')
                            <form method="POST" action="{{ $user->is_blocked ? route('admin.staff.unblock', $user->id) : route('admin.staff.block', $user->id) }}"
                                onsubmit="return confirm('{{ $user->is_blocked ? 'Are you sure you want to unblock this staff member?' : 'Are you sure you want to block this staff member?' }}')">
                                @csrf
                                <button type="submit" class="btn btn-sm w-full {{ $user->is_blocked ? 'btn-success' : 'bg-amber-500/15 text-amber-600 dark:text-amber-400 hover:bg-amber-500/30 border border-amber-500/25 rounded-xl font-bold' }}">
                                    <i data-lucide="{{ $user->is_blocked ? 'unlock' : 'lock' }}" class="w-4 h-4"></i>
                                    {{ $user->is_blocked ? 'Unblock' : 'Block' }}
                                </button>
                            </form>
                        @endif
                        @if($user->role !== 'admin' && auth()->id() !== $user->id)
                            @if($user->left_at)
                                <form method="POST" action="{{ route('admin.staff.rejoin', $user->id) }}"
                                    onsubmit="return confirm('Are you sure you want to re-add this staff member as active?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm w-full">
                                        <i data-lucide="user-check" class="w-4 h-4"></i>
                                        Rejoin
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.staff.left', $user->id) }}"
                                    onsubmit="confirmLeft(event, this)">
                                    @csrf
                                    <input type="hidden" name="left_reason" value="">
                                    <input type="hidden" name="left_at" value="">
                                    <button type="submit" class="btn btn-sm w-full bg-orange-500/15 text-orange-600 dark:text-orange-400 hover:bg-orange-500/30 border border-orange-500/25 rounded-xl font-bold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                            <path d="M12 6v6l4 2" stroke-width="2"/>
                                        </svg>
                                        Mark Left
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.staff.destroy', $user->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this staff member?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-full">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center muted py-10">No staff members found matching your filters.</p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $staff->appends(request()->query())->links() }}
        </div>

    </div>

    <script>
        function confirmLeft(event, form) {
            event.preventDefault();
            const reason = prompt('Optional reason for leaving (can be empty):', '');
            if (reason === null) return false;
            const today = new Date().toISOString().slice(0, 10);
            const leftAt = prompt('Leaving date (YYYY-MM-DD), empty = today:', today);
            if (leftAt === null) return false;
            form.querySelector('[name=left_reason]').value = reason || '';
            form.querySelector('[name=left_at]').value = leftAt || '';
            if (!confirm('Are you sure you want to mark this staff member as left?')) return false;
            form.submit();
            return false;
        }
    </script>
@endsection