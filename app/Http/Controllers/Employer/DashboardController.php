<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user()->load('employerProfile');

        // Mark verification notification as read when they visit dashboard
        $user->unreadNotifications()
            ->where('type', 'App\Notifications\EmployerVerifiedNotification')
            ->update(['read_at' => now()]);

        // ── Dashboard statistics ─────────────────────────────────────────
        $activeUsersCount = User::where('role', 'job_seeker')->where('status', 'active')->count();
        $jobsPostedCount = JobListing::where('employer_id', $user->id)->count();
        $applicationsCount = JobApplication::whereHas('jobListing', fn($q) => $q->where('employer_id', $user->id))->count();
        $totalVisitsCount = 0; // placeholder – wire up analytics later

        // ── Recent posted jobs (latest 6) ────────────────────────────────
        $recentJobs = JobListing::where('employer_id', $user->id)
            ->withCount('applications')
            ->latest()
            ->take(6)
            ->get();

        return Inertia::render('Employer/Dashboard', [
            'user' => $user,
            'profile' => $user->employerProfile,
            'profileComplete' => $user->profile_completed,
            'isVerified' => $user->employerProfile?->is_verified ?? false,
            'justVerified' => $user->notifications()
                ->where('type', 'App\Notifications\EmployerVerifiedNotification')
                ->whereNull('read_at')
                ->exists(),
            'needsPhone' => (bool) ($user->google_id && !$user->phone),

            // new dashboard data
            'activeUsersCount' => $activeUsersCount,
            'jobsPostedCount' => $jobsPostedCount,
            'applicationsCount' => $applicationsCount,
            'totalVisitsCount' => $totalVisitsCount,
            'recentJobs' => $recentJobs,
        ]);
    }
}