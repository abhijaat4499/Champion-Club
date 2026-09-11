<?php
use App\Http\Controllers\GymController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminPlanController;
use App\Http\Controllers\AdminTrainerController;




use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/gym', [GymController::class, 'index'])->name('gym.home');
Route::get('/contact', [ContactController::class, 'index'])->name('gym.contact');
Route::post('/contact', [ContactController::class, 'store'])->name('store.contact');

Route::get('/program', [ProgramController::class, 'index'])->name('gym.program');
Route::get('/membership', [MembershipController::class, 'index'])->name('gym.membership');
Route::get('/trainers', [TrainerController::class, 'index'])->name('gym.trainers');

// admin routes below->x

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/verify-otp', [AdminAuthController::class, 'showOtpForm'])->name('admin.otp.form');
Route::post('/admin/verify-otp', [AdminAuthController::class, 'verifyOtp'])->name('admin.otp.verify');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/plans', [AdminPlanController::class, 'index'])->name('admin.plans.index');
    Route::get('/admin/plans/create', [AdminPlanController::class, 'create'])->name('admin.plans.create');
    Route::post('/admin/plans', [AdminPlanController::class, 'store'])->name('admin.plans.store');
    

    Route::get('/admin/plans/{plan}/edit', [AdminPlanController::class, 'edit'])->name('admin.plans.edit');
    Route::put('/admin/plans/{plan}', [AdminPlanController::class, 'update'])->name('admin.plans.update');
    Route::delete('/admin/plans/{plan}', [AdminPlanController::class, 'destroy'])->name('admin.plans.destroy');
    Route::get('/admin/trainers', [AdminTrainerController::class, 'index'])->name('admin.trainers.index');
    Route::get('/admin/trainers/create', [AdminTrainerController::class, 'create'])->name('admin.trainers.create');
    Route::post('/admin/trainers', [AdminTrainerController::class, 'store'])->name('admin.trainers.store');
}
);



