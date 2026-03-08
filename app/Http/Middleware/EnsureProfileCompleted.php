<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirects job seekers who haven't completed their profile back to the
 * jobs browse page (where the onboarding modal is rendered).
 *
 * Exempt routes:
 *  - job-seeker.jobs.browse   (the page with the onboarding modal)
 *  - job-seeker.profile.complete (the POST endpoint the modal submits to)
 *  - logout
 */
class EnsureProfileCompleted
{
    protected array $except = [
        'job-seeker.jobs.browse',
        'job-seeker.profile.complete',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user
            && $user->role === 'job_seeker'
            && !$user->profile_completed
            && !in_array($request->route()?->getName(), $this->except)
        ) {
            return redirect()->route('job-seeker.jobs.browse');
        }

        return $next($request);
    }
}
