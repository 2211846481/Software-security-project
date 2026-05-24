<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerInstitution extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'country',
        'contact_name',
        'contact_email',
        'website',
        'sector',
    ];

    /**
     * Define the many-to-many relationship with Agreements.
     */
    public function agreements()
    {
        // هذه الدالة تحدد العلاقة "متعدد لمتعدد" مع الـ Model 'Agreement'
        // وتفترض وجود جدول وسيط يسمى 'agreement_partners'
        // الأعمدة في الجدول الوسيط هي 'partner_institution_id' و 'agreement_id'
        return $this->belongsToMany(Agreement::class, 'agreement_partners', 'partner_institution_id', 'agreement_id');
    }
}