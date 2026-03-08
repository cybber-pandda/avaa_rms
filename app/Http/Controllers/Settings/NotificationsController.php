<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationsController extends Controller
{
    public function edit(Request $request): Response
    {
        $user     = $request->user();
        $settings = $user->notificationSettings ?? null;

        return Inertia::render('Settings/Notifications', [
            'settings' => [
                'email' => [
                    'new_job_matches'         => $settings?->email_new_job_matches         ?? true,
                    'application_status'      => $settings?->email_application_status      ?? true,
                    'interview_invites'       => $settings?->email_interview_invites       ?? true,
                    'messages_from_employers' => $settings?->email_messages_from_employers ?? true,
                ],
                'in_app' => [
                    'new_job_matches'         => $settings?->inapp_new_job_matches         ?? true,
                    'application_status'      => $settings?->inapp_application_status      ?? true,
                    'interview_invites'       => $settings?->inapp_interview_invites       ?? true,
                    'messages_from_employers' => $settings?->inapp_messages_from_employers ?? true,
                ],
            ],
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'email.new_job_matches'         => ['boolean'],
            'email.application_status'      => ['boolean'],
            'email.interview_invites'       => ['boolean'],
            'email.messages_from_employers' => ['boolean'],
            'in_app.new_job_matches'        => ['boolean'],
            'in_app.application_status'     => ['boolean'],
            'in_app.interview_invites'      => ['boolean'],
            'in_app.messages_from_employers'=> ['boolean'],
        ]);

        $request->user()->notificationSettings()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'email_new_job_matches'          => $validated['email']['new_job_matches'],
                'email_application_status'       => $validated['email']['application_status'],
                'email_interview_invites'        => $validated['email']['interview_invites'],
                'email_messages_from_employers'  => $validated['email']['messages_from_employers'],
                'inapp_new_job_matches'          => $validated['in_app']['new_job_matches'],
                'inapp_application_status'       => $validated['in_app']['application_status'],
                'inapp_interview_invites'        => $validated['in_app']['interview_invites'],
                'inapp_messages_from_employers'  => $validated['in_app']['messages_from_employers'],
            ]
        );

        return back();
    }
}