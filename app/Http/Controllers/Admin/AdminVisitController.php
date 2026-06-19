<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminVisitController extends Controller
{
    /**
     * Display blocked visits
     */
    public function index()
    {
        $blockedVisits = Visit::with(['salesman', 'customer'])
            ->where('status', 'blocked')
            ->orderBy('blocked_at', 'desc')
            ->paginate(20);

        return view('admin.visits.blocked', compact('blockedVisits'));
    }

    /**
     * Unblock a visit
     */
    public function unblock(Request $request, $id)
    {
        $visit = Visit::where('status', 'blocked')->findOrFail($id);

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $adminId = Auth::id();

        $visit->update([
            'status' => 'started',
            'unblocked_at' => now(),
            'unblocked_by' => $adminId,
        ]);

        // Log the action
        Log::info('Visit unblocked by admin', [
            'visit_id' => $visit->id,
            'admin_id' => $adminId,
            'reason' => $request->reason,
        ]);

        // Send email notification
        $salesman = $visit->salesman;
        $admin = User::find($adminId);

        if ($salesman && $salesman->email) {
            \App\Services\NotificationService::sendEmailVisitUnblocked(
                $salesman->email,
                $salesman->name,
                $visit,
                $admin->name ?? 'Admin'
            );
        }

        return back()->with('success', 'Visit unblocked successfully!');
    }
}