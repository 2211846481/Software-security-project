<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agreement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'reference_code',
        'signing_date',
        'effective_date',
        'expiry_date',
        'description',
        'status',
        'department_id',
        'agreement_type_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'signing_date' => 'date',
        'effective_date' => 'date',
        'expiry_date' => 'date',
    ];
    
    /**
     * Get the department that owns the Agreement.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    
    /**
     * Get the agreement type that owns the Agreement.
     */
    public function agreementType(): BelongsTo
    {
        return $this->belongsTo(AgreementType::class);
    }

    /**
     * The partners that belong to the Agreement.
     */
    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(PartnerInstitution::class, 'agreement_partners', 'agreement_id', 'partner_institution_id');
    }

    /**
     * The documents that belong to the Agreement.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * علاقة ذاتية تشير إلى اتفاقية أخرى
     */
    public function relatedAgreement(): BelongsTo
    {
        return $this->belongsTo(Agreement::class, 'agreement_id');
    }
}
