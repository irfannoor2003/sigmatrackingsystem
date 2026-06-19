@extends('layouts.app')

@section('title','All Salesmen Visits Report')

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

    /* Hide horizontal scrollbar */
    body {
        overflow-x: hidden;
    }

    .overflow-x-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .overflow-x-auto::-webkit-scrollbar {
        display: none;
    }

    .report-table th,
    .report-table td {
        white-space: nowrap;
    }
</style>

<div class="report-main p-0 sm:p-0">

    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-wide mb-6">
        <i data-lucide="bar-chart-3" class="w-7 h-7 inline mr-2 text-[var(--hf-magenta-light)]"></i>
        Salesmen Visits Report
    </h1>

    {{-- Filter Form --}}
    <form method="GET"
        class="bg-white/10 backdrop-blur-xl mb-6 p-4 sm:p-5 rounded-2xl border border-white/20">

        <div class="flex flex-col lg:flex-row lg:items-center gap-4">

            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="relative flex items-center">
                    <i data-lucide="users" class="absolute left-3 w-5 h-5 text-white/50 pointer-events-none"></i>
                    <select name="salesman_id"
                        class="bg-white/10 text-white border border-white/20 p-2.5 pl-10 rounded-xl w-full appearance-none text-sm">
                        <option value="" class="text-black">All Salesmen</option>
                        @foreach($salesmen as $s)
                            <option value="{{ $s->id }}" {{ request('salesman_id') == $s->id ? 'selected' : '' }}
                                class="text-black">
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 absolute right-3 text-white/60 pointer-events-none"></i>
                </div>

                <div class="relative flex items-center">
                    <i data-lucide="calendar" class="absolute left-3 w-5 h-5 text-white/50 pointer-events-none"></i>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                        class="bg-white/10 text-white border border-white/20 p-2.5 pl-10 rounded-xl w-full text-sm">
                </div>

                <div class="relative flex items-center">
                    <i data-lucide="calendar" class="absolute left-3 w-5 h-5 text-white/50 pointer-events-none"></i>
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                        class="bg-white/10 text-white border border-white/20 p-2.5 pl-10 rounded-xl w-full text-sm">
                </div>

                <div class="relative flex items-center">
                    <i data-lucide="check-circle" class="absolute left-3 w-5 h-5 text-white/50 pointer-events-none"></i>
                    <select name="status"
                        class="bg-white/10 text-white border border-white/20 p-2.5 pl-10 rounded-xl w-full appearance-none text-sm">
                        <option value="" class="text-black">All Status</option>
                        <option value="started" {{ request('status')=='started'?'selected':'' }} class="text-black">Started</option>
                        <option value="completed" {{ request('status')=='completed'?'selected':'' }} class="text-black">Completed</option>
                        <option value="blocked" {{ request('status')=='blocked'?'selected':'' }} class="text-black">Blocked</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 absolute right-3 text-white/60 pointer-events-none"></i>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] text-white font-semibold shadow hover:opacity-90 text-sm">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    Filter
                </button>

                <button type="button" onclick="window.print()"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/20 border border-white/30 text-white font-semibold shadow hover:bg-white/30 text-sm">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    Print
                </button>

                <a href="{{ route('visits.export.monthly', request()->all()) }}"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold shadow hover:opacity-90 text-sm">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Export
                </a>
            </div>

        </div>
    </form>

    {{-- Desktop Table --}}
    <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 shadow-xl hidden md:block overflow-x-auto">
        <table class="report-table w-full">
            <thead class="bg-white/10 backdrop-blur-xl">
                <tr class="text-left text-white/70 text-xs uppercase tracking-wider">
                    <th class="p-3 print:hidden whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="hash" class="w-4 h-4 text-white/50"></i>
                            Id
                        </div>
                    </th>
                    <th class="p-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4 text-white/50"></i>
                            Salesman
                        </div>
                    </th>
                    <th class="p-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="building-2" class="w-4 h-4 text-white/50"></i>
                            Customer
                        </div>
                    </th>
                    <th class="p-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="target" class="w-4 h-4 text-white/50"></i>
                            Purpose
                        </div>
                    </th>
                    <th class="p-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="activity" class="w-4 h-4 text-white/50"></i>
                            Status
                        </div>
                    </th>
                    <th class="p-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="sticky-note" class="w-4 h-4 text-white/50"></i>
                            Notes
                        </div>
                    </th>
                    <th class="p-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="map" class="w-4 h-4 text-white/50"></i>
                            Km
                        </div>
                    </th>
                    <th class="p-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-white/50"></i>
                            Address
                        </div>
                    </th>
                    <th class="p-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4 text-white/50"></i>
                            Date
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse($visits as $v)
                    <tr class="hover:bg-white/5 transition-colors border-t border-white/10">
                        <td class="p-2 text-white/90 print:hidden whitespace-nowrap">{{ $v->id }}</td>
                        <td class="p-2 text-white/90 whitespace-nowrap">{{ $v->salesman->name }}</td>
                        <td class="p-2 text-white/90 whitespace-nowrap">{{ $v->customer->name }}</td>
                        <td class="p-2 whitespace-nowrap">
                            <a href="{{ route('salehead.visits.show', $v->id) }}"
                               class="text-indigo-300 hover:text-indigo-400 underline font-medium flex items-center gap-1">
                                <i data-lucide="link" class="w-3 h-3"></i>
                                {{ $v->purpose }}
                            </a>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            @if($v->status == 'started')
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-500/20 border border-yellow-400/40 text-yellow-200 flex items-center gap-1 w-fit">
                                    <i data-lucide="loader-2" class="w-3 h-3"></i>
                                    {{ ucfirst($v->status) }}
                                </span>
                            @elseif($v->status == 'completed')
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 border border-green-400/40 text-green-200 flex items-center gap-1 w-fit">
                                    <i data-lucide="check-square" class="w-3 h-3"></i>
                                    {{ ucfirst($v->status) }}
                                </span>
                            @elseif($v->status == 'blocked')
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-500/20 border border-red-400/40 text-red-200 flex items-center gap-1 w-fit">
                                    <i data-lucide="shield-off" class="w-3 h-3"></i>
                                    {{ ucfirst($v->status) }}
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-500/20 border border-red-400/40 text-red-200 flex items-center gap-1 w-fit">
                                    {{ ucfirst($v->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="p-2 text-white/80 text-sm max-w-[120px] truncate" title="{{ $v->notes ?? '' }}">{{ $v->notes ?? '-' }}</td>
                        <td class="p-2 text-white/80 whitespace-nowrap">{{ $v->distance_km ?? '-' }}</td>
                        <td class="p-2 text-white/80 text-sm max-w-[120px] truncate" title="{{ $v->customer->address ?? '' }}">{{ $v->customer->address ?? '-' }}</td>
                        <td class="p-2 text-white/80 text-sm whitespace-nowrap">{{ $v->started_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-white/60">
                            <i data-lucide="search-x" class="w-6 h-6 inline-block mr-2 text-white/40"></i>
                            No report found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-4">
        @forelse($visits as $v)
            <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg">

                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-pink-500/20 rounded-full flex items-center justify-center shrink-0">
                            <i data-lucide="user" class="w-5 h-5 text-pink-400"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold text-sm">{{ $v->salesman->name }}</h3>
                            <p class="text-white/60 text-xs">{{ $v->customer->name }}</p>
                        </div>
                    </div>
                    @if($v->status == 'started')
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-500/20 border border-yellow-400/40 text-yellow-200">
                            Started
                        </span>
                    @elseif($v->status == 'completed')
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 border border-green-400/40 text-green-200">
                            Completed
                        </span>
                    @elseif($v->status == 'blocked')
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-500/20 border border-red-400/40 text-red-200">
                            Blocked
                        </span>
                    @else
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-500/20 border border-red-400/40 text-red-200">
                            {{ ucfirst($v->status) }}
                        </span>
                    @endif
                </div>

                @if($v->customer->address)
                    <p class="text-white/60 text-xs mb-3 flex items-center">
                        <i data-lucide="map-pin" class="w-3 h-3 mr-1"></i>
                        {{ $v->customer->address }}
                    </p>
                @endif

                <div class="grid grid-cols-2 gap-3 mb-3 text-sm">
                    <div>
                        <span class="text-white/50 text-xs block mb-0.5">Purpose</span>
                        <a href="{{ route('salehead.visits.show', $v->id) }}"
                           class="text-indigo-300 text-sm flex items-center gap-1">
                            <i data-lucide="link" class="w-3 h-3"></i>
                            {{ $v->purpose }}
                        </a>
                    </div>
                    <div>
                        <span class="text-white/50 text-xs block mb-0.5">Distance</span>
                        <span class="text-white/90">{{ $v->distance_km ?? '-' }} km</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-white/50 text-xs block mb-0.5">Date</span>
                        <span class="text-white/90">{{ $v->started_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>

                @if($v->notes)
                    <div class="bg-white/5 rounded-lg p-3 text-sm">
                        <span class="text-white/50 text-xs block mb-1">Notes</span>
                        <span class="text-white/80 text-sm">{{ $v->notes }}</span>
                    </div>
                @endif

            </div>
        @empty
            <div class="p-6 text-center text-white/60 bg-white/5 rounded-xl">
                <i data-lucide="search-x" class="w-6 h-6 inline-block mr-2 text-white/40"></i>
                No report found
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

        .hidden.md\:block {
            display: block !important;
        }

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

        th {
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
        }

        h1 {
            color: black !important;
            margin-bottom: 20px;
        }

        .md\:hidden {
            display: none !important;
        }

        [data-lucide] {
            display: none !important;
        }
    }
</style>

@endsection
