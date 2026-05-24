# نظام إدارة المؤسسات الشريكة

## نظرة عامة

نظام إدارة المؤسسات الشريكة هو جزء من نظام إدارة الاتفاقيات الذي يتيح للمستخدمين إدارة المؤسسات الشريكة المرتبطة بالاتفاقيات.

## الميزات الرئيسية

### 1. إدارة المؤسسات الشريكة (CRUD)
- **عرض قائمة المؤسسات**: عرض جميع المؤسسات الشريكة مع تفاصيلها
- **إضافة مؤسسة جديدة**: إنشاء مؤسسة شريكة جديدة مع التحقق من صحة البيانات
- **تعديل المؤسسة**: تحديث بيانات المؤسسة الموجودة
- **حذف المؤسسة**: حذف المؤسسة مع التحقق من عدم وجود اتفاقيات مرتبطة
- **عرض تفاصيل المؤسسة**: عرض تفاصيل كاملة للمؤسسة مع الاتفاقيات المرتبطة

### 2. البحث والتصفية
- **البحث**: البحث في المؤسسات حسب الاسم، البلد، القطاع، أو الشخص المسؤول
- **التصفية حسب القطاع**: عرض المؤسسات حسب القطاع المحدد

### 3. العلاقات
- **علاقة متعدد لمتعدد مع الاتفاقيات**: كل مؤسسة يمكن أن ترتبط بعدة اتفاقيات
- **حماية من الحذف**: لا يمكن حذف مؤسسة مرتبطة باتفاقيات

## البنية التقنية

### النماذج (Models)

#### PartnerInstitution
```php
class PartnerInstitution extends Model
{
    protected $fillable = [
        'name',
        'country', 
        'contact_name',
        'contact_email',
        'website',
        'sector',
    ];

    public function agreements()
    {
        return $this->belongsToMany(Agreement::class, 'agreement_partners', 'partner_id', 'agreement_id');
    }
}
```

### الكونترولر (Controller)

#### PartnerInstitutionController
الوظائف المتاحة:
- `index()` - عرض قائمة المؤسسات
- `create()` - عرض نموذج الإضافة
- `store()` - حفظ مؤسسة جديدة
- `show()` - عرض تفاصيل المؤسسة
- `edit()` - عرض نموذج التعديل
- `update()` - تحديث بيانات المؤسسة
- `destroy()` - حذف المؤسسة
- `search()` - البحث في المؤسسات
- `bySector()` - تصفية حسب القطاع

### المسارات (Routes)

```php
Route::prefix('partners')->group(function () {
    Route::get('/', [PartnerInstitutionController::class, 'index'])->name('partners.list');
    Route::get('/create', [PartnerInstitutionController::class, 'create'])->name('partners.create');
    Route::post('/', [PartnerInstitutionController::class, 'store'])->name('partners.store');
    Route::get('/{partner}', [PartnerInstitutionController::class, 'show'])->name('partners.show');
    Route::get('/{partner}/edit', [PartnerInstitutionController::class, 'edit'])->name('partners.edit');
    Route::put('/{partner}', [PartnerInstitutionController::class, 'update'])->name('partners.update');
    Route::delete('/{partner}', [PartnerInstitutionController::class, 'destroy'])->name('partners.destroy');
    Route::get('/search', [PartnerInstitutionController::class, 'search'])->name('partners.search');
    Route::get('/sector', [PartnerInstitutionController::class, 'bySector'])->name('partners.bySector');
});
```

### قاعدة البيانات

#### جدول partner_institutions
```sql
CREATE TABLE partner_institutions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    country VARCHAR(100) NOT NULL,
    contact_name VARCHAR(255) NULL,
    contact_email VARCHAR(255) NULL,
    website VARCHAR(255) NULL,
    sector VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### جدول agreement_partners (جدول وسيط)
```sql
CREATE TABLE agreement_partners (
    agreement_id BIGINT UNSIGNED,
    partner_id BIGINT UNSIGNED,
    PRIMARY KEY (agreement_id, partner_id),
    FOREIGN KEY (agreement_id) REFERENCES agreements(id) ON DELETE CASCADE,
    FOREIGN KEY (partner_id) REFERENCES partner_institutions(id) ON DELETE CASCADE
);
```

## ملفات العرض (Views)

### 1. partners/index.blade.php
- عرض قائمة المؤسسات في جدول
- أزرار الإجراءات (تعديل، حذف)
- رسائل النجاح/الخطأ
- رابط إضافة مؤسسة جديدة

### 2. partners/create.blade.php
- نموذج إضافة مؤسسة جديدة
- التحقق من صحة البيانات
- رسائل الأخطاء

### 3. partners/edit.blade.php
- نموذج تعديل المؤسسة
- تعبئة البيانات الحالية
- التحقق من صحة البيانات

### 4. partners/show.blade.php
- عرض تفاصيل المؤسسة
- قائمة الاتفاقيات المرتبطة
- أزرار التعديل والعودة

## التحقق من صحة البيانات (Validation)

```php
$validated = $request->validate([
    'name' => 'required|string|max:255',
    'country' => 'required|string|max:100',
    'contact_name' => 'nullable|string|max:255',
    'contact_email' => 'nullable|email|max:255',
    'website' => 'nullable|url|max:255',
    'sector' => 'nullable|string|max:100',
]);
```

## معالجة الأخطاء

- **ValidationException**: للتعامل مع أخطاء التحقق من صحة البيانات
- **Log**: تسجيل الأخطاء في ملفات السجل
- **User-friendly messages**: رسائل خطأ واضحة للمستخدم

## البيانات التجريبية

يتم إنشاء بيانات تجريبية عبر `PartnerInstitutionSeeder`:

```php
PartnerInstitution::create([
    'name' => 'جامعة التكنولوجيا',
    'country' => 'ألمانيا',
    'contact_name' => 'د. هانس مولر',
    'contact_email' => 'h.muller@tech-uni.de',
    'website' => 'https://www.tech-uni.de',
    'sector' => 'جامعة',
]);
```

## كيفية الاستخدام

### 1. تشغيل الهجرات
```bash
php artisan migrate
```

### 2. تشغيل البيانات التجريبية
```bash
php artisan db:seed --class=PartnerInstitutionSeeder
```

### 3. الوصول للنظام
- قائمة المؤسسات: `/partners`
- إضافة مؤسسة: `/partners/create`
- تعديل مؤسسة: `/partners/{id}/edit`
- عرض تفاصيل: `/partners/{id}`

## الأمان

- **CSRF Protection**: حماية من هجمات CSRF
- **Validation**: التحقق من صحة جميع البيانات المدخلة
- **Authorization**: التحقق من صلاحيات المستخدم
- **SQL Injection Protection**: استخدام Eloquent ORM

## التطوير المستقبلي

1. **إضافة صور للمؤسسات**
2. **تصدير البيانات إلى Excel/PDF**
3. **إضافة تقارير إحصائية**
4. **إضافة نظام إشعارات**
5. **تحسين واجهة المستخدم**
6. **إضافة API endpoints**

## الدعم

لأي استفسارات أو مشاكل، يرجى التواصل مع فريق التطوير. 