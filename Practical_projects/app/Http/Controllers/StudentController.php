<?php

namespace App\Http\Controllers;
use App\Models\Student; // Assuming you have a Student model

use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // أضف هذا السطر للتحقق من uniqueness

class StudentController extends Controller
{

    public function index()
    {
        $students = Student::all(); // Fetch all students from the database
        return view('SALMA', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     * (We won't use this if using modal, but keep it for completeness)
     */
    public function create()
    {
        return view('addstudent'); // هنا ترجع addstudent
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('students')],
            'age' => ['required', 'integer', 'min:5', 'max:100'],
            'registration_number' => ['required', 'string', 'max:255', Rule::unique('students')],
        ]);

        Student::create($validatedData);

        return redirect()->route('salma')->with('success', 'Student added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // يمكنك إضافة منطق عرض تفاصيل الطالب هنا
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // يمكنك إضافة منطق عرض نموذج التعديل هنا
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // يمكنك إضافة منطق تحديث الطالب هنا
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // يمكنك إضافة منطق حذف الطالب هنا
        $student = Student::findOrFail($id);
        $student->delete();
        return redirect()->route('salma')->with('success', 'Student deleted successfully!');
    }
}