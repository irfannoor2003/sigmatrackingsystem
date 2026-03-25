@extends('layouts.app')
@section('title', 'Add Customer')

@section('content')

<div class="max-w-2xl mx-auto bg-white/10 backdrop-blur-xl border border-white/20
           p-8 rounded-2xl shadow-xl">

    {{-- Form Title with Icon --}}
    <h2 class="text-2xl font-bold text-white mb-6 tracking-wide flex items-center">
        {{-- Lucide Icon: user-plus --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-plus mr-3 text-[#ff2ba6]">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <line x1="19" x2="19" y1="8" y2="14"/>
            <line x1="16" x2="22" y1="11" y2="11"/>
        </svg>
        Add Customer
    </h2>

    <form action="{{ route('salesman.customers.store') }}" method="POST"
      enctype="multipart/form-data" class="space-y-5">
    @csrf

    @if($errors->has('duplicate'))
    <div class="mb-4 p-3 bg-red-700/30 text-red-100 rounded-lg border border-red-400">
        {{ $errors->first('duplicate') }}
    </div>
@endif
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block text-sm text-white/80 mb-1 flex items-center">
                {{-- Lucide Icon: building --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building mr-1 text-white/50">
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
                Customer Name
            </label>
            <input name="name" type="text"
                placeholder="Enter customer name"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                placeholder-white/50 focus:bg-white/20 outline-none"
                required value="{{ old('name') }}">
        </div>

        <div>
            <label class="block text-sm text-white/80 mb-1 flex items-center">
                {{-- Lucide Icon: user-round --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round mr-1 text-white/50">
                    <circle cx="12" cy="8" r="5"/>
                    <path d="M20 21a8 8 0 0 0-16 0"/>
                </svg>
                Contact Person
            </label>
            <input name="contact_person" type="text"
                placeholder="Enter contact person name"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                placeholder-white/50 focus:bg-white/20 outline-none"
                required value="{{ old('contact_person') }}">
        </div>

    <div>
    <label class="block text-sm text-white/80 mb-1 flex items-center">
        {{-- Lucide Icon: phone --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone mr-1 text-white/50">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
        </svg>
        Mobile 1
    </label>

    <div class="flex items-center bg-white/10 backdrop-blur-xl border border-white/20
                 rounded-lg overflow-hidden">
        <span class="px-4 py-3 text-white bg-white/5 border-r border-white/10">
            +92-
        </span>

        <input type="text"
               id="phone1_local"
               maxlength="10"
               inputmode="numeric"
               placeholder="3001234567"
               class="w-full px-4 py-3 bg-transparent text-white placeholder-white/40
                      outline-none"
               value="{{ old('phone1') ? preg_replace('/^\+92-/', '', old('phone1')) : '' }}">
    </div>

    <input type="hidden" name="phone1" id="phone1" value="{{ old('phone1') }}">
</div>

    <div>
    <label class="block text-sm text-white/80 mb-1 flex items-center">
        {{-- Lucide Icon: phone --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone mr-1 text-white/50">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
        </svg>
        Mobile 2
    </label>

    <div class="flex items-center bg-white/10 backdrop-blur-xl border border-white/20
                 rounded-lg overflow-hidden">
        <span class="px-4 py-3 text-white bg-white/5 border-r border-white/10">
            +92-
        </span>

        <input type="text"
               id="phone2_local"
               maxlength="10"
               inputmode="numeric"
               placeholder="3001234567 (optional)"
               class="w-full px-4 py-3 bg-transparent text-white placeholder-white/40
                      outline-none"
               value="{{ old('phone2') ? preg_replace('/^\+92-/', '', old('phone2')) : '' }}">
    </div>

    <input type="hidden" name="phone2" id="phone2" value="{{ old('phone2') }}">
</div>


        <div>
            <label class="block text-sm text-white/80 mb-1 flex items-center">
                {{-- Lucide Icon: mail --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail mr-1 text-white/50">
                    <rect width="20" height="16" x="2" y="4" rx="2"/>
                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                </svg>
                Email
            </label>
            <input name="email" type="email"
                placeholder="Enter email address"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                placeholder-white/50 focus:bg-white/20 outline-none"
                value="{{ old('email') }}">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm text-white/80 mb-1 flex items-center">
                {{-- Lucide Icon: map-pin --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin mr-1 text-white/50">
                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                Address
            </label>
            <input name="address" type="text"
                placeholder="Enter full address"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                placeholder-white/50 focus:bg-white/20 outline-none"
                required value="{{ old('address') }}">
        </div>



        <div>
            <label class="block text-sm text-white/80 mb-1 flex items-center">
                {{-- Lucide Icon: briefcase --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase mr-1 text-white/50">
                    <rect width="20" height="14" x="2" y="7" rx="2" ry="2"/>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                </svg>
                Industry
            </label>
            <select name="industry_id"
        class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
        focus:bg-white/20 outline-none"
        required>
    <option value="" class="text-black">Select industry</option>
    @foreach($industries as $industry)
        <option value="{{ $industry->id }}"
            class="text-black"
            {{ old('industry_id') == $industry->id ? 'selected' : '' }}>
            {{ $industry->name }}
        </option>
    @endforeach
</select>
        </div>

        <div>
    <label class="block text-sm text-white/80 mb-1 flex items-center">
        {{-- Lucide Icon: tag --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tag mr-1 text-white/50">
            <path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/>
            <path d="M7 7h.01"/>
        </svg>
        Category
    </label>

    <select name="category_id"
            class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
            focus:bg-white/20 outline-none"
            required>
        <option value="" class="text-black">Select category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                class="text-black"
                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>

       <div>
    <label class="block text-sm text-white/80 mb-1 flex items-center">
        {{-- Lucide Icon: map --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map mr-1 text-white/50">
            <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/>
            <line x1="9" x2="9" y1="3" y2="18"/>
            <line x1="15" x2="15" y1="6" y2="21"/>
        </svg>
        City
    </label>

    <select name="city_id"
            class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
            focus:bg-white/20 outline-none"
            required>
        <option value="" class="text-black">Select city</option>
        @foreach($cities as $city)
            <option value="{{ $city->id }}"
                class="text-black"
                {{ old('city_id') == $city->id ? 'selected' : '' }}>
                {{ $city->name }}
            </option>
        @endforeach
    </select>
</div>

        <div>
            <label class="block text-sm text-white/80 mb-1 flex items-center">
                {{-- Lucide Icon: image --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image mr-1 text-white/50">
                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                    <circle cx="9" cy="9" r="2"/>
                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                </svg>
                Customer Image
            </label>
            <input type="file" name="image" accept="image/*"
                class="w-full text-white file:bg-white/20 file:text-white file:border-0
                file:px-4 file:py-2 file:rounded-lg bg-white/10 rounded-lg py-2 px-3
                focus:bg-white/20"
                required>
        </div>

    </div>

    <button
        class="w-full py-3 rounded-xl text-white font-semibold tracking-wide flex items-center justify-center
               bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] shadow-lg
               hover:opacity-90 transition">
        {{-- Lucide Icon: save --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save mr-2">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/>
            <polyline points="7 3 7 8 15 8"/>
        </svg>
        Save Customer
    </button>

    </form>

</div>

@endsection

<script>
document.addEventListener("DOMContentLoaded", function () {

    function setupPhone(localId, hiddenId) {
        const local = document.getElementById(localId);
        const hidden = document.getElementById(hiddenId);

        // Update hidden field when typing
        local.addEventListener("input", function () {
            // allow only digits
            this.value = this.value.replace(/\D/g, "");

            // update hidden field
            if (this.value.length > 0) {
                hidden.value = "+92-" + this.value;
            } else {
                hidden.value = "";
            }
        });

        // Force update hidden before form submit
        const form = local.closest("form");
        form.addEventListener("submit", function () {
            local.value = local.value.replace(/\D/g, "");

            hidden.value = local.value.length > 0
                ? "+92-" + local.value
                : "";
        });
    }

    setupPhone("phone1_local", "phone1");
    setupPhone("phone2_local", "phone2");

});
</script>
