@extends('layouts.app')

@section('title', 'Imported Old Customers')

@section('content')

<div class=" text-white max-w-7xl mx-auto">

    {{-- Header --}}
    <h1 class="text-3xl font-bold mb-6 tracking-wide flex items-center gap-2">
        <i data-lucide="database" class="w-7 h-7 text-[#ff2ba6]"></i>
        Imported Customers (Old)
    </h1>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-xl bg-green-500/20 text-green-300 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
   <form method="GET" class="flex flex-col md:flex-row gap-4 mb-6">

    <div class="relative w-full md:w-1/2">
        <i data-lucide="search" class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-white/60"></i>
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Search company / contact / phone / email"
            class="w-full pl-12 pr-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white
                   focus:outline-none focus:ring-2 focus:ring-[#ff2ba6]/50">
    </div>

    {{-- Filter --}}
    <button type="submit"
        class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#ff5fcf]
               font-semibold flex items-center justify-center gap-2">
        <i data-lucide="filter" class="w-5 h-5"></i>
        Filter
    </button>

    {{-- Reset --}}

       <a href="{{ route('admin.old-customers.index') }}"
           class="px-6 py-3 rounded-xl border border-white/20 text-white/80
                  hover:bg-white/10 font-semibold flex items-center justify-center gap-2">
            <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
            Reset
        </a>


</form>


    {{-- Action Bar --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <a href="{{ route('admin.old-customers.import.form') }}"
            class="px-5 py-3 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#ff5fcf]
                   font-semibold flex items-center justify-center gap-2">
            <i data-lucide="upload" class="w-5 h-5"></i>
            Import CSV
        </a>
        <button id="send-old-email-btn" disabled
            class="px-5 py-3 rounded-xl bg-gradient-to-r from-blue-500/50 to-indigo-500/50
                   font-semibold disabled:opacity-50 flex items-center justify-center gap-2">
            <i data-lucide="mail" class="w-5 h-5"></i>
            Send Email
        </button>
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="overflow-x-auto bg-white/5 border border-white/10 rounded-2xl hidden md:block">
        <table class="min-w-full text-sm">
            <thead class="bg-white/10 text-white/80  text-xs">
                <tr>
                    <th class="p-4 text-center">
                        <input type="checkbox" id="select-all-old">
                    </th>
                    <th class="p-4"><i data-lucide="hash" class="inline w-4 h-4 mr-1"></i>ID</th>
                    <th class="p-4"><i data-lucide="building-2" class="inline w-4 h-4 mr-1"></i>Company</th>
                    <th class="p-4"><i data-lucide="user" class="inline w-4 h-4 mr-1"></i>Person</th>
                    <th class="p-4"><i data-lucide="mail" class="inline w-4 h-4 mr-1"></i>Email</th>
                    <th class="p-4"><i data-lucide="phone" class="inline w-4 h-4 mr-1"></i>Mobile</th>
                    <th class="p-4"><i data-lucide="map-pin" class="inline w-4 h-4 mr-1"></i>Address</th>
                </tr>
            </thead>

            <tbody>
                @forelse($customers as $customer)
                    <tr class="border-t border-white/10 hover:bg-white/5">
                        <td class="p-4 text-center">
                            <input type="checkbox" class="old-customer-checkbox" value="{{ $customer->id }}">
                        </td>
                        <td class="p-4">#{{ $customer->id }}</td>
                        <td class="p-4 font-semibold">{{ $customer->company_name }}</td>
                        <td class="p-4">{{ $customer->contact_person ?? '-' }}</td>
                        <td class="p-4">{{ $customer->email ?? '-' }}</td>
                        <td class="p-4">{{ $customer->contact ?? '-' }}</td>
                        <td class="p-4">{{ $customer->address ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-white/60">No records found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARDS --}}
    <div class="md:hidden space-y-4">
        @forelse($customers as $customer)
            <div class="bg-white/5 border border-white/10 rounded-xl p-4 space-y-2">
                <div class="flex justify-between items-center">
                    <div class="font-semibold flex items-center gap-2">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                        {{ $customer->company_name }}
                    </div>
                    <input type="checkbox" class="old-customer-checkbox" value="{{ $customer->id }}">
                </div>
<div class="text-sm text-white/70 flex items-center gap-2">
                    <i data-lucide="hash" class="w-4 h-4"></i>
                    {{ $customer->id ?? '-' }}
                </div>
                <div class="text-sm text-white/70 flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    {{ $customer->contact_person ?? '-' }}
                </div>

                <div class="text-sm text-white/70 flex items-center gap-2">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                    {{ $customer->email ?? '-' }}
                </div>

                <div class="text-sm text-white/70 flex items-center gap-2">
                    <i data-lucide="phone" class="w-4 h-4"></i>
                    {{ $customer->contact ?? '-' }}
                </div>
                <div class="text-sm text-white/70 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    {{ $customer->address ?? '-' }}
                </div>
            </div>
        @empty
            <div class="text-center text-white/60 py-10">
                No records found
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $customers->links() }}
    </div>

</div>

{{-- EMAIL MODAL (unchanged UI, icons added) --}}
<div id="email-modal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
    <div class="bg-slate-900 p-6 rounded-2xl w-full max-w-xl">
        <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
            <i data-lucide="send"></i>
            Send Promotion Email
        </h2>

        <form method="POST" action="{{ route('admin.promotions.send') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="customer_type" value="old">
            <div id="selected-customers-inputs"></div>

            <input type="text" name="subject" required
                   class="w-full p-3 rounded-xl bg-white/10 mb-3"
                   placeholder="Subject">

            <textarea name="message" rows="5" required
                      class="w-full p-3 rounded-xl bg-white/10 mb-3"
                      placeholder="Message"></textarea>

            <input type="file" name="attachment" class="mb-4">

            <div class="flex justify-end gap-3">
                <button type="button" id="close-email-modal"
                        class="px-4 py-2 border rounded-xl flex items-center gap-2">
                    <i data-lucide="x"></i> Cancel
                </button>

                <button type="submit"
                        class="px-6 py-2 bg-gradient-to-r from-[#ff2ba6] to-[#ff5fcf]
                               rounded-xl font-semibold flex items-center gap-2">
                    <i data-lucide="send"></i> Send
                </button>
            </div>
        </form>
    </div>
</div>



    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const sendBtn = document.getElementById('send-old-email-btn');
            const modal = document.getElementById('email-modal');
            const closeBtn = document.getElementById('close-email-modal');
            const inputsDiv = document.getElementById('selected-customers-inputs');
            const selectAll = document.getElementById('select-all-old');
            const checkboxes = document.querySelectorAll('.old-customer-checkbox');

            function updateBtn() {
                sendBtn.disabled =
                    document.querySelectorAll('.old-customer-checkbox:checked').length === 0;
            }

            selectAll.addEventListener('change', () => {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateBtn();
            });

            checkboxes.forEach(cb => cb.addEventListener('change', updateBtn));

            sendBtn.addEventListener('click', () => {
                inputsDiv.innerHTML = '';

                document.querySelectorAll('.old-customer-checkbox:checked')
                    .forEach(cb => {
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'customer_ids[]';
                        input.value = cb.value;
                        inputsDiv.appendChild(input);
                    });

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });

            closeBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });

        });
    </script>

@endsection
