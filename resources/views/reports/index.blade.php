@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div>
    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="bar-chart-3" class="w-6 h-6"></i></span>
            Monthly Reports
        </h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6">
            <h3 class="font-semibold text-[var(--text)] mb-4 flex items-center gap-2">
                <i data-lucide="calendar-check" class="w-5 h-5 text-[var(--accent)]"></i>
                Monthly Visits
            </h3>
            <canvas id="visitsChart" height="140"></canvas>
        </div>

        <div class="card p-6">
            <h3 class="font-semibold text-[var(--text)] mb-4 flex items-center gap-2">
                <i data-lucide="user-plus" class="w-5 h-5 text-[var(--accent)]"></i>
                New Customers (Monthly)
            </h3>
            <canvas id="customersChart" height="140"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const visits = @json($visits ?? array_fill(0, 12, 0));
    const customers = @json($customers ?? array_fill(0, 12, 0));
    const dark = document.documentElement.classList.contains('dark');
    const axis = dark ? '#8f839e' : '#837793';
    const grid = dark ? 'rgba(255,255,255,.08)' : 'rgba(16,12,24,.08)';

    new Chart(document.getElementById('visitsChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Visits',
                data: visits,
                borderColor: '#d6007b',
                backgroundColor: 'rgba(214,0,123,.15)',
                fill: true,
                tension: .35,
                borderWidth: 2
            }]
        },
        options: {
            scales: { y: { ticks: { color: axis }, grid: { color: grid } }, x: { ticks: { color: axis }, grid: { color: grid } } },
            plugins: { legend: { labels: { color: axis } } }
        }
    });

    new Chart(document.getElementById('customersChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'New Customers',
                data: customers,
                backgroundColor: window.wheelColor(3, 70, 60),
                borderRadius: 6
            }]
        },
        options: {
            scales: { y: { ticks: { color: axis }, grid: { color: grid } }, x: { ticks: { color: axis }, grid: { color: grid } } },
            plugins: { legend: { labels: { color: axis } } }
        }
    });
</script>
@endsection