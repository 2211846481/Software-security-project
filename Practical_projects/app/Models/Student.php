<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // أضف هذا السطر
class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'age',
        'registration_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // يمكنك إضافة أي خصائص لا تريد أن تظهر عند تحويل النموذج إلى JSON، مثل 'password' إذا كان موجودًا
        // لا توجد خصائص مخفية بشكل افتراضي للطلاب في هذا السيناريو، ولكن يمكنك إضافتها إذا لزم الأمر
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // يمكنك تحديد كيفية تحويل بعض الخصائص (مثلاً، تحويل تاريخ إلى كائن تاريخ)
        // لا توجد خصائص تحتاج إلى تحويل خاص في هذا السيناريو، ولكن يمكنك إضافتها إذا لزم الأمر
    ];

    // هنا يمكنك تعريف أي علاقات إذا كان الطالب مرتبطًا بجداول أخرى (مثلاً، دروس، درجات، إلخ.)
    // على سبيل المثال:
    /*
    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
    */ //
}
