<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $fillable = [
        'job_listing_id',
        'user_id',
        'status',
        'cover_letter',
        'resume_path',
        'application_data',
        'rejection_reason',
        'employer_notes',
        'reviewed_at',
        'hired_at',
        'contract_ended_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'hired_at' => 'datetime',
        'contract_ended_at' => 'datetime',
        'application_data' => 'array',
    ];

    /* ── Relationships ── */

    public function jobListing(): BelongsTo
    {
        return $this->belongsTo(JobListing::class, 'job_listing_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function interview()
    {
        return $this->hasOne(\App\Models\Interview::class);
    }

    /* ── Scopes ── */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}