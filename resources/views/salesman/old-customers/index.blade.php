@extends('layouts.app')

@section('title', 'My Imported Customers')

@section('content')

<div class=" text-white">

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <h1 class="text-3xl font-bold tracking-wide flex items-center gap-2">
        <i data-lucide="database" class="w-7 h-7 text-[#ff2ba6]"></i>
        Imported Customers (Old)
    </h1>

    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
        <a href="{{ route('salesman.old-customers.export.all') }}"
           class="px-5 py-3 rounded-xl w-full md:w-auto
                 bg-green-600 hover:bg-green-700
                 font-semibold shadow transition
                 flex items-center justify-center gap-2">
            <i data-lucide="download" class="w-5 h-5"></i>
            Export Excel
        </a>

        <a href="{{ route('salesman.old-customers.import.form') }}"
           class="px-5 py-3 rounded-xl w-full md:w-auto
                 bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6]
                 font-semibold shadow hover:opacity-90
                 flex items-center justify-center gap-2">
            <i data-lucide="upload" class="w-5 h-5"></i>
            Import Excel
        </a>
    </div>
</div>

<form method="GET" class="mb-6">
    <div class="relative w-full md:w-1/3">
        <i data-lucide="search" class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-white/60"></i>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search company / contact / phone / email"
               class="w-full pl-12 pr-4 py-3 rounded-xl
                     bg-white/10 border border-white/20
                     text-white focus:outline-none focus:ring-2 focus:ring-[#ff2ba6]">
    </div>
</form>

<div class="overflow-x-auto bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl hidden md:block">
    <table class="min-w-full text-sm">
     <thead class="bg-white/10 text-white/80">
    <tr>
        <th class="p-4 text-left w-20">
            <div class="flex items-center gap-2">
                <i data-lucide="hash" class="w-4 h-4 text-white/40"></i>
                Id
            </div>
        </th>

        <th class="p-4 text-left">
            <div class="flex items-center gap-2">
                <i data-lucide="building-2" class="w-4 h-4 text-[#ff2ba6]"></i>
                Company
            </div>
        </th>

        <th class="p-4 text-left">
            <div class="flex items-center gap-2">
                <i data-lucide="user-check" class="w-4 h-4 text-white/70"></i>
                Person
            </div>
        </th>

        <th class="p-4 text-left">
            <div class="flex items-center gap-2">
                <i data-lucide="phone" class="w-4 h-4 text-white/70"></i>
                Phone
            </div>
        </th>

        <th class="p-4 text-left">
            <div class="flex items-center gap-2">
                <i data-lucide="map-pin" class="w-4 h-4 text-white/70"></i>
                Address
            </div>
        </th>

        <th class="p-4 text-left">
            <div class="flex items-center gap-2">
                <i data-lucide="mail" class="w-4 h-4 text-white/70"></i>
                Email
            </div>
        </th>
    </tr>
</thead>

        <tbody>
            @forelse($customers as $c)
                <tr class="border-t border-white/10 hover:bg-white/5">
                    <td class="p-4 text-white/60 font-mono flex items-center gap-1">

                        {{ $c->id }}
                    </td>

                    <td class="p-4 font-semibold">
                        <div class="flex items-center gap-1">

                            {{ $c->company_name }}
                        </div>
                    </td>

                    <td class="p-4">
                        <div class="flex items-center gap-1">

                            {{ $c->contact_person ?? '-' }}
                        </div>
                    </td>

                    <td class="p-4">
                        <div class="flex items-center gap-1">

                            {{ $c->contact ?? '-' }}
                        </div>
                    </td>

                    <td class="p-4">
                        <div class="flex items-center gap-1">

                            {{ $c->address ?? '-' }}
                        </div>
                    </td>

                    <td class="p-4">
                        <div class="flex items-center gap-1">

                            {{ $c->email ?? '-' }}
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-white/60">No data found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="md:hidden p-1 space-y-4">
    @forelse($customers as $customer)
        <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg">

            <h2 class="text-lg font-semibold text-white mb-2 flex items-center">
                <i data-lucide="building-2" class="w-5 h-5 mr-2 text-pink-400"></i>
                {{ $customer->company_name }}
            </h2>

            <p class="text-white/60 text-sm mb-2 flex items-center gap-2">
                <i data-lucide="hash" class="w-4 h-4 text-white/40 shrink-0"></i>
                <strong>ID:</strong> {{ $customer->id }}
            </p>

            <p class="text-white/70 text-sm mb-1 flex items-start gap-2">
                <i data-lucide="user-check" class="w-4 h-4 mt-0.5 text-white/70 shrink-0"></i>
                <strong>Contact Person:</strong>
                <span class="break-words">
                    {{ $customer->contact_person ?? '—' }}
                </span>
            </p>

            <p class="text-white/80 text-sm mb-1 flex items-center gap-2">
                <i data-lucide="phone" class="w-4 h-4 text-white/70 shrink-0"></i>
                <strong>Mobile:</strong> {{ $customer->contact ?? '—' }}
            </p>

            <p class="text-white/70 text-sm mb-1 flex items-start gap-2">
                <i data-lucide="mail" class="w-4 h-4 mt-0.5 text-white/70 shrink-0"></i>
                <strong>Email:</strong>
                <span class="break-words">
                    {{ $customer->email ?? '—' }}
                </span>
            </p>

            <p class="text-white/70 text-sm flex items-start gap-2 mt-3">
                <i data-lucide="map-pin" class="w-4 h-4 mt-0.5 text-white/70 shrink-0"></i>
                <strong>Address:</strong>
                <span class="break-words">
                    {{ $customer->address ?? '—' }}
                </span>
            </p>

        </div>
    @empty
        <p class="text-center text-white/50">No records found</p>
    @endforelse
</div>

<div class="mt-6">
    {{ $customers->links() }}
</div>

</div>
@endsection
