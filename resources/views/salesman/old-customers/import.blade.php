@extends('layouts.app')

@section('title','Import Old Customers')

@section('content')
<div class="max-w-2xl mx-auto mt-10">

    <div class="card !rounded-3xl shadow-card-lg p-8">

        <div class="page-head !mb-6">
            <h2 class="page-title">
                <span class="page-ico"><i data-lucide="upload" class="w-6 h-6"></i></span>
                Import Old Customers
            </h2>
        </div>

        <form action="{{ route('salesman.old-customers.import') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf

            <div>
                <label for="excel_file" class="label flex items-center gap-2">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4 text-[var(--accent)]"></i>
                    Upload Customer Data File (Excel: .xlsx, .xls, .csv)
                </label>

                <div class="relative">
                    <input type="file" name="file" id="excel_file" required
                           accept=".xlsx, .xls, .csv"
                           class="w-full text-sm text-[var(--text-soft)]
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-xl file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-[var(--accent)] file:text-white
                                  hover:file:bg-[var(--accent-strong)]
                                  input !py-3 rounded-xl w-full
                                  cursor-pointer outline-none">
                </div>
            </div>

            <div class="soft-panel p-4 rounded-xl border border-[var(--border)] text-sm">
                <p class="font-semibold mb-2 flex items-center gap-2 text-[var(--accent)]">
                    <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                    Required Excel Column Headings (must be in this order):
                </p>
                <code class="block p-2 soft-panel rounded-lg text-amber-600 dark:text-amber-400 overflow-x-auto text-xs border border-[var(--border)]">
                    Company Name | Contact Person | Address | Email | Mobile Number
                </code>
            </div>

            <a href="http://portal.sessigmasoft.com/uploads/old_customers_import_file.xlsx"
               download
               class="btn btn-outline w-full">
                <i data-lucide="download" class="w-5 h-5"></i>
                Download Import Template
            </a>

            <button type="submit" class="btn btn-primary w-full">
                <i data-lucide="cloud-upload" class="w-5 h-5"></i>
                Import Data
            </button>
        </form>

    </div>
</div>
@endsection