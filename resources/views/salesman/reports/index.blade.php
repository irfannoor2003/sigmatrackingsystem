@extends('layouts.app')

@section('title','My Visit Report')

@section('content')
<div class="p-0">
    <h1 class="text-2xl font-bold mb-4">My Visit Report</h1>

    <table class="table-auto w-full border">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2">Customer</th>
                <th class="p-2">Purpose</th>
                <th class="p-2">Status</th>
                <th class="p-2">Notes</th>
                <th class="p-2">Duration (min)</th>
                <th class="p-2">Visit Started Date</th>
                <th class="p-2">Date & Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visits as $v)
            <tr class="border-t">
                <td class="p-2">{{ $v->customer->name }}</td>
                <td class="p-2">{{ $v->purpose }}</td>
                <td class="p-2">{{ ucfirst($v->status) }}</td>
                <td class="p-2">{{ $v->notes }}</td>
                <td class="p-2">{{ $v->duration ?? '-' }}</td>
                <td class="p-2">
                    {{ optional($v->started_at)->format('Y-m-d') }}
                </td>
                <td class="p-2">
                    {{ optional($v->started_at)->format('Y-m-d H:i') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
