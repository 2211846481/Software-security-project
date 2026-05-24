<?php

namespace App\Http\Controllers;

use App\Models\User; // تأكد من استيراد موديل User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Department; // Using the Department model

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('department');
    
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('email', 'like', $search . '%');
                  
        }
    
        $employees = $query->paginate(10);
        
        
        // إذا لم يكن الطلب Ajax، اعرض الواجهة الكاملة
        return view('user.showUser', compact('employees'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // جلب جميع الأقسام من قاعدة البيانات
        $departments = Department::all();
        
        // تمرير الأقسام إلى واجهة العرض
        return view('user.adduser', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // منطق حفظ موظف جديد
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|integer|in:0,1,2',
            'department_id' => 'required|exists:departments,id', // أضف هذا السطر للتحقق من وجود القسم
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'department_id' => $request->department_id, // أضف هذا السطر لحفظ القسم
        ]);

        return redirect()->route('employees.list')->with('success', 'User added successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = User::findOrFail($id);
        return view('view_employee', compact('employee'));
    }


     /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // جلب بيانات الموظف بناءً على الـ ID مع تحميل علاقته بالقسم
        $employee = User::with('department')->findOrFail($id); 
        // جلب جميع الأقسام من قاعدة البيانات
        $departments = Department::all();
        
        // تمرير بيانات الموظف وجميع الأقسام إلى الواجهة
        return view('user.editeuser', compact('employee', 'departments')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = User::findOrFail($id); // جلب الموظف المراد تحديثه

        // Check if the user is trying to change the role of the last admin
        if ($employee->role == 2) {
            $adminCount = User::where('role', 2)->count();
            if ($adminCount === 1 && $request->role != 2) {
                return redirect()->back()->withErrors(['role' => 'The last administrator in the system cannot be deleted. At least one administrator must remain.']);
            }
        }

        // قواعد التحقق من الصحة
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'role' => 'required|integer|in:0,1,2',
            'department_id' => 'required|exists:departments,id', // أضف هذا السطر للتحقق من وجود القسم
        ];

        // إذا تم إدخال كلمة مرور جديدة، أضف قواعد التحقق الخاصة بها
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // تحديث البيانات
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->role = $request->role;
        $employee->department_id = $request->department_id; // أضف هذا السطر لحفظ القسم

        // إذا تم إدخال كلمة مرور جديدة، قم بتشفيرها وتحديثها
        if ($request->filled('password')) {
            $employee->password = Hash::make($request->password);
        }

        $employee->save();

        return redirect()->route('employees.list')->with('success', 'User data updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = User::findOrFail($id);

        // Check if the user trying to be deleted is the last admin
        if ($employee->role == 2) { // If the user is an admin
            $adminCount = User::where('role', 2)->count();
            if ($adminCount === 1) {
                // If this is the only admin, prevent deletion
                return redirect()->route('employees.index')->with('error', 'The last administrator in the system cannot be deleted. At least one administrator must remain.');
            }
        }

        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'The User has been successfully deleted.');
    }
}
