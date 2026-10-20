@extends('layouts.app')

@section('title','Import Old Customers')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="upload" class="w-6 h-6"></i></span>
            Import Old Customers
        </h1>
    </div>

    <div class="card !rounded-2xl p-8">


        <form action="{{ route('admin.old-customers.import') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf

            <div>
                <label for="salesman_id" class="label flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 text-[var(--accent)]"></i>
                    Assign to Salesman
                </label>
                <select name="salesman_id" id="salesman_id" required class="select">
                    <option value="">-- Select Salesman --</option>
                    @foreach($salesmen as $salesman)
                        <option value="{{ $salesman->id }}" {{ old('salesman_id') == $salesman->id ? 'selected' : '' }}>
                            {{ $salesman->name }}
                        </option>
                    @endforeach
                </select>
                @error('salesman_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="excel_file" class="label flex items-center gap-2">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4 text-[var(--accent)]"></i>
                    Upload Customer Data File (Excel: .xlsx, .xls, .csv)
                </label>

                <input type="file" name="file" id="excel_file" required
                       accept=".xlsx, .xls, .csv"
                       class="w-full text-sm text-[var(--text-muted)]
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-xl file:border-0
                              file:text-sm file:font-semibold
                              file:bg-[var(--accent)] file:text-white
                              hover:file:brightness-110
                              soft-panel p-3
                              cursor-pointer">
                @error('file')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="soft-panel p-4 rounded-xl text-sm">
                <p class="font-semibold mb-2 flex items-center gap-2 text-[var(--accent)]">
                    <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                    Required Excel Column Headings (must be in this order):
                </p>
                <code class="block p-2 rounded-lg bg-[var(--surface-3)] text-amber-600 dark:text-amber-300 overflow-x-auto text-xs">
                    Company Name | Contact Person | Address | Email | Mobile Number
                </code>
            </div>

            <a href="http://portal.sessigmasoft.com/uploads/old_customers_import_file.xlsx"
               download
               class="btn btn-outline w-full">
                <i data-lucide="download" class="w-4 h-4"></i>
                Download Import Template
            </a>

            <button type="submit" class="btn btn-primary w-full">
                <i data-lucide="cloud-upload" class="w-4 h-4"></i>
                Import Data
            </button>
        </form>

    </div>
</div>
@endsection