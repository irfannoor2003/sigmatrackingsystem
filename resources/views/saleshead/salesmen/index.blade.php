@extends('layouts.app')

@section('title','Salesmen')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-white tracking-wide flex items-center">
        <i data-lucide="users" class="w-7 h-7 mr-3 text-pink-400"></i> Salesmen
    </h2>
</div>

<div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg">

    <div class="hidden md:block">
        <table class="w-full">
            <thead>
                <tr class="text-left text-sm text-white/70 border-b border-white/20">
                    <th class="p-3">
                        <div class="flex items-center gap-2">
                            <i data-lucide="hash" class="w-4 h-4 text-white/50"></i>
                            Id
                        </div>
                    </th>
                    <th class="p-3">
                        <div class="flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4 text-white/50"></i>
                            Name
                        </div>
                    </th>
                    <th class="p-3">
                        <div class="flex items-center gap-2">
                            <i data-lucide="mail" class="w-4 h-4 text-white/50"></i>
                            Email
                        </div>
                    </th>
                    <th class="p-3">
                        <div class="flex items-center gap-2">
                            <i data-lucide="users" class="w-4 h-4 text-white/50"></i>
                            Customers
                        </div>
                    </th>
                    <th class="p-3">
                        <div class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-purple-300"></i>
                            Visits
                        </div>
                    </th>
                    <th class="p-3">
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4 text-white/50"></i>
                            Joined
                        </div>
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-white/10">
                @forelse($salesmen as $s)
                    <tr class="hover:bg-white/5 transition">
                        <td class="p-2 text-white">{{ $s->id }}</td>
                        <td class="p-2 text-white">
                            <div class="flex items-center">
                                <span>{{ $s->name }}</span>
                                @if($s->is_blocked)
                                    <span class="ml-2 inline-block px-2 py-0.5 text-xs rounded-full bg-red-600 text-white font-semibold">Blocked</span>
                                @endif
                            </div>
                        </td>
                        <td class="p-2 text-white/90">{{ $s->email }}</td>
                        <td class="p-2 text-white/80">{{ $s->customers_count }}</td>
                        <td class="p-2 text-purple-300 font-semibold">{{ $s->visits_count }}</td>
                        <td class="p-2 text-white/50 text-sm">{{ $s->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-white/70 bg-white/5">
                            <i data-lucide="search-x" class="w-5 h-5 inline-block mr-2"></i> No Salesman Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="md:hidden space-y-4">
        @forelse($salesmen as $s)
            <div class="p-4 bg-white/10 rounded-xl border border-white/10 shadow">
                <div class="text-lg font-semibold text-white flex items-center">
                    <i data-lucide="user" class="w-5 h-5 mr-2 text-pink-300"></i> {{ $s->name }}
                </div>
                <div class="text-white/70 text-sm flex items-center">
                    <i data-lucide="mail" class="w-4 h-4 mr-2"></i> {{ $s->email }}
                </div>

                <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                    <div class="bg-white/5 p-3 rounded-lg">
                        <div class="text-white/60 flex items-center"><i data-lucide="building" class="w-4 h-4 mr-2"></i> Total Customers</div>
                        <div class="text-white font-semibold text-lg">{{ $s->customers_count }}</div>
                    </div>
                    <div class="bg-white/5 p-3 rounded-lg">
                        <div class="text-white/60 flex items-center"><i data-lucide="map-pin" class="w-4 h-4 mr-2"></i> Total Visits</div>
                        <div class="text-purple-300 font-semibold text-lg">{{ $s->visits_count }}</div>
                    </div>
                </div>

                <div class="text-xs text-white/50 mt-3 flex items-center">
                    <i data-lucide="clock" class="w-3 h-3 mr-1"></i> Joined: {{ $s->created_at->format('Y-m-d') }}
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-white/70 bg-white/5 rounded-xl">
                <i data-lucide="search-x" class="w-5 h-5 inline-block mr-2"></i> No Salesman Found
            </div>
        @endforelse
    </div>

    <div class="mt-5">
        {{ $salesmen->links() }}
    </div>

</div>

@endsection
