@extends('layouts.app')

@section('title', 'All Customers')

@section('content')

    <div>

        <div class="page-head">
            <h1 class="page-title">
                <span class="page-ico"><i data-lucide="building-2" class="w-6 h-6"></i></span>
                All Customers
            </h1>
        </div>

        <x-filter-bar>
            <x-filter-field icon="map-pin">
                <select name="city_id" class="select">
                    <option value="">All Cities</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </x-filter-field>

            <x-filter-field icon="users">
                <select name="salesman_id" class="select">
                    <option value="">All Salesmen</option>
                    @foreach ($salesmen as $s)
                        <option value="{{ $s->id }}" {{ request('salesman_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </x-filter-field>

            <x-filter-field icon="tag">
                <select name="category_id" class="select">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </x-filter-field>

            <x-filter-field icon="factory">
                <select name="industry_id" class="select">
                    <option value="">All Industries</option>
                    @foreach ($industries as $ind)
                        <option value="{{ $ind->id }}" {{ request('industry_id') == $ind->id ? 'selected' : '' }}>
                            {{ $ind->name }}
                        </option>
                    @endforeach
                </select>
            </x-filter-field>

            <x-filter-field icon="search" class="lg:col-span-2">
                <input type="text" name="search" placeholder="Search name / phone" value="{{ request('search') }}" class="input">
            </x-filter-field>

            <x-filter-actions :reset="route('admin.customers.index')" />
        </x-filter-bar>

        <form action="{{ route('admin.customers.export.bulk') }}" method="POST">
            @csrf

            <div class="hidden md:block tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th class="!w-10">
                                <input type="checkbox" id="select-all" class="checkbox cursor-pointer">
                            </th>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Industry</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($customers as $c)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_customers[]" value="{{ $c->id }}"
                                        class="checkbox cursor-pointer customer-checkbox">
                                </td>
                                <td class="muted">{{ $c->id }}</td>
                                <td>
                                    <a href="{{ route('admin.customers.show', $c->id) }}"
                                        class="font-semibold text-[var(--accent)] hover:underline">
                                        {{ $c->name }}
                                    </a>
                                </td>
                                <td>{{ $c->phone1 }}</td>
                                <td><span class="badge badge-gray">{{ $c->city->name ?? '-' }}</span></td>
                                <td>{{ $c->industry->name ?? '-' }}</td>
                                <td>{{ $c->category->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.customers.export.single', $c->id) }}"
                                        class="btn btn-xs btn-success">
                                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                        Export
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-10 text-center muted">No Customers Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="flex flex-col sm:flex-row gap-3 mt-4 mb-3 ml-2 px-1">

                    <button type="submit" id="export-selected-btn" disabled
                        class="btn btn-success w-full sm:w-auto hidden sm:flex">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        Export Selected
                    </button>

                    <a href="{{ route('admin.customers.export.all') }}"
                        class="btn btn-primary w-full sm:w-auto text-center">
                        <i data-lucide="archive" class="w-4 h-4"></i>
                        Export All
                    </a>

                    <button type="button" id="send-email-btn" disabled
                        class="btn btn-outline w-full sm:w-auto hidden sm:flex">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                        Send Email
                    </button>
                </div>
            </div>

        </form>

        <div class="md:hidden p-0 space-y-4 mt-4">

            @forelse ($customers as $c)
                <div class="card !rounded-2xl p-4">

                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-semibold text-[var(--text)]">
                            <i data-lucide="user" class="w-4 h-4 inline mr-1 text-[var(--accent)]"></i>{{ $c->name }}
                        </h2>
                        <input type="checkbox" name="selected_customers[]" value="{{ $c->id }}"
                            class="checkbox cursor-pointer customer-checkbox">
                    </div>

                    <p class="text-[var(--text-soft)] text-sm mb-1">
                        <span class="text-[var(--text)] font-semibold">
                            <i data-lucide="phone" class="w-4 h-4 inline mr-1 muted"></i>Phone:
                        </span> {{ $c->phone1 ?? '—' }}
                    </p>

                    <p class="text-[var(--text-soft)] text-sm mb-1">
                        <span class="text-[var(--text)] font-semibold">
                            <i data-lucide="map-pin" class="w-4 h-4 inline mr-1 muted"></i>City:
                        </span> {{ $c->city->name ?? '—' }}
                    </p>

                    <p class="text-[var(--text-soft)] text-sm mb-1">
                        <span class="text-[var(--text)] font-semibold">
                            <i data-lucide="factory" class="w-4 h-4 inline mr-1 muted"></i>Industry:
                        </span> {{ $c->industry->name ?? '—' }}
                    </p>

                    <p class="text-[var(--text-soft)] text-sm mb-3">
                        <span class="text-[var(--text)] font-semibold">
                            <i data-lucide="tag" class="w-4 h-4 inline mr-1 muted"></i>Category:
                        </span> {{ $c->category->name ?? '—' }}
                    </p>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.customers.show', $c->id) }}"
                            class="btn btn-sm btn-outline flex-1">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            View Details
                        </a>

                        <a href="{{ route('admin.customers.export.single', $c->id) }}"
                            class="btn btn-sm btn-success flex-1">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            Export
                        </a>
                    </div>

                </div>
            @empty
                <p class="text-center muted">No customers found</p>
            @endforelse

        </div>

        <div class="mt-5">
            {{ $customers->links() }}
        </div>

    </div>

    <script>
        function updateSendEmailButton() {
            const sendEmailBtn = document.getElementById('send-email-btn');
            if (!sendEmailBtn) return;

            const checkedCount = document.querySelectorAll('.customer-checkbox:checked').length;

            sendEmailBtn.disabled = checkedCount === 0;
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectAllCheckbox = document.getElementById('select-all');
            const customerCheckboxes = document.querySelectorAll("input[name='selected_customers[]']");
            const exportSelectedBtn = document.getElementById('export-selected-btn');

            function updateExportButtonState() {
                const checkedCount = document.querySelectorAll(".customer-checkbox:checked").length;
                if (exportSelectedBtn) {
                    exportSelectedBtn.disabled = checkedCount === 0;
                }
            }

            // 1. Select All Toggle
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    customerCheckboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });

                    updateExportButtonState();
                    updateSendEmailButton(); // ✅ REQUIRED
                });
            }

            // 2. Individual Checkbox Change (for Export Button state)
            customerCheckboxes.forEach(cb => {
                cb.classList.add('customer-checkbox'); // Ensure all individual checkboxes have the class
                cb.addEventListener('change', updateExportButtonState);
            });

            // Initial state check
            updateExportButtonState();
        });
    </script>

    <div id="email-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 px-4">

        <div class="card relative w-full max-w-2xl !rounded-[1.5rem] p-6 shadow-card-lg">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-display font-bold text-[var(--text)] flex items-center gap-2">
                    <i data-lucide="mail" class="w-6 h-6 text-[var(--accent)]"></i>
                    Send Promotion Email
                </h2>

                <button id="close-email-modal" class="icon-btn">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.promotions.send') }}" enctype="multipart/form-data">
                @csrf

                <div id="selected-customers-inputs"></div>
                <input type="hidden" name="customer_type" value="new">

                <div class="mb-4">
                    <label class="label">Subject</label>
                    <input type="text" name="subject" required class="input" placeholder="Subject">
                </div>

                <div class="mb-4">
                    <label class="label">Email Message</label>
                    <textarea name="message" rows="6" required class="textarea" placeholder="Write something attractive..."></textarea>
                </div>

                <div class="mb-5">
                    <label class="label">Attachment (optional)</label>
                    <input type="file" name="attachment" class="text-sm text-[var(--text-muted)]">
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" id="preview-email" class="btn btn-outline">Preview</button>
                    <button type="button" id="cancel-email" class="btn btn-ghost">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const sendEmailBtn = document.getElementById('send-email-btn');
            const emailModal = document.getElementById('email-modal');
            const closeEmailModal = document.getElementById('close-email-modal');
            const cancelEmail = document.getElementById('cancel-email');
            const selectedInputsDiv = document.getElementById('selected-customers-inputs');

            function updateSendEmailButton() {
                const checkedCount = document.querySelectorAll('.customer-checkbox:checked').length;

                sendEmailBtn.disabled = checkedCount === 0;
            }

            // Open modal
            sendEmailBtn.addEventListener('click', () => {
                selectedInputsDiv.innerHTML = '';

                document.querySelectorAll('.customer-checkbox:checked').forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'customer_ids[]';
                    input.value = cb.value;
                    selectedInputsDiv.appendChild(input);
                });

                emailModal.classList.remove('hidden');
                emailModal.classList.add('flex');
            });

            // Close modal
            [closeEmailModal, cancelEmail].forEach(btn => {
                btn.addEventListener('click', () => {
                    emailModal.classList.add('hidden');
                    emailModal.classList.remove('flex');
                });
            });

            document.querySelectorAll('.customer-checkbox').forEach(cb => {
                cb.addEventListener('change', updateSendEmailButton);
            });

            updateSendEmailButton();
        });
    </script>

    <div id="preview-modal"
        class="fixed inset-0 bg-black/60 backdrop-blur-md hidden items-center justify-center z-[60] px-4">

        <div class="card relative w-full max-w-2xl !rounded-[1.5rem] overflow-hidden shadow-card-lg">

            <div class="flex justify-between items-center px-6 py-4 border-b border-[var(--border)] bg-[var(--surface-2)]">
                <div class="flex items-center gap-2">
                    <span class="dot bg-red-500"></span>
                    <span class="dot bg-amber-400"></span>
                    <span class="dot bg-emerald-500"></span>
                    <span class="ml-2 text-xs font-medium muted uppercase tracking-widest">Email Preview</span>
                </div>
                <button id="close-preview" class="icon-btn">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="p-6">
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="text-[10px] font-bold text-[var(--accent)] uppercase tracking-[0.2em] mb-1 block">Subject Line</label>
                        <h2 id="preview-subject" class="text-2xl font-display font-bold text-[var(--text)] leading-tight"></h2>
                    </div>

                    <div class="flex items-center gap-3 py-3 border-y border-[var(--border)]">
                        <div class="avatar avatar-md">A</div>
                        <div>
                            <p class="text-sm font-semibold text-[var(--text)]">Admin Team <span class="muted font-normal">&lt;noreply@yourbrand.com&gt;</span></p>
                            <p class="text-xs muted">To: Selected Customers</p>
                        </div>
                    </div>
                </div>

                <div class="relative min-h-[200px] rounded-2xl soft-panel p-6 overflow-y-auto max-h-[40vh]">
                    <div id="preview-body" class="whitespace-pre-line text-[var(--text-soft)] leading-relaxed text-base"></div>
                </div>

                <div id="preview-attachment-container" class="mt-6 hidden">
                    <div class="inline-flex items-center gap-3 px-4 py-2 rounded-xl bg-[var(--accent-soft)] border border-[var(--border)]">
                        <i data-lucide="file-text" class="w-4 h-4 text-[var(--accent)]"></i>
                        <span id="preview-attachment" class="text-sm text-[var(--text)]"></span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end px-6 py-6 bg-[var(--surface-2)] border-t border-[var(--border)]">
                <button id="close-preview-btn" class="btn btn-outline">Back to Editor</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const previewBtn = document.getElementById('preview-email');
            const previewModal = document.getElementById('preview-modal');

            if (!previewBtn || !previewModal) return;

            const subjectInput = document.querySelector("input[name='subject']");
            const messageInput = document.querySelector("textarea[name='message']");
            const attachmentInput = document.querySelector("input[name='attachment']");

            const previewSubject = document.getElementById('preview-subject');
            const previewBody = document.getElementById('preview-body');
            const previewAttachment = document.getElementById('preview-attachment');

            const closePreview = document.getElementById('close-preview');
            const closePreviewBtn = document.getElementById('close-preview-btn');

            // OPEN PREVIEW
            previewBtn.addEventListener('click', () => {

                previewSubject.textContent =
                    subjectInput.value.trim() || '(No Subject)';

                previewBody.textContent =
                    messageInput.value.trim() || '(No Message)';

                if (attachmentInput.files.length > 0) {
                    previewAttachment.textContent =
                        'Attachment: ' + attachmentInput.files[0].name;
                    previewAttachment.classList.remove('hidden');
                } else {
                    previewAttachment.classList.add('hidden');
                }

                previewModal.classList.remove('hidden');
                previewModal.classList.add('flex');
            });

            // CLOSE PREVIEW
            [closePreview, closePreviewBtn].forEach(btn => {
                btn.addEventListener('click', () => {
                    previewModal.classList.add('hidden');
                    previewModal.classList.remove('flex');
                });
            });
        });
    </script>

@endsection