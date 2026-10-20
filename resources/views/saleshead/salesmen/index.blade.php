@extends('layouts.app')

@section('title','Salesmen')

@section('content')

<div class="page-head !mb-6">
    <h2 class="page-title">
        <span class="page-ico"><i data-lucide="users" class="w-6 h-6"></i></span>
        Salesmen
    </h2>
</div>

<div class="card !rounded-2xl p-6 shadow-card-lg">

    <div class="tbl-wrap !border-0 !shadow-none">
    <div class="hidden md:block">
        <table class="tbl w-full">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Customers</th>
                    <th>Visits</th>
                    <th>Joined</th>
                </tr>
            </thead>

            <tbody>
                @forelse($salesmen as $s)
                    <tr>
                        <td class="muted">{{ $s->id }}</td>
                        <td class="text-[var(--text)]">
                            <div class="flex items-center">
                                <span>{{ $s->name }}</span>
                                @if($s->is_blocked)
                                    <span class="badge badge-red ml-2">Blocked</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-[var(--text-soft)]">{{ $s->email }}</td>
                        <td class="text-[var(--text-soft)]">{{ $s->customers_count }}</td>
                        <td class="font-semibold text-[var(--accent)]">{{ $s->visits_count }}</td>
                        <td class="text-[var(--text-muted)] text-sm">{{ $s->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center muted soft-panel">
                            <i data-lucide="search-x" class="w-5 h-5 inline-block mr-2"></i> No Salesman Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>

    <div class="md:hidden space-y-4">
        @forelse($salesmen as $s)
            <div class="card !rounded-xl p-4 border border-[var(--border)]">
                <div class="text-lg font-semibold text-[var(--text)] flex items-center">
                    <i data-lucide="user" class="w-5 h-5 mr-2 text-[var(--accent)]"></i> {{ $s->name }}
                    @if($s->is_blocked)
                        <span class="badge badge-red ml-2">Blocked</span>
                    @endif
                </div>
                <div class="text-[var(--text-soft)] text-sm flex items-center">
                    <i data-lucide="mail" class="w-4 h-4 mr-2"></i> {{ $s->email }}
                </div>

                <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                    <div class="soft-panel p-3 rounded-lg">
                        <div class="text-[var(--text-muted)] flex items-center"><i data-lucide="building" class="w-4 h-4 mr-2"></i> Total Customers</div>
                        <div class="text-[var(--text)] font-semibold text-lg">{{ $s->customers_count }}</div>
                    </div>
                    <div class="soft-panel p-3 rounded-lg">
                        <div class="text-[var(--text-muted)] flex items-center"><i data-lucide="map-pin" class="w-4 h-4 mr-2"></i> Total Visits</div>
                        <div class="text-[var(--accent)] font-semibold text-lg">{{ $s->visits_count }}</div>
                    </div>
                </div>

                <div class="text-[var(--text-muted)] text-xs mt-3 flex items-center">
                    <i data-lucide="clock" class="w-3 h-3 mr-1"></i> Joined: {{ $s->created_at->format('Y-m-d') }}
                </div>
            </div>
        @empty
            <div class="p-6 text-center muted soft-panel rounded-xl">
                <i data-lucide="search-x" class="w-5 h-5 inline-block mr-2"></i> No Salesman Found
            </div>
        @endforelse
    </div>

    <div class="mt-5">
        {{ $salesmen->links() }}
    </div>

</div>

@endsection