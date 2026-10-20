@extends('layouts.app')
@section('title', 'Add Customer')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="card !rounded-2xl shadow-card-lg p-8">

    <div class="page-head !mb-6">
        <h2 class="page-title">
            <span class="page-ico"><i data-lucide="user-plus" class="w-6 h-6"></i></span>
            Add Customer
        </h2>
    </div>

    <form action="{{ route('salesman.customers.store') }}" method="POST"
      enctype="multipart/form-data" class="space-y-5">
    @csrf

    @if($errors->has('duplicate'))
    <div class="mb-4 p-3 alert alert-error rounded-lg">
        {{ $errors->first('duplicate') }}
    </div>
@endif
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="label flex items-center">
                <i data-lucide="building" class="w-4 h-4 mr-1 text-[var(--text-soft)]"></i>
                Customer Name
            </label>
            <input name="name" type="text"
                placeholder="Enter customer name"
                class="input w-full"
                required value="{{ old('name') }}">
        </div>

        <div>
            <label class="label flex items-center">
                <i data-lucide="user-round" class="w-4 h-4 mr-1 text-[var(--text-soft)]"></i>
                Contact Person
            </label>
            <input name="contact_person" type="text"
                placeholder="Enter contact person name"
                class="input w-full"
                required value="{{ old('contact_person') }}">
        </div>

    <div>
    <label class="label flex items-center">
        <i data-lucide="phone" class="w-4 h-4 mr-1 text-[var(--text-soft)]"></i>
        Mobile 1
    </label>

    <div class="flex items-center input !p-0 overflow-hidden">
        <span class="px-4 py-3 text-[var(--text-soft)] bg-[var(--surface-3)] border-r border-[var(--border)]">
            +92-
        </span>

        <input type="text"
               id="phone1_local"
               maxlength="10"
               inputmode="numeric"
               placeholder="3001234567"
               class="w-full px-4 py-3 bg-transparent text-[var(--text)] placeholder-[var(--text-muted)]
                      outline-none"
               value="{{ old('phone1') ? preg_replace('/^\+92-/', '', old('phone1')) : '' }}">
    </div>

    <input type="hidden" name="phone1" id="phone1" value="{{ old('phone1') }}">
</div>

    <div>
    <label class="label flex items-center">
        <i data-lucide="phone" class="w-4 h-4 mr-1 text-[var(--text-soft)]"></i>
        Mobile 2
    </label>

    <div class="flex items-center input !p-0 overflow-hidden">
        <span class="px-4 py-3 text-[var(--text-soft)] bg-[var(--surface-3)] border-r border-[var(--border)]">
            +92-
        </span>

        <input type="text"
               id="phone2_local"
               maxlength="10"
               inputmode="numeric"
               placeholder="3001234567 (optional)"
               class="w-full px-4 py-3 bg-transparent text-[var(--text)] placeholder-[var(--text-muted)]
                      outline-none"
               value="{{ old('phone2') ? preg_replace('/^\+92-/', '', old('phone2')) : '' }}">
    </div>

    <input type="hidden" name="phone2" id="phone2" value="{{ old('phone2') }}">
</div>


        <div>
            <label class="label flex items-center">
                <i data-lucide="mail" class="w-4 h-4 mr-1 text-[var(--text-soft)]"></i>
                Email
            </label>
            <input name="email" type="email"
                placeholder="Enter email address"
                class="input w-full"
                value="{{ old('email') }}">
        </div>

        <div class="md:col-span-2">
            <label class="label flex items-center">
                <i data-lucide="map-pin" class="w-4 h-4 mr-1 text-[var(--text-soft)]"></i>
                Address
            </label>
            <input name="address" type="text"
                placeholder="Enter full address"
                class="input w-full"
                required value="{{ old('address') }}">
        </div>



        <div>
            <label class="label flex items-center">
                <i data-lucide="briefcase" class="w-4 h-4 mr-1 text-[var(--text-soft)]"></i>
                Industry
            </label>
            <select name="industry_id" class="select w-full" required>
    <option value="">Select industry</option>
    @foreach($industries as $industry)
        <option value="{{ $industry->id }}"
            {{ old('industry_id') == $industry->id ? 'selected' : '' }}>
            {{ $industry->name }}
        </option>
    @endforeach
</select>
        </div>

        <div>
    <label class="label flex items-center">
        <i data-lucide="tag" class="w-4 h-4 mr-1 text-[var(--text-soft)]"></i>
        Category
    </label>

    <select name="category_id" class="select w-full" required>
        <option value="">Select category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>

       <div>
    <label class="label flex items-center">
        <i data-lucide="map" class="w-4 h-4 mr-1 text-[var(--text-soft)]"></i>
        City
    </label>

    <select name="city_id" class="select w-full" required>
        <option value="">Select city</option>
        @foreach($cities as $city)
            <option value="{{ $city->id }}"
                {{ old('city_id') == $city->id ? 'selected' : '' }}>
                {{ $city->name }}
            </option>
        @endforeach
    </select>
</div>

        <div>
            <label class="label flex items-center">
                <i data-lucide="image" class="w-4 h-4 mr-1 text-[var(--text-soft)]"></i>
                Customer Image
            </label>
            <input type="file" name="image" accept="image/*"
                class="w-full text-[var(--text)] file:bg-[var(--accent-softer)] file:text-[var(--accent)] file:border-0
                file:px-4 file:py-2 file:rounded-lg input rounded-lg py-2 px-3"
                required>
        </div>

    </div>

    <button class="btn btn-primary w-full !py-3">
        <i data-lucide="save" class="w-5 h-5 mr-2"></i>
        Save Customer
    </button>

    </form>

</div>
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