@extends('layouts.app')

@section('title','Out of City Visits')

@section('content')

<style>
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
        cursor: pointer;
    }
    .report-main {
        max-width: 68rem;
        margin-left: auto;
        margin-right: auto;
    }
    .report-table th,
    .report-table td {
        white-space: nowrap;
    }
</style>

<div class="report-main p-0 sm:p-0">

    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-wide mb-6">
        <i data-lucide="plane" class="w-7 h-7 inline mr-2 text-[var(--hf-magenta-light)]"></i>
        Out of City Visits
    </h1>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('admin.out-of-city-visits.index') }}"
        class="bg-white/10 backdrop-blur-xl mb-6 p-4 sm:p-5 rounded-2xl border border-white/20">

        <div class="flex flex-col lg:flex-row lg:items-center gap-4">

            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-3">
                <div class="relative flex items-center">
                    <i data-lucide="filter" class="absolute left-3 w-5 h-5 text-white/50 pointer-events-none"></i>
                    <select name="status" onchange="this.form.submit()"
                        class="bg-white/10 text-white border border-white/20 p-2.5 pl-10 rounded-xl w-full appearance-none text-sm">
                        <option value="" class="text-black">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }} class="text-black">Pending</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }} class="text-black">Submitted</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 absolute right-3 text-white/60 pointer-events-none"></i>
                </div>

                <div class="relative flex items-center">
                    <i data-lucide="users" class="absolute left-3 w-5 h-5 text-white/50 pointer-events-none"></i>
                    <select name="salesman_id" onchange="this.form.submit()"
                        class="bg-white/10 text-white border border-white/20 p-2.5 pl-10 rounded-xl w-full appearance-none text-sm">
                        <option value="" class="text-black">All Salesmen</option>
                        @foreach ($salesmen as $s)
                            <option value="{{ $s->id }}" {{ request('salesman_id') == $s->id ? 'selected' : '' }} class="text-black">{{ $s->name }}</option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 absolute right-3 text-white/60 pointer-events-none"></i>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                @if (request('status') || request('salesman_id'))
                    <a href="{{ route('admin.out-of-city-visits.index') }}"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/20 border border-white/30 text-white font-semibold shadow hover:bg-white/30 text-sm">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Clear
                    </a>
                @endif
            </div>

        </div>
    </form>

    {{-- Desktop Table --}}
    <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 shadow-xl hidden md:block overflow-x-auto">
        <table class="report-table w-full">
            <thead class="bg-white/10 backdrop-blur-xl">
                <tr class="text-left text-white/70 text-xs uppercase tracking-wider">
                    <th class="p-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="hash" class="w-4 h-4 text-white/50"></i> ID
                        </div>
                    </th>
                    <th class="p-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4 text-white/50"></i> Salesman
                        </div>
                    </th>
                    <th class="p-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4 text-white/50"></i> Date
                        </div>
                    </th>
                    <th class="p-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="activity" class="w-4 h-4 text-white/50"></i> Status
                        </div>
                    </th>
                    <th class="p-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="building-2" class="w-4 h-4 text-white/50"></i> Visits
                        </div>
                    </th>
                    <th class="p-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="clock" class="w-4 h-4 text-white/50"></i> Submitted
                        </div>
                    </th>
                    <th class="p-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="eye" class="w-4 h-4 text-white/50"></i> Action
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse ($visits as $v)
                    <tr class="hover:bg-white/5 transition-colors border-t border-white/10">
                        <td class="p-2 text-white/90 whitespace-nowrap">{{ $v->id }}</td>
                        <td class="p-2 text-white/90 whitespace-nowrap">{{ $v->salesman->name ?? '-' }}</td>
                        <td class="p-2 text-white/80 whitespace-nowrap">{{ optional($v->attendance)->date ? $v->attendance->date->format('d M Y') : '-' }}</td>
                        <td class="p-2 whitespace-nowrap">
                            @if ($v->status === 'pending')
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-500/20 border border-yellow-400/40 text-yellow-200 flex items-center gap-1 w-fit">
                                    <i data-lucide="loader-2" class="w-3 h-3"></i> {{ ucfirst($v->status) }}
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 border border-green-400/40 text-green-200 flex items-center gap-1 w-fit">
                                    <i data-lucide="check-square" class="w-3 h-3"></i> {{ ucfirst($v->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="p-2 text-white/80 whitespace-nowrap">{{ $v->items->count() }}</td>
                        <td class="p-2 text-white/80 whitespace-nowrap">{{ optional($v->submitted_at)->format('d M Y, h:i A') ?? '-' }}</td>
                        <td class="p-2 whitespace-nowrap">
                            <a href="{{ route('admin.out-of-city-visits.show', $v->id) }}"
                                class="inline-flex items-center gap-1 px-4 py-2 rounded-xl
                                    bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6]
                                    text-white font-semibold shadow hover:opacity-90 text-sm">
                                <i data-lucide="eye" class="w-4 h-4"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-white/60">
                            <i data-lucide="search-x" class="w-6 h-6 inline-block mr-2 text-white/40"></i>
                            No out of city visits found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-4">
        @forelse ($visits as $v)
            <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-pink-500/20 rounded-full flex items-center justify-center shrink-0">
                            <i data-lucide="user" class="w-5 h-5 text-pink-400"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold text-sm">{{ $v->salesman->name ?? '-' }}</h3>
                            <p class="text-white/60 text-xs">{{ optional($v->attendance)->date ? $v->attendance->date->format('d M Y') : '-' }}</p>
                        </div>
                    </div>
                    @if ($v->status === 'pending')
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-500/20 border border-yellow-400/40 text-yellow-200">
                            Pending
                        </span>
                    @else
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 border border-green-400/40 text-green-200">
                            Submitted
                        </span>
                    @endif
                </div>

                <p class="text-white/60 text-xs mb-3 flex items-center">
                    <i data-lucide="building-2" class="w-3 h-3 mr-1"></i>
                    {{ $v->items->count() }} customer(s) visited
                </p>

                <a href="{{ route('admin.out-of-city-visits.show', $v->id) }}"
                    class="block text-center py-2 rounded-lg
                        bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6]
                        text-white text-sm font-semibold shadow hover:opacity-90 flex items-center justify-center gap-1.5">
                    <i data-lucide="eye" class="w-4 h-4"></i> View Details
                </a>
            </div>
        @empty
            <div class="p-6 text-center text-white/60 bg-white/5 rounded-xl">
                <i data-lucide="search-x" class="w-6 h-6 inline-block mr-2 text-white/40"></i>
                No out of city visits found.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $visits->links() }}
    </div>

</div>

<style>
    @media print {
        nav, .sidebar, form, button, .pagination, footer {
            display: none !important;
        }
        body {
            background: white !important;
            color: black !important;
            margin: 0;
            padding: 0;
        }
        .bg-white\/10, .backdrop-blur-xl, .bg-white\/5 {
            background: transparent !important;
            backdrop-filter: none !important;
            border: none !important;
            box-shadow: none !important;
        }
        .hidden.md\:block { display: block !important; }
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            color: black !important;
        }
        th, td {
            border: 1px solid #ddd !important;
            padding: 8px !important;
            color: black !important;
            text-align: left !important;
        }
        th { background-color: #f2f2f2 !important; -webkit-print-color-adjust: exact; }
        h1 { color: black !important; margin-bottom: 20px; }
        .md\:hidden { display: none !important; }
        [data-lucide] { display: none !important; }
    }
</style>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

@endsection
