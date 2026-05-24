<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // تأكد من استيراد BelongsTo

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_id',
        'file_name',
        'file_content',
        'file_type',
        'file_size',
        'uploaded_by_user_id',
        'description',
    ];

    /**
     * Get the agreement that owns the document.
     */
    public function agreement(): BelongsTo
    {
        return $this->belongsTo(Agreement::class, 'agreement_id');
    }

    // <--- أضف هذه العلاقة الجديدة
    /**
     * Get the user who uploaded the document.
     */
    public function uploadedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }
}