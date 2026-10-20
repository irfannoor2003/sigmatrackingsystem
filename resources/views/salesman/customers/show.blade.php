@extends('layouts.app')

@section('title', 'Customer Details: ' . $customer->name)

@section('content')
<div class="mt-10 px-0">

    <div class="card !rounded-3xl shadow-card-lg p-6 md:p-8">

        <div class="page-head !mb-6">
            <h2 class="page-title">
                <span class="page-ico"><i data-lucide="clipboard-list" class="w-6 h-6"></i></span>
                Customer Details
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">

            <div>
                {{-- Customer Name --}}
                <h3 class="text-xl md:text-2xl font-semibold text-[var(--text)] flex items-center">
                    <i data-lucide="building" class="w-5 h-5 mr-2 text-[var(--text-soft)]"></i>
                    {{ $customer->name }}
                </h3>

                <p class="text-indigo-500 font-medium mb-4 capitalize ml-8">
                    {{ $customer->category->name ?? 'No Category' }}
                </p>

                <div class="space-y-3">

                    <div class="flex justify-between soft-panel py-3 px-4 rounded-xl items-center">
                        <span class="text-[var(--text-soft)] flex items-center">
                            <i data-lucide="user-round" class="w-4 h-4 mr-2"></i>
                            Contact Person
                        </span>
                        <span class="text-[var(--text)]">{{ $customer->contact_person ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between soft-panel py-3 px-4 rounded-xl items-center">
                        <span class="text-[var(--text-soft)] flex items-center">
                            <i data-lucide="phone" class="w-4 h-4 mr-2"></i>
                            Mobile 1
                        </span>
                        <span class="text-[var(--text)]">{{ $customer->phone1 ?? 'N/A'}}</span>
                    </div>

                    <div class="flex justify-between soft-panel py-3 px-4 rounded-xl items-center">
                        <span class="text-[var(--text-soft)] flex items-center">
                            <i data-lucide="phone-call" class="w-4 h-4 mr-2"></i>
                            Mobile 2
                        </span>
                        <span class="text-[var(--text)]">{{ $customer->phone2 ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between soft-panel py-3 px-4 rounded-xl items-center">
                        <span class="text-[var(--text-soft)] flex items-center">
                            <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                            Email
                        </span>
                        <span class="text-[var(--text)]">{{ $customer->email ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between soft-panel py-3 px-4 rounded-xl items-center">
                        <span class="text-[var(--text-soft)] flex items-center">
                            <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i>
                            City
                        </span>
                        <span class="text-[var(--text)] capitalize">{{ $customer->city?->name ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between soft-panel py-3 px-4 rounded-xl items-center">
                        <span class="text-[var(--text-soft)] flex items-center">
                            <i data-lucide="briefcase" class="w-4 h-4 mr-2"></i>
                            Industry
                        </span>
                        <span class="text-[var(--text)]">{{ $customer->industry?->name ?? 'N/A' }}</span>
                    </div>
                     <div class="flex justify-between soft-panel py-3 px-4 rounded-xl items-center">
                        <span class="text-[var(--text-soft)] flex items-center">
                            <i data-lucide="tag" class="w-4 h-4 mr-2"></i>
                            Category
                        </span>
                        <span class="text-[var(--text)]">{{ $customer->category?->name ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between soft-panel py-3 px-4 rounded-xl items-start">
                        <span class="text-[var(--text-soft)] flex items-center mt-0.5">
                            <i data-lucide="map" class="w-4 h-4 mr-2 flex-shrink-0"></i>
                            Address
                        </span>
                        <span class="text-[var(--text)] text-right break-words max-w-[60%]">{{ $customer->address ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between soft-panel py-3 px-4 rounded-xl items-center">
                        <span class="text-[var(--text-soft)] flex items-center">
                            <i data-lucide="calendar-check" class="w-4 h-4 mr-2"></i>
                            Added On
                        </span>
                        <span class="text-[var(--text)]">{{ $customer->created_at->format('d M, Y') }}</span>
                    </div>

                </div>

                <div class="mt-6 flex gap-4">
                    <button id="showImageBtn" class="btn btn-primary">
                        <i data-lucide="image" class="w-5 h-5 mr-2"></i>
                        Preview Document
                    </button>

                    <button id="closeImageBtn" class="btn btn-danger hidden">
                        <i data-lucide="x" class="w-5 h-5 mr-2"></i>
                        Close Preview
                    </button>
                </div>
            </div>
        </div>

        <div id="imageDisplaySection" class="mt-8 hidden">

            <h3 class="text-xl font-semibold text-[var(--text)] mb-4 flex items-center">
                <i data-lucide="file-text" class="w-5 h-5 mr-2 text-[var(--text-soft)]"></i>
                Customer Document
            </h3>

         @if ($customer->image)
    <div class="rounded-xl overflow-hidden bg-[var(--accent-softer)] border border-[var(--border)] shadow-lg cursor-pointer"
         id="imageBox"
         data-image="{{ asset($customer->image) }}">

        <img src="{{ asset($customer->image) }}"
             class="w-full h-64 object-cover">
    </div>
@else
    <p class="muted text-lg">No document uploaded</p>
@endif

        </div>

        <div class="mt-8 text-center md:text-right">
            <a href="{{ route('salesman.customers.index') }}" class="btn btn-outline">
                <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
                Back to Customers
            </a>
        </div>

    </div>
</div>

<div id="imagePreviewModal"
     class="fixed inset-0 bg-black/80 hidden items-center justify-center z-[9999] p-4">

    <div class="relative w-full max-w-3xl mx-auto">

        <button id="closePreview"
            class="absolute top-3 right-3 bg-black/60 text-white w-8 h-8 flex items-center justify-center
                   rounded-full hover:bg-black/80 text-xl md:text-2xl transition">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <img id="previewImage" src=""
             class="w-full max-h-[85vh] md:max-h-[90vh] object-contain rounded-2xl shadow-2xl">
    </div>
</div>

<script>
    const showBtn = document.getElementById('showImageBtn');
    const closeBtn = document.getElementById('closeImageBtn');
    const section = document.getElementById('imageDisplaySection');

    // SHOW DOCUMENT
    showBtn.addEventListener('click', () => {
        section.classList.remove('hidden');
        showBtn.classList.add('hidden');
        closeBtn.classList.remove('hidden');
    });

    // HIDE DOCUMENT
    closeBtn.addEventListener('click', () => {
        section.classList.add('hidden');
        showBtn.classList.remove('hidden');
        closeBtn.classList.add('hidden');
    });

    // FULLSCREEN PREVIEW
    const imageBox = document.getElementById("imageBox");

    if (imageBox) {
        imageBox.addEventListener("click", () => {
            document.getElementById("previewImage").src =
                imageBox.getAttribute("data-image");

            const modal = document.getElementById("imagePreviewModal");
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        });
    }

    // CLOSE MODAL
    document.getElementById("closePreview").addEventListener("click", () => {
        const modal = document.getElementById("imagePreviewModal");
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    });

    // CLICK OUTSIDE TO CLOSE
    document.getElementById("imagePreviewModal").addEventListener("click", (e) => {
        if (e.target.id === "imagePreviewModal") {
            const modal = document.getElementById("imagePreviewModal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }
    });
</script>

@endsection