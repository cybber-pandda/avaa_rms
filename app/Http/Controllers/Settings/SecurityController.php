<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SecurityController extends Controller
{
    public function edit(Request $request): Response
    {
        $user     = $request->user();
        $settings = $user->securitySettings ?? null;

        return Inertia::render('Settings/SecurityPrivacy', [
            'settings' => [
                'two_factor_enabled'     => $settings?->two_factor_enabled     ?? false,
                'login_alert_email'      => $settings?->login_alert_email      ?? true,
                'login_alert_push'       => $settings?->login_alert_push       ?? true,
                'marketplace_visibility' => $settings?->marketplace_visibility ?? 'public',
                'show_contact_info'      => $settings?->show_contact_info      ?? true,
                'show_ratings'           => $settings?->show_ratings           ?? true,
                'hide_while_employed'    => $settings?->hide_while_employed    ?? false,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'two_factor_enabled'     => ['boolean'],
            'login_alert_email'      => ['boolean'],
            'login_alert_push'       => ['boolean'],
            'marketplace_visibility' => ['in:public,agency_only,private'],
            'show_contact_info'      => ['boolean'],
            'show_ratings'           => ['boolean'],
            'hide_while_employed'    => ['boolean'],
        ]);

        $request->user()->securitySettings()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return back();
    }
}