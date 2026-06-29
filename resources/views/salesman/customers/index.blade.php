@extends('layouts.app')
@section('title', 'My Customers')

@section('content')

    <div class="p-0">

        {{-- Header with Lucide Icon --}}
        <h1 class="text-3xl font-bold text-white mb-6 tracking-wide flex items-center">
            {{-- Lucide Icon: user-2 --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-user-2 mr-3">
                <circle cx="12" cy="7" r="4" />
                <path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
            </svg>
            My Customers
        </h1>

        {{-- Add Customer Button --}}
        <div class="mb-6 flex flex-col sm:flex-row justify-between gap-3">
            <a href="{{ route('salesman.customers.create') }}"
                class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6]
                   text-white font-semibold shadow hover:opacity-90 transition flex items-center justify-center gap-2">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Add Customer
            </a>

            <a href="{{ route('salesman.customers.export.all') }}"
                class="px-5 py-2 rounded-xl bg-green-600 hover:bg-green-700
                   text-white font-semibold shadow transition flex items-center justify-center gap-2">
                <i data-lucide="download" class="w-5 h-5"></i>
                Export Excel
            </a>
        </div>


        <div
            class="bg-white/10 backdrop-blur-xl border border-white/20
                 rounded-2xl shadow-xl overflow-hidden hidden md:block">

            <table class="w-full table-auto text-white">
                <thead>
                    <tr class="bg-white/10 text-white/80">
                        <th class="p-3 text-left">
                            <div class="flex items-center gap-2">
                                <i data-lucide="hash" class="w-4 h-4 text-white/50"></i>
                                Id
                            </div>
                        </th>

                        <th class="p-3 text-left">
                            <div class="flex items-center gap-2">
                                <i data-lucide="user" class="w-4 h-4 text-white/50"></i>
                                Name
                            </div>
                        </th>

                        <th class="p-3 text-left">
                            <div class="flex items-center gap-2">
                                <i data-lucide="user-check" class="w-4 h-4 text-white/50"></i>
                                Contact
                            </div>
                        </th>

                        <th class="p-3 text-left">
                            <div class="flex items-center gap-2">
                                <i data-lucide="phone" class="w-4 h-4 text-white/50"></i>
                                Mobile
                            </div>
                        </th>

                        <th class="p-3 text-left">
                            <div class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-white/50"></i>
                                Address
                            </div>
                        </th>

                        <th class="p-3 text-left">
                            <div class="flex items-center gap-2">
                                <i data-lucide="settings" class="w-4 h-4 text-white/50"></i>
                                Action
                            </div>
                        </th>
                    </tr>
                </thead>


                <tbody>
                    @forelse ($customers as $c)
                        <tr class="border-t border-white/10 hover:bg-white/5 transition">
                            <td class="p-3">{{ $c->id }}</td>
                            <td class="p-3">{{ $c->name }}</td>
                            <td class="p-3 text-white/80">
                                {{ $c->contact_person ?? '-' }}
                            </td>
                            <td class="p-3 text-white/80">
                                {{ $c->phone1 ?? '-' }}
                            </td>
                            <td class="p-3 text-white/60">
                                {{ $c->address ?? '-' }}
                            </td>
                            <td class="p-3 flex gap-2">

                                {{-- View --}}
                                <a href="{{ route('salesman.customers.show', $c->id) }}"
                                    class="px-3 py-2 rounded-lg bg-blue-500/30 border border-blue-400/40
              text-blue-100 text-sm hover:bg-blue-500/40 transition">
                                    View
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('salesman.customers.edit', $c->id) }}"
                                    class="px-3 py-2 rounded-lg bg-pink-500/30 border border-pink-400/40
              text-pink-100 text-sm hover:bg-pink-500/40 transition">
                                    Edit
                                </a>

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-white/70 bg-white/5">
                                No customers found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>



        <div class="md:hidden p-1 space-y-4">

            <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg">

                @forelse ($customers as $customer)
                    <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg mb-3">

                        {{-- Customer Name --}}
                        <h2 class="text-lg font-semibold text-white mb-2 flex items-center">
                            {{-- Lucide Icon: building (for company name) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-building mr-2 text-pink-400">
                                <rect width="16" height="20" x="4" y="2" rx="2" ry="2" />
                                <path d="M9 22v-4h6v4" />
                                <path d="M8 6h.01" />
                                <path d="M16 6h.01" />
                                <path d="M12 6h.01" />
                                <path d="M12 10h.01" />
                                <path d="M12 14h.01" />
                                <path d="M16 10h.01" />
                                <path d="M16 14h.01" />
                                <path d="M8 10h.01" />
                                <path d="M8 14h.01" />
                            </svg>
                            {{ $customer->name }}
                        </h2>

                        {{-- Contact Person --}}
                        <p class="text-white/70 text-sm mb-2 flex items-center">
                            {{-- Lucide Icon: contact --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-user-round mr-2 text-white/50">
                                <circle cx="12" cy="8" r="5" />
                                <path d="M20 21a8 8 0 0 0-16 0" />
                            </svg>
                            <span class="font-medium mr-1">Contact Person:</span> {{ $customer->contact_person ?? '—' }}
                        </p>

                        {{-- Phone --}}
                        <p class="text-white/80 text-sm mb-2 flex items-center">
                            {{-- Lucide Icon: phone --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-phone mr-2 text-white/50">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                            <span class="font-medium mr-1">Mobile:</span> {{ $customer->phone1 ?? '—' }}
                        </p>

                        {{-- Address --}}
                        <p class="text-white/70 text-sm mb-3 flex items-start">
                            {{-- Lucide Icon: map-pin --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-map-pin mr-2 text-white/50 flex-shrink-0 mt-0.5">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            <span class="font-medium mr-1">Address:</span> {{ $customer->address ?? '—' }}
                        </p>

                        <div class="flex gap-2">

                            {{-- View --}}
                            <a href="{{ route('salesman.customers.show', $customer->id) }}"
                                class="flex-1 px-3 py-2 rounded-lg bg-blue-500/30 border border-blue-400/40
              text-blue-100 text-sm text-center hover:bg-blue-500/40 transition">
                                View
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('salesman.customers.edit', $customer->id) }}"
                                class="flex-1 px-3 py-2 rounded-lg bg-pink-500/30 border border-pink-400/40
              text-pink-100 text-sm text-center hover:bg-pink-500/40 transition">
                                Edit
                            </a>

                        </div>


                    </div>

                @empty
                    <p class="text-center text-white/50">No customers found</p>
                @endforelse

            </div>

        </div>

    </div>
  <div class="mt-5">
            {{ $customers->links() }}
        </div>

@endsection
