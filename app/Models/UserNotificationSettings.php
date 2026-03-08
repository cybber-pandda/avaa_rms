<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotificationSettings extends Model
{
    protected $fillable = [
        'user_id',
        'email_new_job_matches',
        'email_application_status',
        'email_interview_invites',
        'email_messages_from_employers',
        'inapp_new_job_matches',
        'inapp_application_status',
        'inapp_interview_invites',
        'inapp_messages_from_employers',
    ];

    protected $casts = [
        'email_new_job_matches'          => 'boolean',
        'email_application_status'       => 'boolean',
        'email_interview_invites'        => 'boolean',
        'email_messages_from_employers'  => 'boolean',
        'inapp_new_job_matches'          => 'boolean',
        'inapp_application_status'       => 'boolean',
        'inapp_interview_invites'        => 'boolean',
        'inapp_messages_from_employers'  => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}