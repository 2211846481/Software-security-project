<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Agreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class DocumentController extends Controller
{
    /**
     * دالة لعرض قائمة بجميع المستندات بناءً على الصلاحيات.
     * المسؤول يرى كل شيء، أما مستخدم القسم والعارض فيرى مستندات قسمه فقط.
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            // ابدأ ببناء استعلام جديد للمستندات مع تحميل العلاقات المطلوبة
            $query = Document::with(['agreement', 'uploadedByUser'])->latest();

            // تطبيق فلتر البحث
            if ($request->has('search') && $request->search != '') {
                $query->where('file_name', 'like', '%' . $request->search . '%');
            }

            // تطبيق شرط الصلاحيات: إذا لم يكن مسؤولاً، قم بالفلترة حسب القسم.
            // هذا المنطق ضروري لضمان أن العارض يرى فقط مستندات قسمه.
            // افتراض أن role 2 يمثل صلاحية المسؤول (admin).
            if ($user->role != 2) {
                $query->whereHas('agreement', function ($q) use ($user) {
                    $q->where('department_id', $user->department_id);
                });
            }

            $documents = $query->paginate(10);
            return view('document.showDocument', compact('documents'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحميل المستندات: ' . $e->getMessage());
        }
    }

    /**
     * دالة لعرض نموذج إضافة مستند جديد.
     * يتم جلب الاتفاقيات بناءً على صلاحية المستخدم.
     */
    public function create()
    {
        $user = Auth::user();
        $agreements = collect(); // Initialize an empty collection

        if ($user) {
            // افتراض أن role 2 يمثل صلاحية المسؤول (admin).
            // افتراض أن role 1 يمثل صلاحية مسؤول القسم (department_manager).
            if ($user->role == 2) {
                // المسؤول يرى كل الاتفاقيات
                // تم تعديل هذا الجزء لجلب الحقول الضرورية فقط
                $agreements = Agreement::select('id', 'title')->get();
            } else {
                // مسؤول القسم أو أي مستخدم آخر يرى فقط الاتفاقيات الخاصة بقسمه
                // يمكن تعديل هذا المنطق بناءً على احتياجات النظام
                if ($user->department_id) {
                    // تم تعديل هذا الجزء لجلب الحقول الضرورية فقط
                    $agreements = Agreement::where('department_id', $user->department_id)->select('id', 'title')->get();
                }
            }
        }
        
        // في هذه الحالة، يمكن أن تمرر المتغيرات 'agreements' و 'user' إلى View
        return view('document.addDocument', compact('agreements', 'user'));
    }

    /**
     * دالة لمعالجة رفع المستندات وتخزينها.
     */
   public function store(Request $request)
{
    // 1. إضافة قواعد التحقق من صحة الملف قبل حفظه
    //    - 'file_content' هو اسم الحقل في النموذج
    //    - 'required' للتأكد من أن المستخدم قد اختار ملفاً
    //    - 'file' للتحقق من أنه ملف
    //    - 'mimes' للتحقق من نوع الملف (PDF, DOC, DOCX)
    //    - 'max' للتحقق من الحجم الأقصى للملف بالكيلوبايت (هنا 10240KB = 10MB)
    $request->validate([
        'agreement_id' => 'required|integer|exists:agreements,id',
        'file_content' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10 ميجا بايت كحد أقصى
        'file_name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
    ]);

    try {
        // 2. قراءة محتوى الملف
        $file = $request->file('file_content');
        $fileContent = file_get_contents($file->getRealPath());

        // 3. حفظ المستند في قاعدة البيانات
        $document = new Document();
        $document->agreement_id = $request->agreement_id;
        $document->file_name = $request->file_name;
        $document->file_content = $fileContent; // محتوى الملف الثنائي
        $document->file_type = $file->getClientMimeType();
        $document->file_size = $file->getSize();
        $document->uploaded_by_user_id = Auth::id(); // استخدام معرف المستخدم المسجل تلقائياً
        $document->description = $request->description;
        $document->save();

        // 4. إعادة التوجيه مع رسالة نجاح
        return redirect()->route('documents.index')->with('success', 'تم رفع المستند بنجاح!');

    } catch (\Exception $e) {
        // 5. في حالة حدوث أي خطأ غير متوقع، إعادة التوجيه مع رسالة خطأ
        return redirect()->back()->with('error', 'حدث خطأ أثناء رفع المستند: ' . $e->getMessage())->withInput();
    }
}
    /**
     * دالة لتحميل الملف الثنائي المخزن في قاعدة البيانات.
     * يمكن للمسؤول ومستخدم القسم والعارض تحميل ملفات قسمه فقط.
     */
   private function getFileExtensionFromMime(string $mimeType): string
    {
        // 🔴🔴🔴 يمكنك إضافة المزيد من أنواع الملفات هنا حسب الحاجة
        $extensions = [
            'application/pdf' => '.pdf',
            'application/msword' => '.doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => '.docx',
            'application/vnd.ms-excel' => '.xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => '.xlsx',
            'image/jpeg' => '.jpeg',
            'image/png' => '.png',
        ];

        // إرجاع الامتداد المناسب، أو سلسلة فارغة إذا لم يتم العثور عليه
        return $extensions[$mimeType] ?? '';
    }

    public function download(Document $document)
    {
        // 🔴🔴🔴 التحقق من الصلاحيات: إذا لم يكن مسؤولاً، تحقق من أن المستند ينتمي إلى قسمه
        $user = Auth::user();
        if ($user->role != 2) {
            if ($document->agreement->department_id !== $user->department_id) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        // التحقق من وجود محتوى الملف في قاعدة البيانات
        if ($document->file_content) {
            // 🔴🔴🔴 الخطوة الجديدة: الحصول على الامتداد من نوع الملف وإضافته لاسم الملف
            // تم تحديث طريقة استدعاء الدالة إلى $this->getFileExtensionFromMime()
            $extension = $this->getFileExtensionFromMime($document->file_type);
            $downloadFileName = $document->file_name . $extension;

            // 🔴🔴🔴 استخدام response()->streamDownload() لتحميل الملف بشكل صحيح
            return response()->streamDownload(function () use ($document) {
                // echo يقوم بإرسال محتوى الملف الثنائي إلى المتصفح كتيار بيانات
                echo $document->file_content;
            }, $downloadFileName, [ // 🔴🔴🔴 استخدام الاسم الجديد مع الامتداد
                'Content-Type' => $document->file_type,
                'Content-Length' => $document->file_size,
                'Content-Disposition' => 'attachment; filename="' . $downloadFileName . '"',
            ]);
        }

        // إذا لم يتم العثور على محتوى الملف
        abort(404, 'File content not found for this document.');
    }
    /**
     * عرض نموذج تعديل مستند معين.
     */
    public function edit(Document $document)
    {
        $user = Auth::user();
        $agreements = collect();

        if ($user) {
            if ($user->role == 2) {
                // المسؤول يرى كل الاتفاقيات
                $agreements = Agreement::all();
            } else {
                // مسؤول القسم أو أي مستخدم آخر يرى فقط اتفاقيات قسمه
                if ($user->department_id) {
                    $agreements = Agreement::where('department_id', $user->department_id)->get();
                }
            }
        }

        return view('document.updateDocument', compact('document', 'agreements'));
    }

    /**
     * تحديث مستند معين في قاعدة البيانات.
     */
    public function update(Request $request, Document $document)
    {
        // تم تعديل قواعد التحقق لتتناسب مع واجهة المستخدم الجديدة ومنطق العمل
        $request->validate([
            'file_name' => 'required|string|max:255',
            'file_content' => 'nullable|file|mimes:pdf,doc,docx|max:20480', // تم إضافة قواعد MIME
            'agreement_id' => 'required|integer|exists:agreements,id', // تم إضافة التحقق من وجود الاتفاقية
            'description' => 'nullable|string',
        ]);

        try {
            $document->file_name = $request->file_name;
            $document->agreement_id = $request->agreement_id;
            // تم حذف حقل uploaded_by_user_id من التحديث لعدم الحاجة إليه
            $document->description = $request->description;

            if ($request->hasFile('file_content')) {
                $fileContent = file_get_contents($request->file('file_content')->getRealPath());
                $document->file_content = $fileContent;
                $document->file_type = $request->file('file_content')->getClientMimeType();
                $document->file_size = $request->file('file_content')->getSize();
            }

            $document->save();

            return redirect()->route('documents.index')->with('success', 'Document updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث المستند: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * دالة لحذف مستند معين.
     */
    public function destroy(Document $document)
    {
        try {
            $document->delete();
            return response()->json(['message' => 'تم حذف المستند بنجاح.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'حدث خطأ أثناء حذف المستند.', 'error' => $e->getMessage()], 500);
        }
    }
}
