@extends('layouts.app')
@section('title', 'Out of City Visits')

@section('content')
    <div>

        <div class="page-head">
            <h1 class="page-title">
                <span class="page-ico"><i data-lucide="plane" class="w-6 h-6"></i></span>
                Out of City Visits
            </h1>
        </div>



        <x-filter-bar :action="route('salesman.out-of-city-visits.index')" class="print:hidden">
            <x-filter-field icon="plane">
                <select name="status" onchange="this.form.submit()" class="select">
                    <option value="" @selected(!request('status'))>All Visits</option>
                    <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                    <option value="submitted" @selected(request('status') == 'submitted')>Submitted</option>
                </select>
            </x-filter-field>

            <x-filter-actions :reset="route('salesman.out-of-city-visits.index')" />
        </x-filter-bar>

        <div class="tbl-wrap">
            <table class="tbl hidden md:table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Attendance Note</th>
                        <th>Status</th>
                        <th>Visits Added</th>
                        <th>Submitted At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visits as $v)
                        <tr>
                            <td class="font-semibold text-[var(--text)]">{{ $v->id }}</td>
                            <td>{{ optional($v->attendance)->date ? $v->attendance->date->format('d M Y') : '-' }}</td>
                            <td class="max-w-xs">
                                <div class="truncate max-w-[200px]" title="{{ $v->attendance->note ?? '' }}">
                                    {{ \Illuminate\Support\Str::limit($v->attendance->note ?? '-', 30) }}
                                </div>
                            </td>
                            <td>
                                @if ($v->status === 'pending')
                                    <span class="badge badge-amber">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        {{ ucfirst($v->status) }}
                                    </span>
                                @else
                                    <span class="badge badge-emerald">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                        {{ ucfirst($v->status) }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $v->items->count() }}</td>
                            <td>{{ optional($v->submitted_at)->format('d M Y, h:i A') ?? '-' }}</td>
                            <td>
                                @if ($v->isPending())
                                    <a href="{{ route('salesman.out-of-city-visits.create', $v->id) }}" class="btn btn-soft btn-sm">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                        Add Details
                                    </a>
                                @else
                                    <a href="{{ route('salesman.out-of-city-visits.show', $v->id) }}" class="btn btn-primary btn-sm">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        View
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center muted">
                                <i data-lucide="inbox" class="w-8 h-8 inline-block mb-2"></i>
                                No out of city visits found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="md:hidden p-4 space-y-4">
                @forelse ($visits as $v)
                    <div class="soft-panel !rounded-2xl p-4">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <h2 class="text-base font-bold text-[var(--text)] flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-[var(--accent)]"></i>
                                {{ optional($v->attendance)->date ? $v->attendance->date->format('d M Y') : '-' }}
                            </h2>
                            @if ($v->status === 'pending')
                                <span class="badge badge-amber">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    {{ ucfirst($v->status) }}
                                </span>
                            @else
                                <span class="badge badge-emerald">
                                    <i data-lucide="check" class="w-3 h-3"></i>
                                    {{ ucfirst($v->status) }}
                                </span>
                            @endif
                        </div>

                        <p class="text-sm text-[var(--text-soft)] mb-1 flex items-center gap-2">
                            <i data-lucide="clipboard-list" class="w-3.5 h-3.5 text-[var(--text-muted)]"></i>
                            {{ $v->items->count() }} customer(s) visited
                        </p>

                        @if ($v->submitted_at)
                            <p class="text-xs text-[var(--text-muted)] mb-3 flex items-center gap-2">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                Submitted: {{ $v->submitted_at->format('d M Y, h:i A') }}
                            </p>
                        @endif

                        <div class="mt-3">
                            @if ($v->isPending())
                                <a href="{{ route('salesman.out-of-city-visits.create', $v->id) }}" class="btn btn-soft w-full">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                    Add Visit Details
                                </a>
                            @else
                                <a href="{{ route('salesman.out-of-city-visits.show', $v->id) }}" class="btn btn-primary w-full">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                    View Details
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center muted p-6">No out of city visits found.</p>
                @endforelse
            </div>
        </div>

        <div class="mt-6">
            {{ $visits->links() }}
        </div>
    </div>
@endsection
