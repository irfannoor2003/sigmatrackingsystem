@extends('layouts.app')

@section('title','Salesman Dashboard')

@section('content')

@include('partials.late-reason-modal', ['lateRecord' => $pendingLateRecord ?? null])

<div class="space-y-6" style="--brand: #eb268f">
    <div class="page-head">
        <div>
            <h1 class="page-title">
                <span class="page-ico"><i data-lucide="layout-dashboard" class="w-6 h-6"></i></span>
                Salesman Dashboard
            </h1>
            <p class="mt-2 text-sm muted">Track your visits and customer additions for this month.</p>
        </div>
        <span class="badge badge-gray !px-3 !py-1.5">
            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
            {{ now()->format('d M Y') }}
        </span>
    </div>

    @if($isNonWorkingDay)
        <div class="alert alert-info">
            <i data-lucide="calendar-off" class="w-5 h-5 shrink-0 mt-px"></i>
            <span>Today is a non-working day: <strong>{{ $nonWorkingReason }}</strong></span>
        </div>
    @endif

    <div class="card p-6 sm:p-7 flex items-center gap-4">
        <div class="w-11 h-11 rounded-2xl bg-[var(--accent-soft)] text-[var(--brand)] flex items-center justify-center shrink-0">
            <i data-lucide="smile" class="w-6 h-6"></i>
        </div>
        <div>
            <h2 class="text-xl font-display font-semibold text-[var(--text)]">Welcome</h2>
            <p class="mt-1 text-sm muted">Keep your daily activity and customer follow-up on track.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="card p-5 sm:p-6">
            <div class="flex items-center gap-3 mb-5">
                <span class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <i data-lucide="check-square" class="w-5 h-5"></i>
                </span>
                <h2 class="font-display font-semibold text-[var(--text)]">Visits Completed This Month</h2>
            </div>
            <canvas id="visitsChart" height="140"></canvas>
        </div>

        <div class="card p-5 sm:p-6">
            <div class="flex items-center gap-3 mb-5">
                <span class="w-9 h-9 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                </span>
                <h2 class="font-display font-semibold text-[var(--text)]">Customers Added This Month</h2>
            </div>
            <canvas id="customersChart" height="140"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <a href="{{ $isNonWorkingDay ? '#' : route('salesman.customers.create') }}"
            class="card card-hover p-5 sm:p-6 block {{ $isNonWorkingDay ? 'opacity-50 pointer-events-none' : '' }}">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-[var(--accent-soft)] text-[var(--brand)] flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                </span>
                <h3 class="font-display font-semibold text-[var(--text)]">Add Customer</h3>
            </div>
            <p class="mt-3 text-sm muted">Register a new customer.</p>
        </a>

        <a href="{{ $isNonWorkingDay ? '#' : route('salesman.visits.create') }}"
            class="card card-hover p-5 sm:p-6 block {{ $isNonWorkingDay ? 'opacity-50 pointer-events-none' : '' }}">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                </span>
                <h3 class="font-display font-semibold text-[var(--text)]">Add Visit</h3>
            </div>
            <p class="mt-3 text-sm muted">Record a new visit for today.</p>
        </a>

        <a href="{{ $isNonWorkingDay ? '#' : route('salesman.visits.index') }}"
            class="card card-hover p-5 sm:p-6 block {{ $isNonWorkingDay ? 'opacity-50 pointer-events-none' : '' }}">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <i data-lucide="clipboard" class="w-5 h-5"></i>
                </span>
                <h3 class="font-display font-semibold text-[var(--text)]">My Visits</h3>
            </div>
            <p class="mt-3 text-sm muted">View all your visit history.</p>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const visitLabels = @json($visitLabels);
    const visitData = @json($visitData);
    const customerData = @json($customerData);
    const rootStyles = getComputedStyle(document.documentElement);
    const chartColor = (name, fallback) => rootStyles.getPropertyValue(name).trim() || fallback;
    const chartText = chartColor('--text-soft', '#4a3f58');
    const chartGrid = chartColor('--border', '#e8e2f0');
    const chartAccent = chartColor('--accent', '#eb268f');
    const chartAccentSoft = chartColor('--accent-soft', 'rgba(235, 38, 143, .12)');

    new Chart(document.getElementById('visitsChart'), {
        type: 'bar',
        data: {
            labels: visitLabels,
            datasets: [{
                label: 'Completed Visits',
                data: visitData,
                backgroundColor: chartAccentSoft,
                borderColor: chartAccent,
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: chartText },
                    grid: { color: chartGrid }
                },
                x: {
                    ticks: { color: chartText },
                    grid: { color: chartGrid }
                }
            },
            plugins: {
                legend: { labels: { color: chartText } }
            }
        }
    });

    new Chart(document.getElementById('customersChart'), {
        type: 'line',
        data: {
            labels: visitLabels,
            datasets: [{
                label: 'Customers Added',
                data: customerData,
                fill: true,
                borderColor: chartAccent,
                backgroundColor: chartAccentSoft,
                tension: 0.4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: chartText },
                    grid: { color: chartGrid }
                },
                x: {
                    ticks: { color: chartText },
                    grid: { color: chartGrid }
                }
            },
            plugins: {
                legend: { labels: { color: chartText } }
            }
        }
    });

    document.addEventListener("DOMContentLoaded", () => {
        if (window.SigmaIcons) window.SigmaIcons.refresh();
    });
</script>

@endsection
