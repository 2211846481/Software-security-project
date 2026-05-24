<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents List</title>
</head>
<body>

    @extends('layout.BaseLayout')
    @section('content')

    {{-- ************************************************************ --}}
    {{-- رسائل النجاح/الخطأ من الجلسة (Session Messages) --}}
    @if(session('success'))
        <div class="kt-alert kt-alert-success mb-4" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="kt-alert kt-alert-danger mb-4" id="error-alert">
            <span class="text-destructive uppercase">Warning:</span>
            {{ session('error') }}
        </div>
    @endif
    {{-- ************************************************************ --}}

    <div class="mb-5 lg:mb-7.5">
        <div class="kt-container-fluid flex items-center justify-between flex-wrap gap-5">
            <div class="flex items-center flex-wrap gap-1 lg:gap-5">
                <h1 class="font-medium text-lg text-mono">
                    Documents Management
                </h1>
            </div>
        </div>
    </div>

    <div class="kt-card kt-card-grid min-w-full">
        <div class="kt-card-header py-5 flex-wrap gap-2">
            <h3 class="kt-card-title">
                Documents List
            </h3>
            <div class="flex gap-6">
                {{-- حقل البحث التلقائي --}}
                <div class="kt-input">
                    <i class="ki-filled ki-magnifier"></i>
                    <input placeholder="Search Documents by File Name" type="text" id="auto-search-input" value="{{ request('search') }}">
                </div>
                {{-- زر إضافة ملف جديد - يظهر فقط للمستخدمين ذوي الصلاحية > 0 --}}
                @if(Auth::user()->role > 0)
                <a href="{{ route('documents.create') }}" class="kt-btn kt-btn-primary kt-btn-sm">
                    <span class="kt-btn-icon">
                        <i class="ki-filled ki-add-files"></i>
                    </span>
                    <span class="kt-btn-title">
                        Add New Document
                    </span>
                </a>
                @endif
            </div>
        </div>
        
        <div class="kt-card-content">
            <div class="grid" data-kt-datatable="true" data-kt-datatable-page-size="10">
                <div class="kt-scrollable-x-auto">
                    <table class="kt-table kt-table-border" data-kt-datatable-table="true" id="documents_info_table">
                        <thead>
                            <tr class="bg-accent/50">
                                <th class="text-start font-medium min-w-[250px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">File Name</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th class="text-start font-medium min-w-[120px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">File</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th class="text-start font-medium min-w-[103px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Agreement ID</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th class="text-start font-medium min-w-[103px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Uploaded By</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th class="text-start font-medium min-w-[250px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Description</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th class="text-start font-medium min-w-[103px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">File Size</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th class="text-start font-medium min-w-[103px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">File Type</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                {{-- عمود الإجراءات بالكامل يتم إخفاؤه إذا كانت الصلاحية > 0 --}}
                                @if(Auth::user()->role > 0)
                                <th class="text-center w-[60px]">
                                    Actions
                                </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            {{-- تحديد قيمة colspan بناءً على صلاحية المستخدم --}}
                            @php
                                $colspan = Auth::user()->role > 0 ? 8 : 7;
                            @endphp
                            
                            @forelse($documents as $document)
                            <tr>
                                <td class="text-start">
                                    <div class="flex items-center gap-2.5">
                                        <div class="rounded-full size-9 shrink-0 bg-primary/20 flex items-center justify-center text-primary font-semibold text-sm">
                                            {{ strtoupper(substr($document->file_name, 0, 1)) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <a class="text-sm font-semibold text-mono hover:text-primary mb-px" href="#">
                                                {{ $document->file_name }}
                                            </a>
                                            
                                        </div>
                                    </div>
                                </td>
                                <td class="text-start">
                                    {{-- تم تعديل هذا الرابط ليعمل على تنزيل الملف --}}
                                    <a href="{{ route('documents.download', ['document' => $document->id]) }}" class="kt-btn kt-btn-outline-link kt-btn-sm" title="Download">
                                        <span class="kt-btn-title">Download File</span>
                                    </a>
                                </td>
                                <td class="text-start">
                                    <span class="text-sm font-normal text-secondary-foreground">
                                        {{$document->agreement->title }}
                                    </span>
                                </td>
                                <td class="text-start">
                                    <span class="text-sm font-normal text-secondary-foreground">
                                        {{ $document->uploadedByUser->name }}
                                    </span>
                                </td>
                                <td class="text-start">
                                    <span class="text-sm font-normal text-secondary-foreground">
                                        {{ $document->description ?? 'No description' }}
                                    </span>
                                </td>
                                <td class="text-start">
                                    <span class="text-sm font-normal text-secondary-foreground">
                                        {{ round($document->file_size / 1024, 2) }} KB
                                    </span>
                                </td>
                                <td class="text-start">
                                    <span class="text-sm font-normal text-secondary-foreground">
                                        {{ $document->file_type }}
                                    </span>
                                </td>
                                {{-- خلية الإجراءات يتم إخفاؤها مع العمود --}}
                                @if(Auth::user()->role > 0)
                                <td class="text-center">
                                    <div class="flex justify-center gap-2">
                                        {{-- زر التعديل --}}
                                        <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-secondary" href="{{ route('documents.edit', $document->id) }}" title="Edit">
                                            <i class="ki-filled ki-pencil"></i>
                                        </a>
                                        {{-- زر الحذف --}}
                                        <button type="button" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-danger" onclick="confirmAndDelete({{ $document->id }})" title="Remove">
                                            <i class="ki-filled ki-trash"></i>
                                        </button>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $colspan }}" class="text-center text-secondary-foreground py-4">No documents found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- الترقيم --}}
        <div class="kt-card-footer justify-center">
            @if(isset($documents) && $documents)
                {{ $documents->appends(['search' => request('search')])->links() }}
            @endif
        </div>
    </div>

    {{-- ************************************************************ --}}
    {{-- كود JavaScript لوظائف التنبيه والحذف والبحث --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // مؤقت رسائل التنبيه من الجلسة
            const successAlert = document.getElementById('success-alert');
            const errorAlert = document.getElementById('error-alert');

            if (successAlert || errorAlert) {
                setTimeout(function() {
                    successAlert?.remove();
                    errorAlert?.remove();
                }, 3000);
            }

            // دالة لعرض الرسائل الجميلة من JavaScript
            function displayJsMessage(type, message) {
                const container = document.getElementById('js-message-container');
                if (!container) return; // تأكد من وجود الحاوية

                container.innerHTML = ''; // مسح أي رسائل سابقة

                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} px-4 py-3 rounded relative mb-4`;
                
                if (type === 'success') {
                    alertDiv.classList.add('bg-green-100', 'border', 'border-green-400', 'text-green-700');
                } else if (type === 'error') {
                    alertDiv.classList.add('bg-red-100', 'border', 'border-red-400', 'text-red-700');
                }

                alertDiv.innerHTML = `<strong class="font-bold">${type === 'success' ? 'Success!' : 'Error!'}</strong>
                                     <span class="block sm:inline">${message}</span>`;
                
                container.appendChild(alertDiv);

                // إزالة الرسالة بعد 5 ثوانٍ
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }

            // دالة الحذف
            window.confirmAndDelete = function(documentId) {
                // استخدام نافذة تأكيد مخصصة أو نمط بديل بدلاً من `confirm()`
                const userConfirmed = window.confirm('هل أنت متأكد أنك تريد حذف هذا المستند؟');
                if (userConfirmed) {
                    fetch('/documents/' + documentId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        }
                        return response.json().then(err => { throw new Error(err.message || 'Something went wrong on the server.'); });
                    })
                    .then(data => {
                        displayJsMessage('success', data.message || 'تم حذف المستند بنجاح!');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        displayJsMessage('error', 'حدث خطأ أثناء حذف المستند: ' + error.message);
                    });
                }
            }

            // كود البحث التلقائي
            const searchInput = document.getElementById('auto-search-input');
            let timeout = null;

            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(function() {
                        const searchValue = searchInput.value;
                        const url = new URL(window.location.href);
                        if (searchValue.trim() !== '') {
                            url.searchParams.set('search', searchValue);
                        } else {
                            url.searchParams.delete('search');
                        }
                        window.location.href = url.href;
                    }, 500); // 500ms تأخير
                });
            }
        });
    </script>
    {{-- ************************************************************ --}}
    @endsection

</body>
</html>
