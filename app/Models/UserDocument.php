<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDocument extends Model
{
    protected $fillable = [
        'user_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'document_type',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}