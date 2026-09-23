@extends('layouts.app')
@section('title', 'Out of City Visits')

@section('content')
    <div class="p-0">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <h1 class="text-3xl font-bold text-white tracking-wide flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="mr-3 text-[#ff2ba6]">
                    <path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>
                </svg>
                Out of City Visits
            </h1>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-500/20 border border-green-400/40 text-green-100 rounded-xl backdrop-blur-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check mr-2"><path d="M20 6 9 17l-5-5"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('info'))
            <div class="mb-4 p-4 bg-blue-500/20 border border-blue-400/40 text-blue-100 rounded-xl backdrop-blur-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info mr-2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                {{ session('info') }}
            </div>
        @endif

        {{-- FILTER BAR --}}
        <form method="GET" action="{{ route('salesman.out-of-city-visits.index') }}"
            class="w-full sm:w-auto flex flex-col sm:flex-row sm:items-center gap-3
                backdrop-blur-xl rounded-xl print:hidden mb-6">

            <div class="flex items-center gap-2 text-white/70 text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-pink-400"><polygon points="3 4 21 4 14 12 14 20 10 18 10 12 3 4"/></svg>
                Filter
            </div>

            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-auto">
                    <select name="status" onchange="this.form.submit()"
                        class="appearance-none w-full sm:w-auto bg-white/10 text-white
                            px-4 py-2 pr-9 rounded-lg outline-none focus:bg-white/20 transition">
                        <option value="" class="text-black">All Visits</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }} class="text-black">Pending</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }} class="text-black">Submitted</option>
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/50 pointer-events-none"><polyline points="6 9 12 15 18 9"/></svg>
                </div>

                @if (request('status'))
                    <a href="{{ route('salesman.out-of-city-visits.index') }}"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg text-sm text-red-300
                            bg-red-500/10 hover:bg-red-500/20 transition text-center">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl overflow-hidden">
            <table class="w-full table-auto text-white hidden md:table">
                <thead>
                    <tr class="bg-white/10 text-white/80">
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
                                ID
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                Date
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><path d="M15.5 8H20v14H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8.5L20 8.5z"/><path d="M15 2v4a2 2 0 0 0 2 2h4"/></svg>
                                Attendance Note
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                                Status
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><path d="M4 20h16"/><path d="M16 8h-4"/><path d="M12 8h-2"/></svg>
                                Visits Added
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Submitted At
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                                Action
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visits as $v)
                        <tr class="border-t border-white/10 hover:bg-white/5 transition">
                            <td class="p-3">{{ $v->id }}</td>
                            <td class="p-3">{{ optional($v->attendance)->date ? $v->attendance->date->format('d M Y') : '-' }}</td>
                            <td class="p-3 max-w-xs">
                                <div class="truncate max-w-[200px]" title="{{ $v->attendance->note ?? '' }}">
                                    {{ \Illuminate\Support\Str::limit($v->attendance->note ?? '-', 30) }}
                                </div>
                            </td>
                            <td class="p-3">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs
                                    @if ($v->status === 'pending') bg-yellow-500/20 border border-yellow-400/40 text-yellow-200
                                    @else bg-green-500/20 border border-green-400/40 text-green-200 @endif">
                                    @if ($v->status === 'pending')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    @endif
                                    {{ ucfirst($v->status) }}
                                </span>
                            </td>
                            <td class="p-3">{{ $v->items->count() }}</td>
                            <td class="p-3">{{ optional($v->submitted_at)->format('d M Y, h:i A') ?? '-' }}</td>
                            <td class="p-3">
                                @if ($v->isPending())
                                    <a href="{{ route('salesman.out-of-city-visits.create', $v->id) }}"
                                        class="px-4 py-2 rounded-lg bg-[#ff2ba6]/20 border border-[#ff2ba6]/40
                                            text-[#ff2ba6] text-sm font-semibold hover:bg-[#ff2ba6]/30 transition inline-flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                        Add Details
                                    </a>
                                @else
                                    <a href="{{ route('salesman.out-of-city-visits.show', $v->id) }}"
                                        class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#ff2ba6] to-[#d41a8a]
                                            text-white text-sm font-semibold hover:opacity-90 transition inline-flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        View
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-white/70 bg-white/5">
                                No out of city visits found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Mobile View --}}
            <div class="md:hidden p-4 space-y-4">
                @forelse ($visits as $v)
                    <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-lg font-semibold text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-indigo-300"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                {{ optional($v->attendance)->date ? $v->attendance->date->format('d M Y') : '-' }}
                            </h2>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs
                                @if ($v->status === 'pending') bg-yellow-500/20 border border-yellow-400/40 text-yellow-200
                                @else bg-green-500/20 border border-green-400/40 text-green-200 @endif">
                                @if ($v->status === 'pending')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                @endif
                                {{ ucfirst($v->status) }}
                            </span>
                        </div>
                        <p class="text-white/60 text-sm mb-1 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white/50"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><path d="M4 20h16"/><path d="M16 8h-4"/><path d="M12 8h-2"/></svg>
                            {{ $v->items->count() }} customer(s) visited
                        </p>
                        @if ($v->submitted_at)
                            <p class="text-white/50 text-xs mb-3 flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white/50"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Submitted: {{ $v->submitted_at->format('d M Y, h:i A') }}
                            </p>
                        @endif
                        <div class="mt-3">
                            @if ($v->isPending())
                                <a href="{{ route('salesman.out-of-city-visits.create', $v->id) }}"
                                    class="w-full py-2 rounded-xl bg-[#ff2ba6]/20 border border-[#ff2ba6]/40
                                        text-[#ff2ba6] text-sm font-semibold hover:bg-[#ff2ba6]/30 transition flex items-center justify-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                    Add Visit Details
                                </a>
                            @else
                                <a href="{{ route('salesman.out-of-city-visits.show', $v->id) }}"
                                    class="w-full py-2 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#d41a8a]
                                        text-white text-sm font-semibold hover:opacity-90 transition flex items-center justify-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    View Details
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-white/50 p-6">No out of city visits found.</p>
                @endforelse
            </div>
        </div>

        <div class="mt-6">
            {{ $visits->links() }}
        </div>
    </div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endsection
