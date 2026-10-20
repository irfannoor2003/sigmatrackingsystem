@extends('layouts.app')

@section('title', 'All Customers')

@section('content')

    <div class="p-0">

        <div class="page-head !mb-6">
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

            <x-filter-actions :reset="route('salehead.customers.index')" />
        </x-filter-bar>

        <form action="{{ route('admin.customers.export.bulk') }}" method="POST">
            @csrf

            <div class="card !rounded-2xl overflow-hidden shadow-card-lg hidden md:block">
                <div class="tbl-wrap !border-0 !shadow-none">
                <table class="tbl w-full min-w-[600px]">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Industry</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $c)
                            <tr>
                                <td class="muted">{{ $c->id }}</td>
                                <td>
                                    <a href="{{ route('salehead.customers.show', $c->id) }}"
                                        class="text-[var(--accent)] hover:underline font-semibold">
                                        {{ $c->name }}
                                    </a>
                                </td>
                                <td class="text-[var(--text-soft)]">{{ $c->phone1 }}</td>
                                <td class="text-[var(--text-soft)]">{{ $c->city->name ?? '-' }}</td>
                                <td class="text-[var(--text-soft)]">{{ $c->industry->name ?? '-' }}</td>
                                <td class="text-[var(--text-soft)]">{{ $c->category->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center muted">No Customers Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>

        </form>

        <div class="md:hidden p-0 space-y-4 mt-4">

            @forelse ($customers as $c)
                <div class="card !rounded-2xl p-4">

                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-semibold text-[var(--text)]">
                            <i data-lucide="user" class="w-4 h-4 inline mr-1 text-[var(--text-soft)]"></i>{{ $c->name }}
                        </h2>
                        <input type="checkbox" name="selected_customers[]" value="{{ $c->id }}"
                            class="checkbox customer-checkbox">
                    </div>

                    <p class="text-[var(--text-soft)] text-sm mb-1">
                        <span class="text-[var(--text)] font-semibold">
                            <i data-lucide="phone" class="w-4 h-4 inline mr-1 text-[var(--text-soft)]"></i>Phone:
                        </span> {{ $c->phone1 ?? '—' }}
                    </p>

                    <p class="text-[var(--text-soft)] text-sm mb-1">
                        <span class="text-[var(--text)] font-semibold">
                            <i data-lucide="map-pin" class="w-4 h-4 inline mr-1 text-[var(--text-soft)]"></i>City:
                        </span> {{ $c->city->name ?? '—' }}
                    </p>

                    <p class="text-[var(--text-soft)] text-sm mb-1">
                        <span class="text-[var(--text)] font-semibold">
                            <i data-lucide="factory" class="w-4 h-4 inline mr-1 text-[var(--text-soft)]"></i>Industry:
                        </span> {{ $c->industry->name ?? '—' }}
                    </p>

                    <p class="text-[var(--text-soft)] text-sm mb-3">
                        <span class="text-[var(--text)] font-semibold">
                            <i data-lucide="tag" class="w-4 h-4 inline mr-1 text-[var(--text-soft)]"></i>Category:
                        </span> {{ $c->category->name ?? '—' }}
                    </p>

                    <a href="{{ route('salehead.customers.show', $c->id) }}" class="btn btn-soft btn-sm block w-full">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        View Details
                    </a>

                </div>
            @empty
                <p class="text-center muted">No customers found</p>
            @endforelse

        </div>

        <div class="mt-5">
            {{ $customers->links() }}
        </div>

    </div>

@endsection