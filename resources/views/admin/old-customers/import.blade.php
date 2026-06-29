@extends('layouts.app')

@section('title','Import Old Customers')

@section('content')
<div class="max-w-2xl mx-auto mt-10">

    <div class="bg-white/10 backdrop-blur-xl border border-white/20
                 rounded-3xl shadow-2xl p-8 text-white">

        <h2 class="text-2xl font-bold mb-6 tracking-wide flex items-center gap-2">
            <i data-lucide="upload" class="w-6 h-6 text-[#ff2ba6]"></i>
            Import Old Customers
        </h2>

        @if(session('success'))
            <div class="mb-4 p-3 rounded-xl bg-green-500/20 text-green-300 flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.old-customers.import') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf

            <div>
                <label for="salesman_id" class="block mb-2 text-sm font-medium flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 text-[#ff2ba6]"></i>
                    Assign to Salesman
                </label>
                <select name="salesman_id" id="salesman_id" required
                    class="w-full text-sm text-white/80
                           bg-white/10 border border-white/30 rounded-xl p-3
                           focus:outline-none focus:ring-2 focus:ring-[#ff2ba6]/50">
                    <option value="" class="text-black">-- Select Salesman --</option>
                    @foreach($salesmen as $salesman)
                        <option value="{{ $salesman->id }}" {{ old('salesman_id') == $salesman->id ? 'selected' : '' }}
                            class="text-black">
                            {{ $salesman->name }}
                        </option>
                    @endforeach
                </select>
                @error('salesman_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="excel_file" class="block mb-2 text-sm font-medium flex items-center gap-2">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4 text-[#ff2ba6]"></i>
                    Upload Customer Data File (Excel: .xlsx, .xls, .csv)
                </label>

                <div class="relative">
                    <input type="file" name="file" id="excel_file" required
                           accept=".xlsx, .xls, .csv"
                           class="w-full text-sm text-white/80
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-xl file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-[#ff2ba6] file:text-white
                                  hover:file:bg-pink-600
                                  bg-white/10 border border-white/30 rounded-xl p-3
                                  cursor-pointer focus:outline-none">
                </div>
                @error('file')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="p-4 rounded-xl bg-white/5 border border-white/10 text-sm">
                <p class="font-semibold mb-2 flex items-center gap-2 text-[#ff2ba6]">
                    <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                    Required Excel Column Headings (must be in this order):
                </p>
                <code class="block p-2 bg-white/10 rounded-lg text-yellow-300 overflow-x-auto text-xs">
                    Company Name | Contact Person | Address | Email | Mobile Number
                </code>
            </div>

            <a href="http://portal.sessigmasoft.com/uploads/old_customers_import_file.xlsx"
               download
               class="w-full py-3 rounded-xl border border-white/20 bg-white/5
                      font-semibold hover:bg-white/10 transition
                      flex items-center justify-center gap-2">
                <i data-lucide="download" class="w-5 h-5"></i>
                Download Import Template
            </a>

            <button type="submit"
                class="w-full py-3 rounded-xl
                       bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6]
                       font-semibold shadow-lg hover:opacity-90
                       flex items-center justify-center gap-2">
                <i data-lucide="cloud-upload" class="w-5 h-5"></i>
                Import Data
            </button>
        </form>

    </div>
</div>
@endsection
