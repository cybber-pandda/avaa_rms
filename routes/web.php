<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Employer\DashboardController as EmployerDashboardController;
use App\Http\Controllers\Employer\JobListingController;
use App\Http\Controllers\Employer\InterviewController;
use App\Http\Controllers\Employer\EmployeeController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmployerVerificationController;
use App\Http\Controllers\Employer\ProfileController as EmployerProfileController;
use App\Http\Controllers\JobSeeker\ProfileController as JobSeekerProfileController;
use App\Http\Controllers\Admin\VerificationsController;
use App\Http\Controllers\JobSeeker\JobBrowseController;
use App\Http\Controllers\JobSeeker\JobApplicationController;
use App\Http\Controllers\Settings\AccountController;
use App\Http\Controllers\Settings\SecurityController;
use App\Http\Controllers\Settings\NotificationsController;
use App\Http\Controllers\Settings\JobPreferencesController;
use App\Http\Controllers\Settings\DocumentsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Home
Route::get('/', fn() => Inertia::render('Welcome'))->name('home');

// Registration
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'showRoleSelection'])->name('register');
    Route::get('/register/employer', [RegisteredUserController::class, 'createEmployer'])->name('register.employer');
    Route::post('/register/employer', [RegisteredUserController::class, 'storeEmployer'])->name('register.employer.store');
    Route::get('/register/job-seeker', [RegisteredUserController::class, 'createJobSeeker'])->name('register.job-seeker');
    Route::post('/register/job-seeker', [RegisteredUserController::class, 'storeJobSeeker'])->name('register.job-seeker.store');
});

// Role-based dashboards
Route::middleware(['auth', 'verified', 'profile.complete'])->group(function () {


    // ── Settings (shared across all roles) ───────────────────────────────────
    Route::prefix('settings')->name('settings.')->group(function () {

        // Account
        Route::get('/account', [AccountController::class, 'edit'])->name('account');
        Route::patch('/account', [AccountController::class, 'update'])->name('account.update');
        Route::post('/account/avatar', [AccountController::class, 'uploadAvatar'])->name('account.avatar');
        Route::delete('/account/avatar', [AccountController::class, 'removeAvatar'])->name('account.avatar.remove');
        Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy');

        // Security & Privacy
        Route::get('/security', [SecurityController::class, 'edit'])->name('security');
        Route::patch('/security', [SecurityController::class, 'update'])->name('security.update');

        // Notifications
        Route::get('/notifications', [NotificationsController::class, 'edit'])->name('notifications');
        Route::patch('/notifications', [NotificationsController::class, 'update'])->name('notifications.update');

        // Job Preferences
        Route::get('/job-preferences', [JobPreferencesController::class, 'edit'])->name('job-preferences');
        Route::patch('/job-preferences', [JobPreferencesController::class, 'update'])->name('job-preferences.update');

        // Documents
        Route::get('/documents', [DocumentsController::class, 'index'])->name('documents');
        Route::post('/documents', [DocumentsController::class, 'store'])->name('documents.store');
        Route::get('/documents/{document}/download', [DocumentsController::class, 'download'])->name('documents.download');
        Route::delete('/documents/{document}', [DocumentsController::class, 'destroy'])->name('documents.destroy');
    });
    // ─────────────────────────────────────────────────────────────────────────

    // ── Employer ─────────────────────────────────────────────────────────────
    Route::middleware('role:employer')->prefix('employer')->name('employer.')->group(function () {

        Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');
        Route::post('/profile/complete', [EmployerProfileController::class, 'complete'])->name('profile.complete');

        // Employer Profile & Settings
        Route::get('/profile', [EmployerProfileController::class, 'show'])->name('profile.show');
        Route::get('/settings', [EmployerProfileController::class, 'settings'])->name('settings');
        Route::patch('/settings/personal', [EmployerProfileController::class, 'updatePersonal'])->name('settings.personal.update');
        Route::patch('/settings/company', [EmployerProfileController::class, 'updateCompany'])->name('settings.company.update');
        Route::post('/profile/avatar', [EmployerProfileController::class, 'uploadAvatar'])->name('profile.avatar');
        Route::delete('/profile/avatar', [EmployerProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');

        Route::get('/jobs', [JobListingController::class, 'index'])->name('jobs.index');
        Route::post('/jobs', [JobListingController::class, 'store'])->name('jobs.store');
        Route::put('/jobs/{job}', [JobListingController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [JobListingController::class, 'destroy'])->name('jobs.destroy');
        Route::patch('/jobs/{job}/status', [JobListingController::class, 'updateStatus'])->name('jobs.status');
        Route::get('/jobs/{job}/applications', [JobListingController::class, 'applications'])->name('jobs.applications');
        Route::patch('/jobs/{job}/applications/{application}', [JobListingController::class, 'updateApplicationStatus'])->name('jobs.applications.status');
        Route::post('/jobs/{job}/applications/{application}/reject', [JobListingController::class, 'rejectApplication'])->name('jobs.applications.reject');
        Route::post('/jobs/{job}/applications/{application}/approve', [JobListingController::class, 'approveApplication'])->name('jobs.applications.approve');

        Route::get('/interviews', [InterviewController::class, 'index'])->name('interviews.index');
        Route::put('/interviews/{interview}', [InterviewController::class, 'update'])->name('interviews.update');
        Route::patch('/interviews/{interview}/status', [InterviewController::class, 'updateStatus'])->name('interviews.status');
        Route::post('/interviews/{interview}/pass', [InterviewController::class, 'passInterview'])->name('interviews.pass');
        Route::post('/interviews/{interview}/fail', [InterviewController::class, 'failInterview'])->name('interviews.fail');

        Route::get('/users', [EmployeeController::class, 'index'])->name('users.index');
        Route::post('/users/{application}/end-contract', [EmployeeController::class, 'endContract'])->name('users.end-contract');
    });
    // ─────────────────────────────────────────────────────────────────────────

    // ── Job Seeker ───────────────────────────────────────────────────────────
    Route::middleware('role:job_seeker')->prefix('job-seeker')->name('job-seeker.')->group(function () {
        Route::post('/profile/complete', [JobSeekerProfileController::class, 'complete'])->name('profile.complete');
        Route::get('/profile', [JobSeekerProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [JobSeekerProfileController::class, 'edit'])->name('profile.edit');
        Route::match(['POST', 'PATCH'], '/profile', [JobSeekerProfileController::class, 'update'])->name('profile.update');
        Route::get('/jobs', [JobBrowseController::class, 'browse'])->name('jobs.browse');
        Route::get('/jobs/saved', [JobBrowseController::class, 'saved'])->name('jobs.saved');
        Route::post('/jobs/{job}/save', [JobBrowseController::class, 'save'])->name('jobs.save');
        Route::delete('/jobs/{job}/unsave', [JobBrowseController::class, 'unsave'])->name('jobs.unsave');
        Route::get('/jobs/{job}/apply', [JobApplicationController::class, 'create'])->name('jobs.apply.form');
        Route::post('/jobs/{job}/apply', [JobApplicationController::class, 'store'])->name('jobs.apply');
        Route::post('/jobs/{job}/apply/draft', [JobApplicationController::class, 'saveDraft'])->name('jobs.apply.draft');
        Route::get('/jobs/{job}', [JobBrowseController::class, 'show'])->name('jobs.show'); // ← add this
    });
    // ─────────────────────────────────────────────────────────────────────────

    // ── Admin ─────────────────────────────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/employers/{user}/verify', [EmployerVerificationController::class, 'verify'])->name('employers.verify');
        Route::post('/employers/{user}/revoke', [EmployerVerificationController::class, 'revoke'])->name('employers.revoke');
        Route::get('/verifications', [EmployerVerificationController::class, 'index'])->name('verifications');
    });
    // ─────────────────────────────────────────────────────────────────────────
});

require __DIR__ . '/auth.php';