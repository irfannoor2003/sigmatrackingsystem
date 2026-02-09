@extends('layouts.app')

@section('title','All Salesmen Visits Report')

@section('content')
<style>
    input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1); /* Changes a black icon to white */
}
</style>

<div class="bg-white/10 backdrop-blur-xl border border-white/20 p-8 rounded-2xl shadow-lg">

    <h1 class="text-2xl font-bold text-white mb-6 tracking-wide flex items-center">
        <i data-lucide="bar-chart-3" class="w-7 h-7 mr-3 text-pink-400"></i> Salesmen Visits Report
    </h1>

    <form method="GET" class="mb-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">

        <div class="relative flex items-center">
            <i data-lucide="users" class="absolute left-3 w-5 h-5 text-white/50 pointer-events-none"></i>
            <select name="salesman_id"
                class="bg-white/10 text-white border border-white/20 p-3 pl-10 rounded-xl outline-none w-full appearance-none">

                <option value="" class="text-black">All Salesmen</option>
                @foreach($salesmen as $s)
                    <option value="{{ $s->id }}"
                        {{ request('salesman_id') == $s->id ? 'selected' : '' }}
                        class="text-black">
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>
            <i data-lucide="chevron-down" class="w-4 h-4 absolute right-3 text-white/60 pointer-events-none"></i>
        </div>

        <div class="relative flex items-center">
            <i data-lucide="calendar" class="w-5 h-5 absolute left-3 text-white/60"></i>
            <input type="date"
                name="from_date"
                value="{{ request('from_date') }}"
                class="bg-white/10 text-white border border-white/20 p-3 pl-10 rounded-xl outline-none w-full">
        </div>

        <div class="relative flex items-center">
            <i data-lucide="calendar" class="w-5 h-5 absolute left-3 text-white/60"></i>
            <input type="date"
                name="to_date"
                value="{{ request('to_date') }}"
                class="bg-white/10 text-white border border-white/20 p-3 pl-10 rounded-xl outline-none w-full">
        </div>

        <div class="relative flex items-center">
            <i data-lucide="check-circle" class="w-5 h-5 absolute left-3 text-white/60"></i>
            <select name="status"
                class="bg-white/10 text-white border border-white/20 p-3 pl-10 rounded-xl outline-none w-full appearance-none">
                <option value="" class="text-black">All Status</option>
                <option value="started" {{ request('status')=='started'?'selected':'' }} class="text-black">Started</option>
                <option value="completed" {{ request('status')=='completed'?'selected':'' }} class="text-black">Completed</option>
            </select>
            <i data-lucide="chevron-down" class="w-4 h-4 absolute right-3 text-white/60 pointer-events-none"></i>
        </div>

        <div class="flex gap-3 sm:col-span-2 md:col-span-1">
            <button type="submit"
                class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] text-white font-semibold shadow hover:opacity-90 w-full sm:w-auto flex items-center justify-center">
                <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Filter
            </button>

            <button type="button"
                onclick="window.print()"
                class="px-4 py-2 rounded-xl bg-white/20 border border-white/30 text-white font-semibold shadow hover:bg-white/30 w-full sm:w-auto flex items-center justify-center">
                <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Print
            </button>
        </div>

    </form>


    <div class="overflow-x-auto mt-4 hidden md:block">
        <table class="w-full border border-white/20 rounded-xl overflow-hidden">
            <thead class="bg-white/10 backdrop-blur-xl border-b border-white/20">
    <tr>
        <th class="p-3 text-left text-white text-sm print:hidden">
            <div class="flex items-center gap-2">
                <i data-lucide="hash" class="w-4 h-4 text-white/60"></i>
                Id
            </div>
        </th>

        <th class="p-3 text-left text-white text-sm">
            <div class="flex items-center gap-2">
                <i data-lucide="user" class="w-4 h-4 text-white/60"></i>
                Salesman
            </div>
        </th>

        <th class="p-3 text-left text-white text-sm">
            <div class="flex items-center gap-2">
                <i data-lucide="building-2" class="w-4 h-4 text-white/60"></i>
                Customer
            </div>
        </th>

        <th class="p-3 text-left text-white text-sm">
            <div class="flex items-center gap-2">
                <i data-lucide="target" class="w-4 h-4 text-white/60"></i>
                Purpose
            </div>
        </th>

        <th class="p-3 text-left text-white text-sm">
            <div class="flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-green-400"></i>
                Status
            </div>
        </th>

        <th class="p-3 text-left text-white text-sm">
            <div class="flex items-center gap-2">
                <i data-lucide="sticky-note" class="w-4 h-4 text-white/60"></i>
                Notes
            </div>
        </th>
        <th class="p-3 text-left text-white text-sm">
            <div class="flex items-center gap-2">
                <i data-lucide="map" class="w-4 h-4 text-white/60"></i>
                Km
            </div>
        </th>

        <th class="p-3 text-left text-white text-sm">
            <div class="flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4 text-white/60"></i>
                Date
            </div>
        </th>
    </tr>
</thead>


            <tbody class="divide-y divide-white/10">

                @forelse($visits as $v)
                    <tr class="hover:bg-white/5 transition">
                        <td class="p-2 text-white/90 print:hidden">{{ $v->id }}</td>
                        <td class="p-2 text-white/90">{{ $v->salesman->name }}</td>
                        <td class="p-2 text-white/90">{{ $v->customer->name }}</td>

                        <td class="p-2 text-white/90">
                            <a href="{{ route('salehead.visits.show', $v->id) }}"
                               class="text-indigo-300 hover:underline flex items-center">
                                <i data-lucide="link" class="w-3 h-3 mr-1"></i> {{ $v->purpose }}
                            </a>
                        </td>

                        <td class="p-2 text-white/90">
                            <span class="inline-flex items-center">
                                @if($v->status == 'started')
                                    <i data-lucide="loader-2" class="w-4 h-4 mr-1 text-yellow-400"></i>
                                @elseif($v->status == 'completed')
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1 text-green-400"></i>
                                @endif
                                {{ ucfirst($v->status) }}
                            </span>
                        </td>
                        <td class="p-2 text-white/90">{{ $v->notes }}</td>
                        <td class="p-2 text-white/90">{{ $v->distance_km }}</td>

                        <td class="p-2 text-white/90">{{ $v->started_at->format('Y-m-d H:i') }}</td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="8"
                            class="p-6 text-center text-white/70 bg-white/5">
                            <i data-lucide="search-x" class="w-5 h-5 inline-block mr-2"></i> No report found
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>
    </div>



    <div class="md:hidden space-y-4 mt-6">

        @forelse($visits as $v)
        <div class="bg-white/10 border border-white/10 p-4 rounded-xl shadow">

            <div class="text-white font-semibold text-lg flex items-center">
                <i data-lucide="user" class="w-5 h-5 mr-2 text-pink-300"></i> {{ $v->salesman->name }}
            </div>

            <div class="text-white/70 text-sm mb-3 flex items-center">
                <i data-lucide="building" class="w-4 h-4 mr-2"></i> Customer: {{ $v->customer->name }}
            </div>

            <div class="grid grid-cols-1 gap-3">

                <div class="bg-white/5 p-3 rounded-xl">
                    <div class="text-white/60 text-xs flex items-center"><i data-lucide="target" class="w-4 h-4 mr-2"></i> Purpose</div>
                    <a href="{{ route('salehead.visits.show', $v->id) }}"
                       class="text-indigo-300 text-sm flex items-center">
                       <i data-lucide="link" class="w-3 h-3 mr-1"></i> {{ $v->purpose }}
                    </a>
                </div>

                <div class="bg-white/5 p-3 rounded-xl">
                    <div class="text-white/60 text-xs flex items-center"><i data-lucide="activity" class="w-4 h-4 mr-2"></i> Status</div>
                    <div class="text-white flex items-center">
                        @if($v->status == 'started')
                            <i data-lucide="loader-2" class="w-4 h-4 mr-2 text-yellow-400"></i>
                        @elseif($v->status == 'completed')
                            <i data-lucide="check-square" class="w-4 h-4 mr-2 text-green-400"></i>
                        @endif
                        {{ ucfirst($v->status) }}
                    </div>
                </div>

                @if($v->notes)
                    <div class="bg-white/5 p-3 rounded-xl">
                        <div class="text-white/60 text-xs flex items-center"><i data-lucide="sticky-note" class="w-4 h-4 mr-2"></i> Notes</div>
                        <div class="text-white">{{ $v->notes }}</div>
                    </div>
                @endif
                @if($v->distance_km)
                    <div class="bg-white/5 p-3 rounded-xl">
                        <div class="text-white/60 text-xs flex items-center"><i data-lucide="map" class="w-4 h-4 mr-2"></i> Km</div>
                        <div class="text-white">{{ $v->distance_km }}</div>
                    </div>
                @endif

                <div class="bg-white/5 p-3 rounded-xl">
                    <div class="text-white/60 text-xs flex items-center"><i data-lucide="clock" class="w-4 h-4 mr-2"></i> Date</div>
                    <div class="text-white">{{ $v->started_at->format('Y-m-d H:i') }}</div>
                </div>

            </div>

        </div>
        @empty
            <div class="p-6 text-center text-white/70 bg-white/5 rounded-xl">
                <i data-lucide="search-x" class="w-5 h-5 inline-block mr-2"></i> No report found
            </div>
        @endforelse

    </div>


    <div class="mt-6">
        {{ $visits->links() }}
    </div>

</div>
<style>
    @media print {
        /* 1. Hide everything except the report container */
        nav, .sidebar, form, button, .pagination, footer {
            display: none !important;
        }

        /* 2. Reset background for paper (White background, Black text) */
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

        /* 3. Ensure the table is visible and spans full width */
        .hidden.md\:block {
            display: block !important;
        }

        /* 4. Force Table Styling */
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

        /* 5. Clean up specific elements */
        h1 {
            color: black !important;
            margin-bottom: 20px;
        }

        /* Hide the mobile view cards entirely during print */
        .md\:hidden {
            display: none !important;
        }

        /* Remove icons if they look messy in print */
        [data-lucide] {
            display: none !important;
        }
    }
</style>
@endsection
