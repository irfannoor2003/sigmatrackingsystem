@extends('layouts.app')

@section('title','Salesmen')

@section('content')

<div class="flex flex-wrap justify-between items-center mb-6 gap-3">
    <div class="page-head !mb-0">
        <h2 class="page-title">
            <span class="page-ico"><i data-lucide="users" class="w-6 h-6"></i></span>
            Salesmen
        </h2>
    </div>

    <a href="{{ route('admin.salesmen.create') }}" class="btn btn-primary">
        <i data-lucide="user-plus" class="w-4 h-4"></i> Add Salesman
    </a>
</div>

<div class="card !rounded-2xl p-6">

    {{-- ▌▌ DESKTOP LAYOUT (TABLE) --}}
    <div class="hidden md:block tbl-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Customers</th>
                    <th>Customers (In Month)</th>
                    <th>Visits (In Month)</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    use App\Models\Customer;
                    use App\Models\Visit;
                    use Carbon\Carbon;

                    $start = Carbon::now()->startOfMonth();
                    $end   = Carbon::now()->endOfMonth();
                @endphp

                @forelse($salesmen as $s)
                    @php
                        // Assuming relationships are defined on the Salesman model ($s)
                        $totalCustomers = $s->customers()->count();
                        $customersMonthly = $s->customers()
                            ->whereBetween('created_at', [$start, $end])
                            ->count();

                        $visitsMonthly = $s->visits()
                            ->where('status', 'completed')
                            ->whereBetween('created_at', [$start, $end])
                            ->count();
                    @endphp

                    <tr>
                        <td class="muted">{{ $s->id }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-[var(--text)]">{{ $s->name }}</span>
                                @if($s->is_blocked)
                                    <span class="badge badge-red text-xs">Blocked</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-[var(--text-soft)]">{{ $s->email }}</td>
                        <td class="text-[var(--text-soft)]">{{ $totalCustomers }}</td>
                        <td class="text-indigo-600 dark:text-indigo-400 font-semibold">{{ $customersMonthly }}</td>
                        <td class="text-violet-600 dark:text-violet-400 font-semibold">{{ $visitsMonthly }}</td>

                        <td>
                            <div class="flex items-center justify-center gap-2">

                                {{-- Edit --}}
                                <a href="{{ route('admin.salesmen.edit', $s->id) }}"
                                   class="p-2 rounded-lg bg-blue-500/15 text-blue-600 dark:text-blue-400
                                          hover:bg-blue-500/30 transition"
                                   title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>

                                {{-- Delete --}}
                                <form method="POST"
                                      action="{{ route('admin.salesmen.destroy', $s->id) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this salesman?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="p-2 rounded-lg bg-red-500/15 text-red-600 dark:text-red-400
                                                   hover:bg-red-500/30 transition"
                                            title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>

                                {{-- Block / Unblock --}}
                                <form method="POST" action="{{ route('admin.salesmen.block', $s->id) }}"
                                      onsubmit="return confirm('{{ $s->is_blocked ? 'Are you sure you want to unblock this user?' : 'Are you sure you want to block this user?' }}')">
                                    @csrf
                                    <button type="submit"
                                            class="p-2 rounded-lg transition {{ $s->is_blocked ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/30' : 'bg-amber-500/15 text-amber-600 dark:text-amber-400 hover:bg-amber-500/30' }}"
                                            title="{{ $s->is_blocked ? 'Unblock' : 'Block' }}">
                                        <i data-lucide="{{ $s->is_blocked ? 'unlock' : 'lock' }}" class="w-4 h-4"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center muted">
                            <i data-lucide="search-x" class="w-5 h-5 inline-block mr-2 opacity-50"></i> No Salesman Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>



    {{-- ▌▌ MOBILE LAYOUT (CARDS) --}}
    <div class="md:hidden space-y-4">
        @forelse($salesmen as $s)
            @php
                // Re-calculating for the mobile view
                $totalCustomers = $s->customers()->count();
                $customersMonthly = $s->customers()
                    ->whereBetween('created_at', [$start, $end])
                    ->count();

                $visitsMonthly = $s->visits()
                    ->where('status', 'completed')
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
            @endphp

            <div class="card !rounded-2xl p-4">
                <div class="text-lg font-semibold text-[var(--text)] flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-[var(--accent)]"></i> {{ $s->name }}
                    @if($s->is_blocked)
                        <span class="badge badge-red text-xs">Blocked</span>
                    @endif
                </div>
                <div class="text-[var(--text-soft)] text-sm flex items-center">
                    <i data-lucide="mail" class="w-4 h-4 mr-2"></i> {{ $s->email }}
                </div>

                <div class="mt-3 grid grid-cols-2 gap-3 text-sm">

                    <div class="soft-panel p-3 rounded-lg">
                        <div class="muted flex items-center"><i data-lucide="building" class="w-4 h-4 mr-2"></i> Total Customers</div>
                        <div class="text-[var(--text)] font-semibold text-lg">{{ $totalCustomers }}</div>
                    </div>

                    <div class="soft-panel p-3 rounded-lg">
                        <div class="muted flex items-center"><i data-lucide="calendar-plus" class="w-4 h-4 mr-2"></i> This Month</div>
                        <div class="text-indigo-600 dark:text-indigo-400 font-semibold text-lg">{{ $customersMonthly }}</div>
                    </div>

                    <div class="soft-panel p-3 rounded-lg col-span-2">
                        <div class="muted flex items-center"><i data-lucide="calendar-check" class="w-4 h-4 mr-2"></i> Visits (This Month)</div>
                        <div class="text-violet-600 dark:text-violet-400 font-semibold text-lg">{{ $visitsMonthly }}</div>
                    </div>

                </div>

                <div class="text-xs muted mt-3 flex items-center">
                    <i data-lucide="clock" class="w-3 h-3 mr-1"></i> Joined: {{ $s->created_at->format('Y-m-d') }}
                </div>
                <div class="mt-4 flex gap-2">

                    {{-- Edit --}}
                    <a href="{{ route('admin.salesmen.edit', $s->id) }}"
                       class="flex-1 py-2 rounded-lg bg-blue-500/15 text-blue-600 dark:text-blue-400
                              hover:bg-blue-500/30 transition flex items-center justify-center"
                       title="Edit">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                    </a>

                    {{-- Delete --}}
                    <form method="POST"
                          action="{{ route('admin.salesmen.destroy', $s->id) }}"
                          class="flex-1"
                          onsubmit="return confirm('Delete this salesman?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="w-full py-2 rounded-lg bg-red-500/15 text-red-600 dark:text-red-400
                                       hover:bg-red-500/30 transition flex items-center justify-center"
                                title="Delete">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>

                    {{-- Block / Unblock --}}
                    <form method="POST" action="{{ route('admin.salesmen.block', $s->id) }}" class="flex-1"
                          onsubmit="return confirm('{{ $s->is_blocked ? 'Are you sure you want to unblock this user?' : 'Are you sure you want to block this user?' }}')">
                        @csrf
                        <button type="submit"
                                class="w-full py-2 rounded-lg transition flex items-center justify-center {{ $s->is_blocked ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/30' : 'bg-amber-500/15 text-amber-600 dark:text-amber-400 hover:bg-amber-500/30' }}"
                                title="{{ $s->is_blocked ? 'Unblock' : 'Block' }}">
                            <i data-lucide="{{ $s->is_blocked ? 'unlock' : 'lock' }}" class="w-4 h-4"></i>
                        </button>
                    </form>

                </div>
            </div>
        @empty
            <div class="card !rounded-2xl p-6 text-center muted">
                <i data-lucide="search-x" class="w-5 h-5 inline-block mr-2 opacity-50"></i> No Salesman Found
            </div>
        @endforelse
    </div>


    <div class="mt-5">
        {{ $salesmen->links() }}
    </div>

</div>

@endsection