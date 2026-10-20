@extends('layouts.app')

@section('title', 'Imported Old Customers')

@section('content')

<div>

    {{-- Header --}}
    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="database" class="w-6 h-6"></i></span>
            Imported Customers (Old)
        </h1>
    </div>


    {{-- Filters --}}
    <x-filter-bar>
        <x-filter-field icon="search" class="lg:col-span-2">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search company / contact / phone / email"
                class="input">
        </x-filter-field>

        <x-filter-actions :reset="route('admin.old-customers.index')" />
    </x-filter-bar>

    {{-- Action Bar --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <a href="{{ route('admin.old-customers.import.form') }}" class="btn btn-primary">
            <i data-lucide="upload" class="w-4 h-4"></i>
            Import CSV
        </a>
        <button id="send-old-email-btn" disabled class="btn btn-outline">
            <i data-lucide="mail" class="w-4 h-4"></i>
            Send Email
        </button>
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="hidden md:block tbl-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th class="text-center !w-10">
                        <input type="checkbox" id="select-all-old" class="checkbox cursor-pointer">
                    </th>
                    <th>ID</th>
                    <th>Company</th>
                    <th>Person</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Address</th>
                </tr>
            </thead>

            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="checkbox cursor-pointer old-customer-checkbox" value="{{ $customer->id }}">
                        </td>
                        <td class="muted">#{{ $customer->id }}</td>
                        <td class="font-semibold text-[var(--text)]">{{ $customer->company_name }}</td>
                        <td>{{ $customer->contact_person ?? '-' }}</td>
                        <td>{{ $customer->email ?? '-' }}</td>
                        <td>{{ $customer->contact ?? '-' }}</td>
                        <td>{{ $customer->address ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center muted">No records found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARDS --}}
    <div class="md:hidden space-y-4">
        @forelse($customers as $customer)
            <div class="card !rounded-2xl p-4 space-y-2">
                <div class="flex justify-between items-center">
                    <div class="font-semibold text-[var(--text)] flex items-center gap-2">
                        <i data-lucide="building-2" class="w-4 h-4 text-[var(--accent)]"></i>
                        {{ $customer->company_name }}
                    </div>
                    <input type="checkbox" class="checkbox cursor-pointer old-customer-checkbox" value="{{ $customer->id }}">
                </div>
                <div class="text-sm text-[var(--text-soft)] flex items-center gap-2">
                    <i data-lucide="hash" class="w-4 h-4 muted"></i>
                    #{{ $customer->id ?? '-' }}
                </div>
                <div class="text-sm text-[var(--text-soft)] flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 muted"></i>
                    {{ $customer->contact_person ?? '-' }}
                </div>

                <div class="text-sm text-[var(--text-soft)] flex items-center gap-2">
                    <i data-lucide="mail" class="w-4 h-4 muted"></i>
                    {{ $customer->email ?? '-' }}
                </div>

                <div class="text-sm text-[var(--text-soft)] flex items-center gap-2">
                    <i data-lucide="phone" class="w-4 h-4 muted"></i>
                    {{ $customer->contact ?? '-' }}
                </div>
                <div class="text-sm text-[var(--text-soft)] flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 muted"></i>
                    {{ $customer->address ?? '-' }}
                </div>
            </div>
        @empty
            <div class="card !rounded-2xl text-center muted py-10">No records found</div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $customers->links() }}
    </div>

</div>

{{-- EMAIL MODAL --}}
<div id="email-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 px-4">
    <div class="card p-6 !rounded-2xl w-full max-w-xl shadow-card-lg">
        <h2 class="text-xl font-display font-bold text-[var(--text)] mb-4 flex items-center gap-2">
            <i data-lucide="send" class="w-5 h-5 text-[var(--accent)]"></i>
            Send Promotion Email
        </h2>

        <form method="POST" action="{{ route('admin.promotions.send') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="customer_type" value="old">
            <div id="selected-customers-inputs"></div>

            <div class="mb-3">
                <label class="label">Subject</label>
                <input type="text" name="subject" required class="input" placeholder="Subject">
            </div>

            <div class="mb-3">
                <label class="label">Message</label>
                <textarea name="message" rows="5" required class="textarea" placeholder="Message"></textarea>
            </div>

            <div class="mb-4">
                <label class="label">Attachment (optional)</label>
                <input type="file" name="attachment" class="text-sm text-[var(--text-muted)]">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="close-email-modal" class="btn btn-outline">
                    <i data-lucide="x" class="w-4 h-4"></i> Cancel
                </button>

                <button type="submit" class="btn btn-primary">
                    <i data-lucide="send" class="w-4 h-4"></i> Send
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