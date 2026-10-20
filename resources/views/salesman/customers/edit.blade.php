@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')

<div class="max-w-2xl mx-auto mt-10">
    <div class="card !rounded-2xl shadow-card-lg p-8">

    <div class="page-head !mb-6">
        <h2 class="page-title">
            <span class="page-ico"><i data-lucide="user-cog" class="w-6 h-6"></i></span>
            Edit Customer
        </h2>
    </div>

    {{-- Form --}}
    <form action="{{ route('salesman.customers.update', $customer->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-5">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Customer Name --}}
            <div>
                <label class="label">Customer Name</label>
                <input name="name" type="text"
                       value="{{ old('name', $customer->name) }}"
                       class="input w-full"
                       required>
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Contact Person --}}
            <div>
                <label class="label">Contact Person</label>
                <input name="contact_person" type="text"
                       value="{{ old('contact_person', $customer->contact_person) }}"
                       class="input w-full"
                       required>
                @error('contact_person') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Phone 1 --}}
            <div>
                <label class="label">Mobile 1</label>
                <div class="flex items-center input !p-0 overflow-hidden">
                    <span class="px-4 py-3 text-[var(--text-soft)] bg-[var(--surface-3)] border-r border-[var(--border)]">+92-</span>
                    <input type="text" id="phone1_local"
                           value="{{ old('phone1', str_replace('+92-', '', $customer->phone1)) }}"
                           maxlength="10"
                           inputmode="numeric"
                           class="w-full px-4 py-3 bg-transparent text-[var(--text)] outline-none">
                </div>
                <input type="hidden" name="phone1" id="phone1">
            </div>

            {{-- Phone 2 --}}
            <div>
                <label class="label">Mobile 2</label>
                <div class="flex items-center input !p-0 overflow-hidden">
                    <span class="px-4 py-3 text-[var(--text-soft)] bg-[var(--surface-3)] border-r border-[var(--border)]">+92-</span>
                    <input type="text" id="phone2_local"
                           value="{{ old('phone2', str_replace('+92-', '', $customer->phone2)) }}"
                           maxlength="10"
                           inputmode="numeric"
                           class="w-full px-4 py-3 bg-transparent text-[var(--text)] outline-none">
                </div>
                <input type="hidden" name="phone2" id="phone2">
            </div>

            {{-- Email --}}
            <div>
                <label class="label">Email</label>
                <input name="email" type="email"
                       value="{{ old('email', $customer->email) }}"
                       class="input w-full">
            </div>

            {{-- Address --}}
            <div class="md:col-span-2">
                <label class="label">Address</label>
                <input name="address" type="text"
                       value="{{ old('address', $customer->address) }}"
                       class="input w-full"
                       required>
            </div>

            {{-- Industry --}}
            <div>
                <label class="label">Industry</label>
                <select name="industry_id" class="select w-full" required>
                    @foreach($industries as $industry)
                        <option value="{{ $industry->id }}"
                            {{ old('industry_id', $customer->industry_id) == $industry->id ? 'selected' : '' }}>
                            {{ $industry->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Category --}}
            <div>
                <label class="label">Category</label>
                <select name="category_id" class="select w-full" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $customer->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- City --}}
            <div>
                <label class="label">City</label>
                <select name="city_id" class="select w-full" required>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}"
                            {{ old('city_id', $customer->city_id) == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Image --}}
            <div>
                <label class="label">Customer Image</label>

               @if($customer->image)
    <img src="{{ asset($customer->image) }}"
         class="h-24 rounded-xl mb-3 border border-[var(--border)]">
@endif

                <input type="file" name="image" accept="image/*"
                       class="w-full text-[var(--text)] file:bg-[var(--accent-softer)] file:text-[var(--accent)] file:border-0
                              file:px-4 file:py-2 file:rounded-lg input rounded-lg py-2 px-3">
                <p class="text-xs text-[var(--text-muted)] mt-1">Leave empty to keep existing image</p>
            </div>

        </div>

        {{-- Actions --}}
        <div class="flex gap-3 pt-4">
            <a href="{{ route('salesman.customers.index') }}"
               class="btn btn-ghost flex-1">
                Cancel
            </a>

            <button type="submit" class="btn btn-primary flex-1">
                Update Customer
            </button>
        </div>

    </form>
</div>
</div>

@endsection

{{-- Phone JS --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    function setupPhone(localId, hiddenId) {
        const local = document.getElementById(localId);
        const hidden = document.getElementById(hiddenId);

        function sync() {
            local.value = local.value.replace(/\D/g, "");
            hidden.value = local.value ? "+92-" + local.value : "";
        }

        local.addEventListener("input", sync);
        sync();
    }

    setupPhone("phone1_local", "phone1");
    setupPhone("phone2_local", "phone2");

});
</script>