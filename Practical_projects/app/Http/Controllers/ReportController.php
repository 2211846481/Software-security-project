<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agreement;
use App\Models\Department;
use App\Models\PartnerInstitution;
use App\Models\AgreementType;
use Illuminate\Support\Facades\Auth;
use App\Exports\AgreementsExport; // سنقوم بإنشاء هذا الملف لاحقاً
use Maatwebsite\Excel\Facades\Excel; // يجب إضافة هذه المكتبة
use PDF;
class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 🟢 البدء باستعلام قاعدة البيانات لجميع الاتفاقيات
        $agreements = Agreement::query();
        
        // 🟢 تطبيق شرط التصفية حسب دور المستخدم
        // إذا كان الدور 0 (عارض) أو 1 (موظف قسم)، قم بتصفية الاتفاقيات حسب قسمه
        if ($user->role == 0 || $user->role == 1) { 
            $agreements->where('department_id', $user->department_id);
        }
        
        // 🟢 تطبيق خيارات التصفية بناءً على طلب المستخدم
        // هذا الفلتر سيكون متاحاً فقط للمسؤول (Admin)
        if ($request->has('department_id') && $request->department_id) {
            $agreements->where('department_id', $request->department_id);
        }

        if ($request->has('agreement_type_id') && $request->agreement_type_id) {
            $agreements->where('agreement_type_id', $request->agreement_type_id);
        }

        if ($request->has('status') && $request->status) {
            $agreements->where('status', $request->status);
        }

        if ($request->has('year') && $request->year) {
            $agreements->whereYear('effective_date', $request->year);
        }

        if ($request->has('partner_id') && $request->partner_id) {
            $agreements->whereHas('partners', function ($query) use ($request) {
                $query->where('partner_institutions.id', $request->partner_id);
            });
        }
        
        // 🟢 جلب البيانات اللازمة لملء خيارات التصفية
        $departments = Department::orderBy('name')->get();
        $agreementTypes = AgreementType::orderBy('name')->get();
        $partners = PartnerInstitution::orderBy('name')->get();
        
        // 🟢 جلب النتائج النهائية
        $filteredAgreements = $agreements->get();

        // 🟢 إرسال جميع البيانات إلى الواجهة، بما في ذلك دور المستخدم
        return view('reports.index', compact(
            'filteredAgreements',
            'departments',
            'agreementTypes',
            'partners',
            'user' // 🟢 تم إضافة متغير user هنا
        ));
    }
    public function exportExcel(Request $request)
{
    // 🟢 هنا يجب أن نقوم بتطبيق نفس الفلاتر التي استخدمناها في دالة index
    $agreements = $this->getFilteredAgreements($request);
    
    // 🟢 تصدير الملف بصيغة Excel (xlsx)
    return Excel::download(new AgreementsExport($agreements), 'agreements_report.xlsx');
}

// 🟢 دالة مساعدة لتجنب تكرار الكود
private function getFilteredAgreements(Request $request)
{
    $user = Auth::user();
    $agreements = Agreement::query();

    if ($user->role == 0 || $user->role == 1) { 
        $agreements->where('department_id', $user->department_id);
    }
    
    if ($request->has('department_id') && $request->department_id) {
        $agreements->where('department_id', $request->department_id);
    }

    if ($request->has('agreement_type_id') && $request->agreement_type_id) {
        $agreements->where('agreement_type_id', $request->agreement_type_id);
    }

    if ($request->has('status') && $request->status) {
        $agreements->where('status', $request->status);
    }

    if ($request->has('year') && $request->year) {
        $agreements->whereYear('effective_date', $request->year);
    }

    if ($request->has('partner_id') && $request->partner_id) {
        $agreements->whereHas('partners', function ($query) use ($request) {
            $query->where('partner_institutions.id', $request->partner_id);
        });
    }

    return $agreements->get();
}


public function exportPdf(Request $request)
{
    // 🟢 هنا يجب أن نقوم بتطبيق نفس الفلاتر التي استخدمناها في دالة index
    $filteredAgreements = $this->getFilteredAgreements($request);

    // 🟢 تمرير البيانات إلى واجهة خاصة بملف PDF
    $pdf = PDF::loadView('reports.pdf_template', compact('filteredAgreements'));

    // 🟢 تنزيل الملف بصيغة PDF
    return $pdf->download('agreements_report.pdf');
}
}