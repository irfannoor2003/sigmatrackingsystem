@extends('layouts.app')

@section('title','Salesman Dashboard')

@section('content')

@include('partials.late-reason-modal', ['lateRecord' => $pendingLateRecord ?? null])

<div class="p-0">

    <!-- Page Title -->
    <h1 class="text-3xl font-bold text-white tracking-wide mb-8">
        Salesman Dashboard
    </h1>

    <!-- ✅ Holiday Banner -->
   @if($isNonWorkingDay)
    <div class="bg-purple-500/10 border border-purple-500/30 text-purple-300 p-4 rounded mb-6 text-center font-semibold">
        📢 Today is a non-working day:
        <span class="font-bold">{{ $nonWorkingReason }}</span>
    </div>
@endif


    <!-- Welcome Card -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg flex items-center gap-4">
        <i data-lucide="smile" class="w-8 h-8 text-pink-500"></i>
        <div>
            <h2 class="text-xl font-semibold text-magenta-300">Welcome!</h2>
            <p class="mt-2 text-white/80">
                Track your visits and customer additions for this month.
            </p>
        </div>
    </div>

    <!-- Graphs -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

        <!-- Completed Visits Chart -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <i data-lucide="check-square" class="w-5 h-5"></i>
                Visits Completed This Month
            </h3>
            <canvas id="visitsChart" height="140"></canvas>
        </div>

        <!-- New Customers Chart -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                Customers Added This Month
            </h3>
            <canvas id="customersChart" height="140"></canvas>
        </div>

    </div>

    <!-- Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">

        <!-- Add Customer -->
        <a href="{{ $isNonWorkingDay ? '#' : route('salesman.customers.create') }}"
   class="block bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-lg p-6
          {{ $isNonWorkingDay ? 'opacity-50 cursor-not-allowed pointer-events-none' : 'hover:bg-white/20' }}
          transition border-l-4"
   style="border-left-color:#ff2ba6;">

            <div class="flex items-center gap-3">
                <i data-lucide="user-plus" class="w-7 h-7 text-pink-500"></i>
                <h3 class="text-xl font-semibold text-magenta-300">Add Customer</h3>
            </div>
            <p class="text-white/70 mt-2">Register a new customer.</p>
        </a>

        <!-- Add Visit -->
        <a href="{{ $isNonWorkingDay ? '#' : route('salesman.visits.create') }}"
   class="block bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-lg p-6
          {{ $isNonWorkingDay ? 'opacity-50 cursor-not-allowed pointer-events-none' : 'hover:bg-white/20' }}
          transition border-l-4"
   style="border-left-color:#ff2ba6;">

            <div class="flex items-center gap-3">
                <i data-lucide="map-pin" class="w-7 h-7 text-pink-500"></i>
                <h3 class="text-xl font-semibold text-magenta-300">Add Visit</h3>
            </div>
            <p class="text-white/70 mt-2">Record a new visit for today.</p>
        </a>

        <!-- My Visits -->
        <a href="{{ $isNonWorkingDay ? '#' : route('salesman.visits.index') }}"
   class="block bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-lg p-6
          {{ $isNonWorkingDay ? 'opacity-50 cursor-not-allowed pointer-events-none' : 'hover:bg-white/20' }}
          transition border-l-4"
   style="border-left-color:#ff2ba6;">

            <div class="flex items-center gap-3">
                <i data-lucide="clipboard" class="w-7 h-7 text-pink-500"></i>
                <h3 class="text-xl font-semibold text-magenta-300">My Visits</h3>
            </div>
            <p class="text-white/70 mt-2">View all your visit history.</p>
        </a>

    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const visitLabels = @json($visitLabels);
    const visitData = @json($visitData);
    const customerData = @json($customerData);

    // Visits Chart
    new Chart(document.getElementById('visitsChart'), {
        type: 'bar',
        data: {
            labels: visitLabels,
            datasets: [{
                label: 'Completed Visits',
                data: visitData,
                backgroundColor: 'rgba(129, 140, 248, 0.6)',
                borderColor: 'rgb(129, 140, 248)',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: "#ffffff" },
                    grid: { color: "rgba(255,255,255,0.1)" }
                },
                x: {
                    ticks: { color: "#ffffff" },
                    grid: { color: "rgba(255,255,255,0.1)" }
                }
            },
            plugins: {
                legend: { labels: { color: "#ffffff" } }
            }
        }
    });

    // Customers Added Chart
    new Chart(document.getElementById('customersChart'), {
        type: 'line',
        data: {
            labels: visitLabels,
            datasets: [{
                label: 'Customers Added',
                data: customerData,
                fill: true,
                borderColor: 'rgb(236, 72, 153)',
                backgroundColor: 'rgba(236, 72, 153, 0.4)',
                tension: 0.4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: "#ffffff" },
                    grid: { color: "rgba(255,255,255,0.1)" }
                },
                x: {
                    ticks: { color: "#ffffff" },
                    grid: { color: "rgba(255,255,255,0.1)" }
                }
            },
            plugins: {
                legend: { labels: { color: "#ffffff" } }
            }
        }
    });

    // Render Lucide icons
    document.addEventListener("DOMContentLoaded", () => {
        lucide.createIcons();
    });
</script>

@endsection
