<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Customer;
use App\Helpers\AttendanceHelper;
use Carbon\Carbon;

class SalesmanDashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Current month range
        $start = Carbon::now()->startOfMonth();
        $end   = Carbon::now()->endOfMonth();

        // Completed visits count per day
        $visits = Visit::where('salesman_id', $userId)
            ->where('status', 'completed')
            ->whereBetween('completed_at', [$start, $end])
            ->get()
            ->groupBy(function ($v) {
                return Carbon::parse($v->completed_at)->format('d');
            });

        $visitLabels = [];
        $visitData = [];

        // Loop days of month
        for ($i = 1; $i <= Carbon::now()->daysInMonth; $i++) {
            $day = str_pad($i, 2, '0', STR_PAD_LEFT);
            $visitLabels[] = $day;
            $visitData[] = isset($visits[$day]) ? count($visits[$day]) : 0;
        }

        // Customers added this month
        $customers = Customer::where('salesman_id', $userId)
            ->whereBetween('created_at', [$start, $end])
            ->get()
            ->groupBy(function ($c) {
                return Carbon::parse($c->created_at)->format('d');
            });

        $customerData = [];

        for ($i = 1; $i <= Carbon::now()->daysInMonth; $i++) {
            $day = str_pad($i, 2, '0', STR_PAD_LEFT);
            $customerData[] = isset($customers[$day]) ? count($customers[$day]) : 0;
        }

        // ✅ Check if today is a holiday
        $today = Carbon::today()->format('Y-m-d');

$isNonWorkingDay = AttendanceHelper::isNonWorkingDay($today);
$nonWorkingReason = AttendanceHelper::nonWorkingReason($today);


        return view('salesman.dashboard', [
            'visitLabels'   => $visitLabels,
            'visitData'     => $visitData,
            'customerData'  => $customerData,
            'isNonWorkingDay' => $isNonWorkingDay,
    'nonWorkingReason' => $nonWorkingReason,
        ]);
    }
}
