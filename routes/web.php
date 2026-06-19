<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/

// Base
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SalesmanDashboardController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;


// Admin
use App\Http\Controllers\Admin\AttendanceReportController;
use App\Http\Controllers\Admin\ManualVisitController;
use App\Http\Controllers\Admin\SalesmanController as AdminSalesmanController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\OldCustomerController as AdminOldCustomerController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\HRDashboardController;
use App\Http\Controllers\SalesHeadDashboardController;
use App\Http\Controllers\HR\StaffController as HRStaffController;
use App\Http\Controllers\VisitExportController;
use App\Http\Controllers\Admin\AdminVisitController;





// Salesman
use App\Http\Controllers\Salesman\CustomerController as SalesmanCustomerController;
use App\Http\Controllers\Salesman\OldCustomerController as SalesmanOldCustomerController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT (ROLE BASED)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin'     => redirect()->route('admin.dashboard'),
        'hr'        => redirect()->route('hr.dashboard'),
        'saleshead' => redirect()->route('salehead.dashboard'),
        'salesman'  => redirect()->route('salesman.dashboard'),
        'it', 'account', 'store', 'office_boy' => redirect()->route('staff.dashboard'),
        default     => abort(403),
    };
})->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
| (Admin + HR + SalesHead will reuse these)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        /* ---------------- Reports ---------------- */
        Route::get('/reports', [ReportController::class, 'adminReport'])
            ->name('reports.index');

        Route::get('/reports/{id}', [ReportController::class, 'show'])
            ->name('reports.show');

        /* ---------------- Holidays ---------------- */
        Route::post('/holiday/store', [HolidayController::class, 'store'])
            ->name('holiday.store');

        /* ---------------- Customers ---------------- */
        Route::get('/customers', [AdminCustomerController::class, 'index'])
            ->name('customers.index');

        Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])
            ->name('customers.show');

        Route::get('/customers/export/all', [AdminCustomerController::class, 'exportAll'])
            ->name('customers.export.all');

        Route::get('/customers/export/{id}', [AdminCustomerController::class, 'exportSingle'])
            ->name('customers.export.single');

        Route::post('/customers/export/bulk', [AdminCustomerController::class, 'exportBulk'])
            ->name('customers.export.bulk');

        Route::get('/attendance/today', [AdminDashboardController::class, 'todayAttendance'])
            ->name('attendance.today');

        /* ---------------- Salesmen ---------------- */
        Route::resource('salesmen', AdminSalesmanController::class)
            ->except(['show']);
        // Block / unblock
        Route::post('salesmen/{salesman}/block', [AdminSalesmanController::class, 'block'])
            ->name('salesmen.block');

        Route::post('salesmen/{salesman}/unblock', [AdminSalesmanController::class, 'unblock'])
            ->name('salesmen.unblock');

        /* ---------------- Attendance ---------------- */
        Route::prefix('attendance')->name('attendance.')->group(function () {

            Route::get('/', [AttendanceReportController::class, 'index'])
                ->name('index');

            Route::get('/staff/{id}', [AttendanceReportController::class, 'staffReport'])
                ->name('staff');

            Route::post('/staff/{id}/leave', [AttendanceReportController::class, 'markLeave'])
                ->name('leave');

            Route::post('/update/{attendanceId}', [AttendanceReportController::class, 'updateAttendance'])
                ->name('update');

            Route::get('/export/all', [AttendanceReportController::class, 'exportAllExcel'])
                ->name('export.all');

            Route::get('/export/single/{id}', [AttendanceReportController::class, 'exportSingleExcel'])
                ->name('export.single');

            Route::get('/export/pdf', [AttendanceReportController::class, 'exportPdf'])
                ->name('export.pdf');

            Route::get('/leave-requests', [AttendanceReportController::class, 'leaveRequests'])
                ->name('leave-requests');

            /* -------- Manual Visits (ADMIN ONLY – later we lock) -------- */
            Route::get('/manual-visit/{user}', [ManualVisitController::class, 'create'])
                ->name('manual.visit.create');

            Route::post('/manual-visit/{user}', [ManualVisitController::class, 'store'])
                ->name('manual.visit.store');

            Route::get('/export/range', [AttendanceReportController::class, 'exportRange'])
                ->name('export.range');

        });



        /* ---------------- Promotions ---------------- */
        Route::post('/promotions/send', [PromotionController::class, 'send'])
            ->name('promotions.send');

        /* ---------------- Staff ---------------- */
        Route::get('/staff', [StaffController::class, 'index'])
            ->name('staff.index');

        Route::get('/staff/create', [StaffController::class, 'create'])
            ->name('staff.create');

        Route::post('/staff', [StaffController::class, 'store'])
            ->name('staff.store');

        Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])
            ->name('staff.edit');

        Route::put('/staff/{staff}', [StaffController::class, 'update'])
            ->name('staff.update');

        Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])
            ->name('staff.destroy');

            Route::post('/staff/{staff}/block', [StaffController::class, 'block'])
                ->name('staff.block');

            Route::post('/staff/{staff}/unblock', [StaffController::class, 'unblock'])
                ->name('staff.unblock');


        /* ---------------- Old Customers ---------------- */
        Route::get('/old-customers', [AdminOldCustomerController::class, 'index'])
            ->name('old-customers.index');

        Route::get('/old-customers/import', [AdminOldCustomerController::class, 'importForm'])
            ->name('old-customers.import.form');

        Route::post('/old-customers/import', [AdminOldCustomerController::class, 'import'])
            ->name('old-customers.import');

        /* ---------------- Visits ---------------- */
        Route::get('/visits/blocked', [AdminVisitController::class, 'index'])
            ->name('visits.blocked');

        Route::post('/visits/{id}/unblock', [AdminVisitController::class, 'unblock'])
            ->name('visits.unblock');
    });

/*
|--------------------------------------------------------------------------
| SALESMAN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:salesman'])
    ->prefix('salesman')
    ->name('salesman.')
    ->group(function () {

        Route::get('/dashboard', [SalesmanDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('customers', SalesmanCustomerController::class)
            ->only(['index', 'create', 'store', 'show', 'edit', 'update']);

        Route::resource('visits', VisitController::class)
         ->only(['index', 'create', 'store', 'show', 'edit', 'update']);

        Route::post('/visits/{id}/complete', [VisitController::class, 'complete'])
            ->name('visits.complete');

        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('index');
            Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('clockin');
            Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('clockout');
            Route::get('/history', [AttendanceController::class, 'history'])->name('history');
        });

        Route::get('/reports', [ReportController::class, 'salesmanReport'])
            ->name('reports.index');

        Route::get('/reports/monthly-visits', [ReportController::class, 'monthlyVisitReport'])
            ->name('reports.monthly.visits');

        Route::get('/old-customers', [SalesmanOldCustomerController::class, 'index'])
            ->name('old-customers.index');

        Route::get('/old-customers/import', [SalesmanOldCustomerController::class, 'importForm'])
            ->name('old-customers.import.form');

        Route::post('/old-customers/import', [SalesmanOldCustomerController::class, 'import'])
            ->name('old-customers.import');
    });

/*
|--------------------------------------------------------------------------
| STAFF (IT / ACCOUNTS / STORE / OFFICE BOY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:it,account,store,office_boy'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        Route::get('/dashboard', [StaffDashboardController::class, 'index'])
            ->name('dashboard');

        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('index');
            Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('clockin');
            Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('clockout');
            Route::get('/history', [AttendanceController::class, 'history'])->name('history');
        });
    });

/*
|--------------------------------------------------------------------------
| COMMON ATTENDANCE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->get('/attendance/check-work-hours', [AttendanceController::class, 'checkWorkHours']);

Route::middleware('auth')
    ->post('/attendance/leave', [AttendanceController::class, 'requestLeave'])
    ->name('attendance.leave');

Route::post('/attendance/clock-in-request',
    [AttendanceController::class, 'clockInRequest']
)->middleware('auth');

Route::get('/attendance/verify/{token}',
    [AttendanceController::class, 'verifyClockIn']
)->name('attendance.verify')->middleware('signed');

// SalesHead Routes
Route::prefix('salehead')->middleware(['auth','role:saleshead'])->name('salehead.')->group(function () {

    // ---------------- Dashboard ----------------
    Route::get('/dashboard', [SalesHeadDashboardController::class, 'index'])
        ->name('dashboard');

    // ---------------- Visits ----------------
    Route::get('/visits', [SalesHeadDashboardController::class, 'visitsReport'])
        ->name('reports.index');

    Route::get('/visits/{id}', [SalesHeadDashboardController::class, 'showVisit'])
        ->name('visits.show');

    // ---------------- Customers ----------------
    Route::get('/customers', [SalesHeadDashboardController::class, 'customers'])
        ->name('customers.index');

    Route::get('/customers/{id}', [SalesHeadDashboardController::class, 'showCustomer'])
        ->name('customers.show');


});




Route::prefix('hr')
    ->middleware(['auth', 'role:hr'])
    ->name('hr.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [HrDashboardController::class, 'index'])
        ->name('dashboard');
        // HR Holiday Management
;
 Route::get('attendance/today', [HrDashboardController::class, 'todayAttendance'])
            ->name('attendance.today');

    // Staff
    // Staff management
Route::prefix('staff')->name('staff.')->group(function () {

    Route::get('/', [HRStaffController::class, 'index'])->name('index');      // List all staff
    Route::get('/create', [HRStaffController::class, 'create'])->name('create'); // Create form
    Route::post('/', [HRStaffController::class, 'store'])->name('store');        // Store new staff
    Route::get('/{staff}/edit', [HRStaffController::class, 'edit'])->name('edit');   // Edit form
    Route::put('/{staff}', [HRStaffController::class, 'update'])->name('update');   // Update staff
    Route::delete('/{staff}', [HRStaffController::class, 'destroy'])->name('destroy'); // Delete staff
});


    // Attendance
    Route::prefix('attendance')->name('attendance.')->group(function () {

        // Attendance overview (NO ID)
        Route::get('/', [HrDashboardController::class, 'attendanceIndex'])
            ->name('index');

        // Leave requests
        Route::get('/leave-requests', [HrDashboardController::class, 'leaveRequests'])
            ->name('leave-requests');

        // Single staff attendance (ID REQUIRED)
        Route::get('/staff/{id}', [HrDashboardController::class, 'staffReport'])
            ->name('staff');

        // Mark leave (ID REQUIRED)
        Route::post('/staff/{id}/leave', [HrDashboardController::class, 'markLeave'])
            ->name('leave');

        // Manual visit
        Route::post('/manual-visit/{user}', [HrDashboardController::class, 'storeManualVisit'])
            ->name('manual.visit.store');

            // Exports
            Route::get('/export/all', [HrDashboardController::class, 'exportExcel'])
            ->name('export.all');

            Route::get('/export/single/{id}', [HrDashboardController::class, 'exportExcel'])
            ->name('export.single');

            Route::get('/export/range', [HrDashboardController::class, 'exportRange'])
            ->name('export.range');
            });
            Route::post('/holiday/store', [HrDashboardController::class, 'storeHoliday'])->name('holiday.store');
});



Route::get('/visits/export/monthly', [VisitExportController::class, 'monthly'])
    ->name('visits.export.monthly')
    ->middleware('auth');


    // ---------------- MANUAL ATTENDANCE ----------------
Route::middleware(['auth', 'role:admin,hr'])
    ->prefix('attendance')
    ->name('attendance.')
    ->group(function () {

        // Store manual attendance
        Route::post('/manual/store', [AttendanceController::class, 'manualStore'])
            ->name('manual.store');
    });


// ========================================
// ZKTeco SenseFace Device Routes (NO AUTH)
// ========================================
use App\Http\Controllers\ZKTecoController;


Route::match(['GET','POST'], '/iclock/cdata', [ZKTecoController::class, 'cdata']);
Route::match(['GET','POST'], '/iclock/devicecmd', [ZKTecoController::class, 'deviceCmd']);
Route::match(['GET','POST'], '/iclock/getrequest', [ZKTecoController::class, 'getRequest']);
