<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    protected $fillable = [
        'job_application_id',
        'job_listing_id',
        'candidate_id',
        'employer_id',
        'interviewer_name',
        'interview_date',
        'interview_time',
        'interview_type',
        'location_or_link',
        'notes',
        'status',
        'interview_result',
    ];

    protected $casts = [
        'interview_date' => 'date',
    ];

    /* ── Relationships ── */

    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function jobListing(): BelongsTo
    {
        return $this->belongsTo(JobListing::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    /* ── Scopes ── */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
