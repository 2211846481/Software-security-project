<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload New Document</title>
</head>
<body>
    {{-- Assuming 'layout.BaseLayout' exists --}}
    @extends('layout.BaseLayout')
    @section('content')

    {{-- Success and Error messages --}}
    @if(session('success'))
        <div class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul class="mt-3 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="kt-card pb-2.5">
            <div class="kt-card-header" id="document_upload_form_header">
                <h3 class="kt-card-title">
                    Upload New Document
                </h3>
            </div>
            <div class="kt-card-content grid gap-5">

                {{-- Actual file upload field (file_content) --}}
                <div class="flex items-center flex-wrap gap-2.5">
                    <label class="kt-form-label max-w-56">
                        Document File <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center flex-wrap grow gap-2.5">
                        <span class="text-sm text-secondary-foreground">
                            Upload your document (PDF, DOCX, JPEG, PNG, etc.)
                        </span>
                        <div class="w-full">
                            <input type="file" name="file_content" id="file_content_input"
                                   accept=".pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .jpg, .jpeg, .png, .gif, .txt"
                                   class="kt-input block w-full text-sm text-gray-500
                                           file:mr-4 file:py-2 file:px-4
                                           file:rounded-full file:border-0
                                           file:text-sm file:font-semibold
                                           file:bg-blue-50 file:text-blue-700
                                           hover:file:bg-blue-100"
                                   required>
                            <p class="mt-1 text-sm text-gray-500" id="file_content_help">
                                يرجى اختيار ملف المستند المراد رفعه.
                                <br> {{-- إضافة سطر جديد للملاحظة --}}
                                <span class="text-xs text-blue-600">
                                    (الحد الأقصى لحجم الملف: 20 ميجابايت)
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- File Name field (file_name) --}}
                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            File Name <span class="text-red-500">*</span>
                        </label>
                        <input class="kt-input" type="text" name="file_name" placeholder="Enter file name" required>
                    </div>
                </div>

                {{-- Agreement ID field (agreement_id) as a dropdown list --}}
                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            Agreement <span class="text-red-500">*</span>
                        </label>
                        <select class="kt-input" name="agreement_id" required>
                            <option value="">Select an Agreement</option>
                            @foreach ($agreements as $agreement)
                                <option value="{{ $agreement->id }}">{{ $agreement->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- The uploaded_by_user_id field has been removed as it will be set automatically by the controller. --}}
                
                {{-- Description field (description) --}}
                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            Description
                        </label>
                        <textarea class="kt-input" name="description" placeholder="Enter document description" rows="4"></textarea>
                    </div>
                </div>

                {{-- Submit button --}}
                <div class="flex justify-end pt-2.5">
                    <button class="kt-btn kt-btn-primary" type="submit">
                        Save Document
                    </button>
                </div>
            </div>
        </div>
    </form>
    @endsection
</body>
</html>
