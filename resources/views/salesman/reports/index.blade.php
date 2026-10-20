@extends('layouts.app')

@section('title','My Visit Report')

@section('content')
<div class="p-0">

    <div class="page-head !mb-6">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="bar-chart-3" class="w-6 h-6"></i></span>
            My Visit Report
        </h1>
    </div>

    <div class="card !rounded-2xl shadow-card-lg">
        <div class="tbl-wrap !border-0 !shadow-none">
        <table class="tbl w-full">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Duration (min)</th>
                    <th>Visit Started Date</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($visits as $v)
                <tr>
                    <td class="text-[var(--text)]">{{ $v->customer->name }}</td>
                    <td class="text-[var(--text-soft)]">{{ $v->purpose }}</td>
                    <td>
                        @if($v->status == 'started')
                            <span class="badge badge-amber">{{ ucfirst($v->status) }}</span>
                        @elseif($v->status == 'completed')
                            <span class="badge badge-emerald">{{ ucfirst($v->status) }}</span>
                        @elseif($v->status == 'blocked')
                            <span class="badge badge-red">{{ ucfirst($v->status) }}</span>
                        @else
                            <span class="badge badge-gray">{{ ucfirst($v->status) }}</span>
                        @endif
                    </td>
                    <td class="text-[var(--text-muted)]">{{ $v->notes }}</td>
                    <td class="text-[var(--text-soft)]">{{ $v->duration ?? '-' }}</td>
                    <td class="text-[var(--text-soft)]">
                        {{ optional($v->started_at)->format('Y-m-d') }}
                    </td>
                    <td class="text-[var(--text-soft)]">
                        {{ optional($v->started_at)->format('Y-m-d H:i') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection