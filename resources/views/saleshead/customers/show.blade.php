@extends('layouts.app')

@section('title', 'Customer Details: ' . $customer->name)

@section('content')
    <div class="max-w-4xl mx-auto mt-10 px-0">

        <div class="glass p-6 md:p-8 rounded-3xl border border-white/20 shadow-2xl">

            <h2 class="text-2xl md:text-3xl font-bold text-white mb-6 tracking-wide flex items-center gap-3">
                <i data-lucide="briefcase" class="w-7 h-7 text-[var(--hf-magenta-light)]"></i>
                Customer Details
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                <div>
                    <h3 class="text-xl md:text-2xl font-semibold text-white flex items-center gap-2 mb-2">
                        <i data-lucide="building-2" class="w-6 h-6 text-white/80"></i>
                        {{ $customer->name }}
                    </h3>

                    <p class="text-indigo-200 font-medium mb-4 capitalize flex items-center gap-2">
                        <i data-lucide="tag" class="w-4 h-4 text-indigo-200"></i>
                        {{ $customer->category->name ?? 'No Category' }}
                    </p>

                    <div class="space-y-3">

                        <div class="flex justify-between items-center bg-white/10 py-3 px-4 rounded-xl">
                            <span class="text-white/70 flex items-center gap-2"><i data-lucide="user" class="w-4 h-4"></i>
                                Contact Person</span>
                            <span class="text-white">{{ $customer->contact_person ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between items-center bg-white/10 py-3 px-4 rounded-xl">
                            <span class="text-white/70 flex items-center gap-2"><i data-lucide="phone" class="w-4 h-4"></i>
                                Phone 1</span>
                            <span class="text-white">{{ $customer->phone1 }}</span>
                        </div>

                        <div class="flex justify-between items-center bg-white/10 py-3 px-4 rounded-xl">
                            <span class="text-white/70 flex items-center gap-2"><i data-lucide="phone" class="w-4 h-4"></i>
                                Phone 2</span>
                            <span class="text-white">{{ $customer->phone2 ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between items-center bg-white/10 py-3 px-4 rounded-xl">
                            <span class="text-white/70 flex items-center gap-2"><i data-lucide="mail" class="w-4 h-4"></i>
                                Email</span>
                            <span class="text-white">{{ $customer->email ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between items-center bg-white/10 py-3 px-4 rounded-xl">
                            <span class="text-white/70 flex items-center gap-2"><i data-lucide="map-pin"
                                    class="w-4 h-4"></i> City</span>
                            <span class="text-white capitalize">{{ $customer->city?->name ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between items-center bg-white/10 py-3 px-4 rounded-xl">
                            <span class="text-white/70 flex items-center gap-2"><i data-lucide="factory"
                                    class="w-4 h-4"></i> Industry</span>
                            <span class="text-white">{{ $customer->industry?->name ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between items-start bg-white/10 py-3 px-4 rounded-xl">
                            <span class="text-white/70 flex items-center gap-2 whitespace-nowrap">
                                <i data-lucide="home" class="w-4 h-4"></i> Address
                            </span>

                            <div class="max-w-[60%] text-right">
                                <p id="address-{{ $customer->id }}"
                                    class="text-white text-sm leading-relaxed line-clamp-2 break-words">
                                    {{ $customer->address ?? 'N/A' }}
                                </p>


                            </div>
                        </div>


                        <div class="flex justify-between items-center bg-white/10 py-3 px-4 rounded-xl">
                            <span class="text-white/70 flex items-center gap-2"><i data-lucide="calendar"
                                    class="w-4 h-4"></i> Added On</span>
                            <span class="text-white">{{ $customer->created_at->format('d M, Y') }}</span>
                        </div>

                    </div>

                    <div class="mt-6 flex gap-4">
                        <button id="showImageBtn"
                            class="flex items-center gap-2 px-5 py-3 bg-[#ff2ba6]/80 hover:bg-[#ff2ba6] text-white font-semibold rounded-xl shadow-lg transition">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                            Preview Document
                        </button>

                        <button id="closeImageBtn"
                            class="flex items-center gap-2 px-5 py-3 bg-red-600/70 hover:bg-red-600 text-white font-semibold rounded-xl shadow-lg transition hidden">
                            <i data-lucide="x" class="w-5 h-5"></i>
                            Close Preview
                        </button>
                    </div>

                </div>

            </div>

            <div id="imageDisplaySection" class="mt-8 hidden">

                <h3 class="text-2xl font-semibold text-white mb-4 flex items-center gap-2">
                    <i data-lucide="receipt" class="w-6 h-6 text-white/70"></i>
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
                <a href="{{ route('salehead.customers.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600/80 hover:bg-purple-600 text-white font-semibold rounded-xl shadow-lg transition">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    Back to Customers
                </a>
            </div>

        </div>
    </div>

    <!-- ===========================
         FULL SCREEN IMAGE MODAL
    =========================== -->
    <div id="imagePreviewModal"
        class="fixed inset-0 hidden items-center justify-center z-[9999] bg-black/70 backdrop-blur-xl p-6 transition">

        <div
            class="relative w-full max-w-4xl mx-auto bg-white/10 border border-white/20 backdrop-blur-2xl
                rounded-3xl shadow-2xl p-4 md:p-6 animate-fadeIn">

            <!-- Close Button -->
            <button id="closePreview"
                class="absolute top-4 right-4 bg-black/40 hover:bg-black/60 text-white p-3 rounded-full transition shadow-lg">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>

            <!-- Full-screen Image -->
            <img id="previewImage" src="" class="w-full max-h-[80vh] object-contain rounded-2xl shadow-xl">
        </div>
    </div>


    <!-- Fade Animation -->
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.25s ease-out;
        }
    </style>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const showBtn = document.getElementById('showImageBtn');
            const closeBtn = document.getElementById('closeImageBtn');
            const section = document.getElementById('imageDisplaySection');
            const imageBox = document.getElementById("imageBox");
            const modal = document.getElementById("imagePreviewModal");
            const closePreviewBtn = document.getElementById("closePreview");
            const previewImage = document.getElementById("previewImage");

            // Show image section
            showBtn.addEventListener('click', () => {
                section.classList.remove('hidden');
                showBtn.classList.add('hidden');
                closeBtn.classList.remove('hidden');
            });

            closeBtn.addEventListener('click', () => {
                section.classList.add('hidden');
                showBtn.classList.remove('hidden');
                closeBtn.classList.add('hidden');
            });

            // Open fullscreen modal
            if (imageBox) {
                imageBox.addEventListener("click", () => {
                    previewImage.src = imageBox.getAttribute("data-image");
                    modal.classList.remove("hidden");
                    modal.classList.add("flex");
                });
            }

            // Close modal
            closePreviewBtn.addEventListener("click", () => {
                modal.classList.add("hidden");
                modal.classList.remove("flex");
            });

            // Click outside to close
            modal.addEventListener("click", (e) => {
                if (e.target === modal) {
                    modal.classList.add("hidden");
                    modal.classList.remove("flex");
                }
            });
        });
    </script>

@endsection
