<?php

use App\Http\Controllers\ArchivedResidentController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\ClearanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));

// Verification (public — no auth required)
Route::get('/verify',  [VerificationController::class, 'index'])->name('verify.index');
Route::post('/verify', [VerificationController::class, 'verify'])->name('verify.check');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'active.user'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Residents
    Route::resource('residents', ResidentController::class);

    // Households
    Route::resource('households', HouseholdController::class);
    Route::post('/households/{household}/assign-member', [HouseholdController::class, 'assignMember'])->name('households.assignMember');
    Route::delete('/households/{household}/members/{resident}', [HouseholdController::class, 'removeMember'])->name('households.removeMember');
    Route::patch('/households/{household}/head/{resident}', [HouseholdController::class, 'setHead'])->name('households.setHead');

    // Clearances (no edit/update — clearances are immutable; use revoke)
    Route::resource('clearances', ClearanceController::class)->except(['edit', 'update']);
    Route::get('/clearances/{clearance}/print', [ClearanceController::class, 'print'])->name('clearances.print');
    Route::patch('/clearances/{clearance}/revoke', [ClearanceController::class, 'revoke'])->name('clearances.revoke');

    // Blotter — explicit routes since update behavior is restricted
    Route::resource('blotter', BlotterController::class);
    Route::get('/blotter/{blotter}/print', [BlotterController::class, 'print'])->name('blotter.print');

    // Archived Residents — index/show + CSV export
    Route::get('/archives/residents',                [ArchivedResidentController::class, 'index'])->name('archived_residents.index');
    Route::get('/archives/residents/export',         [ArchivedResidentController::class, 'export'])->name('archived_residents.export');
    Route::get('/archives/residents/{archived_resident}', [ArchivedResidentController::class, 'show'])->name('archived_residents.show');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/residents/export',  [ReportController::class, 'exportResidents'])->name('reports.residents.export');
    Route::get('/reports/clearances/export', [ReportController::class, 'exportClearances'])->name('reports.clearances.export');
    Route::get('/reports/blotter/export',    [ReportController::class, 'exportBlotter'])->name('reports.blotter.export');
    Route::get('/reports/households/export', [ReportController::class, 'exportHouseholds'])->name('reports.households.export');

    // User Management (Admin only)
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Profile
    Route::get('/profile',     [UserController::class, 'profile'])->name('profile');
    Route::patch('/profile',   [UserController::class, 'updateProfile'])->name('profile.update');
});
