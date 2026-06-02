<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'comment_text',
        'file_path',
        'file_mime_type',
        'file_size'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}