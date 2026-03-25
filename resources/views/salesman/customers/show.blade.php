@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 px-0">

    <div class="bg-white/10 backdrop-blur-xl border border-white/20
                 rounded-3xl shadow-2xl p-6 md:p-8">

        <h2 class="text-2xl md:text-3xl font-bold text-white mb-6 tracking-wide flex items-center">
            {{-- Lucide Icon: clipboard-list --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list mr-3 text-[#ff2ba6]">
                <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                <path d="M10 11h4"/>
                <path d="M10 15h4"/>
                <path d="M10 19h4"/>
            </svg>
            Customer Details
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">

            <div>
                {{-- Customer Name with Icon --}}
                <h3 class="text-xl md:text-2xl font-semibold text-white flex items-center">
                    {{-- Lucide Icon: building --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building mr-2 text-white/80">
                        <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                        <path d="M9 22v-4h6v4"/>
                        <path d="M8 6h.01"/>
                        <path d="M16 6h.01"/>
                        <path d="M12 6h.01"/>
                        <path d="M12 10h.01"/>
                        <path d="M12 14h.01"/>
                        <path d="M16 10h.01"/>
                        <path d="M16 14h.01"/>
                        <path d="M8 10h.01"/>
                        <path d="M8 14h.01"/>
                    </svg>
                    {{ $customer->name }}
                </h3>

                <p class="text-indigo-200 font-medium mb-4 capitalize ml-8">
                    {{ $customer->category->name ?? 'No Category' }}
                </p>

                <div class="space-y-3">

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl items-center">
                        <span class="text-white/70 flex items-center">
                            {{-- Lucide Icon: user-round --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round mr-2">
                                <circle cx="12" cy="8" r="5"/>
                                <path d="M20 21a8 8 0 0 0-16 0"/>
                            </svg>
                            Contact Person
                        </span>
                        <span class="text-white">{{ $customer->contact_person ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl items-center">
                        <span class="text-white/70 flex items-center">
                            {{-- Lucide Icon: phone --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone mr-2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            Mobile 1
                        </span>
                        <span class="text-white">{{ $customer->phone1 ?? 'N/A'}}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl items-center">
                        <span class="text-white/70 flex items-center">
                            {{-- Lucide Icon: phone-incoming (or just phone again) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call mr-2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                <path d="M14.5 20.5l-3 3.5"/>
                            </svg>
                            Mobile 2
                        </span>
                        <span class="text-white">{{ $customer->phone2 ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl items-center">
                        <span class="text-white/70 flex items-center">
                            {{-- Lucide Icon: mail --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail mr-2">
                                <rect width="20" height="16" x="2" y="4" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                            Email
                        </span>
                        <span class="text-white">{{ $customer->email ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl items-center">
                        <span class="text-white/70 flex items-center">
                            {{-- Lucide Icon: map --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin mr-2">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            City
                        </span>
                        <span class="text-white capitalize">{{ $customer->city?->name ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl items-center">
                        <span class="text-white/70 flex items-center">
                            {{-- Lucide Icon: briefcase --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase mr-2">
                                <rect width="20" height="14" x="2" y="7" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>
                            Industry
                        </span>
                        <span class="text-white">{{ $customer->industry?->name ?? 'N/A' }}</span>
                    </div>
                     <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl items-center">
                        <span class="text-white/70 flex items-center">
                            {{-- Lucide Icon: briefcase --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase mr-2">
                                <rect width="20" height="14" x="2" y="7" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>
                            Category
                        </span>
                        <span class="text-white">{{ $customer->category?->name ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl items-start">
                        <span class="text-white/70 flex items-center mt-0.5">
                            {{-- Lucide Icon: map-pin (for address) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin mr-2 flex-shrink-0">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            Address
                        </span>
                        <span class="text-white text-right break-words max-w-[60%]">{{ $customer->address ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl items-center">
                        <span class="text-white/70 flex items-center">
                            {{-- Lucide Icon: calendar-check --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-check mr-2">
                                <path d="M21 14V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8"/>
                                <line x1="16" x2="16" y1="2" y2="6"/>
                                <line x1="8" x2="8" y1="2" y2="6"/>
                                <line x1="3" x2="21" y1="10" y2="10"/>
                                <path d="m15 19 2 2 4-4"/>
                            </svg>
                            Added On
                        </span>
                        <span class="text-white">{{ $customer->created_at->format('d M, Y') }}</span>
                    </div>

                </div>

                <div class="mt-6 flex gap-4">
                    <button id="showImageBtn"
                        class="px-5 py-3 bg-[#ff2ba6]/80 hover:bg-[#ff2ba6] flex items-center
                               text-white font-semibold rounded-xl shadow-lg transition">
                        {{-- Lucide Icon: image --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image mr-2">
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                            <circle cx="9" cy="9" r="2"/>
                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                        </svg>
                        Preview Document
                    </button>

                    <button id="closeImageBtn"
                        class="px-5 py-3 bg-red-600/70 hover:bg-red-600 flex items-center
                               text-white font-semibold rounded-xl shadow-lg transition hidden">
                        {{-- Lucide Icon: x --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x mr-2">
                            <path d="M18 6 6 18"/>
                            <path d="m6 6 12 12"/>
                        </svg>
                        Close Preview
                    </button>
                </div>
            </div>
        </div>

        <div id="imageDisplaySection" class="mt-8 hidden">

            <h3 class="text-2xl font-semibold text-white mb-4 flex items-center">
                {{-- Lucide Icon: file-text --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text mr-2 text-white/70">
                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
                    <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
                    <path d="M10 9H8"/>
                    <path d="M16 13H8"/>
                    <path d="M16 17H8"/>
                </svg>
                Customer Document
            </h3>

         @if ($customer->image)
    <div class="rounded-xl overflow-hidden bg-white/10 border border-white/10 shadow-lg cursor-pointer"
         id="imageBox"
         data-image="{{ asset($customer->image) }}">

        <img src="{{ asset($customer->image) }}"
             class="w-full h-64 object-cover">
    </div>
@else
    <p class="text-white/60 text-lg">No document uploaded</p>
@endif



        </div>

        <div class="mt-8 text-center md:text-right">
            <a href="{{ route('salesman.customers.index') }}"
                class="inline-block px-6 py-3 bg-purple-600/80 hover:bg-purple-600
                       text-white font-semibold rounded-xl shadow-lg transition flex items-center justify-center md:inline-flex md:justify-end">
                {{-- Lucide Icon: arrow-left --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left mr-2">
                    <path d="m12 19-7-7 7-7"/>
                    <path d="M19 12H5"/>
                </svg>
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
            {{-- Lucide Icon: x inside the button --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                <path d="M18 6 6 18"/>
                <path d="m6 6 12 12"/>
            </svg>
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
