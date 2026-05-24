<?php

namespace App\Http\Controllers;

use App\Models\Department; // استيراد موديل القسم
use Illuminate\Http\Request;
use App\Models\User; // استيراد موديل المستخدم للتحقق من التبعيات

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        // بناء الاستعلام (Query Builder)
        $query = Department::withCount('users'); // أضفنا withCount('users') لحساب عدد الموظفين

        // إذا كان هناك مدخل بحث، قم بتصفية النتائج
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        // جلب الأقسام مع الترقيم (pagination)
        $departments = $query->paginate(10); // يمكنك تعديل عدد النتائج لكل صفحة

        // تمرير الأقسام إلى الواجهة وعرضها
        return view('department.departments', compact('departments'));
    }


    /**
     * عرض نموذج إنشاء قسم جديد.
     */
    public function create()
    {
        // إرجاع واجهة نموذج الإضافة/التعديل
        return view('department.add-edit-department');
    }

    /**
     * حفظ قسم جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المرسلة
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name', // الاسم مطلوب وفريد
        ]);

        // إنشاء قسم جديد وحفظه
        Department::create([
            'name' => $request->name,
        ]);

        // إعادة التوجيه إلى صفحة الأقسام مع رسالة نجاح
        return redirect()->route('departments.index')->with('success', 'Department added successfully.');
    }

    /**
     * عرض نموذج تعديل قسم موجود.
     */
    public function edit(string $id)
    {
        // البحث عن القسم المراد تعديله
        $department = Department::findOrFail($id);
        
        // إرجاع واجهة نموذج الإضافة/التعديل مع بيانات القسم
        return view('department.add-edit-department', compact('department'));
    }

    /**
     * تحديث بيانات قسم موجود في قاعدة البيانات.
     */
    public function update(Request $request, string $id)
    {
        // البحث عن القسم المراد تحديثه
        $department = Department::findOrFail($id);

        // التحقق من صحة البيانات المرسلة
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id, // الاسم مطلوب وفريد مع تجاهل القسم الحالي
        ]);
        
        // تحديث بيانات القسم
        $department->name = $request->name;
        $department->save();

        // إعادة التوجيه إلى صفحة الأقسام مع رسالة نجاح
        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * حذف قسم من قاعدة البيانات.
     */
    public function destroy(string $id)
    {
        // البحث عن القسم المراد حذفه
        $department = Department::findOrFail($id);

        // التحقق مما إذا كان هناك مستخدمون مرتبطون بهذا القسم
        if (User::where('department_id', $department->id)->exists()) {
            return redirect()->route('departments.index')->with('error', 'Cannot delete department. There are users associated with it.');
        }

        // حذف القسم
        $department->delete();

        // إعادة التوجيه إلى صفحة الأقسام مع رسالة نجاح
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
