<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Document</title>
</head>
<body>
    @extends('layout.BaseLayout')
    @section('content')

    {{-- ************************************************************ --}}
    {{-- Success and Error messages --}}
    @if(session('success'))
        <div class="kt-alert kt-alert-success mb-4" id="success-alert">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="kt-alert kt-alert-danger mb-4" id="error-alert">
            <span class="text-destructive uppercase">Whoops:</span>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul class="mt-3 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- ************************************************************ --}}

    <div class="mb-5 lg:mb-7.5">
        <div class="kt-container-fluid flex items-center justify-between flex-wrap gap-5">
            <div class="flex items-center flex-wrap gap-1 lg:gap-5">
                <h1 class="font-medium text-lg text-mono">
                    Edit Document
                </h1>
            </div>
        </div>
    </div>

    <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="kt-card pb-2.5">
            <div class="kt-card-header" id="document_edit_form_header">
                <h3 class="kt-card-title">
                    Document Details
                </h3>
            </div>
            <div class="kt-card-content grid gap-5">

                {{-- Actual file upload field (file_content) --}}
                <div class="flex items-center flex-wrap gap-2.5">
                    <label class="kt-form-label max-w-56">
                        Document File
                    </label>
                    <div class="flex items-center flex-wrap grow gap-2.5">
                        <span class="text-sm text-secondary-foreground">
                            Upload new file to replace current one ({{ $document->file_name ?? 'N/A' }} - {{ round($document->file_size / 1024, 2) ?? 'N/A' }} KB)
                        </span>
                        <div class="w-full">
                            <input type="file" name="file_content" id="file_content_input"
                                    accept=".pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .jpg, .jpeg, .png, .gif, .txt"
                                    class="kt-input block w-full text-sm text-gray-500
                                           file:mr-4 file:py-2 file:px-4
                                           file:rounded-full file:border-0
                                           file:text-sm file:font-semibold
                                           file:bg-blue-50 file:text-blue-700
                                           hover:file:bg-blue-100">
                            <p class="mt-1 text-sm text-gray-500" id="file_content_help">
                                اترك فارغاً للإبقاء على الملف الحالي.
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
                        <input class="kt-input" type="text" name="file_name" placeholder="Enter file name" value="{{ old('file_name', $document->file_name) }}" required>
                    </div>
                </div>

                {{-- Agreement field (agreement_id) as a dropdown list --}}
                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            Agreement <span class="text-red-500">*</span>
                        </label>
                        <select class="kt-input" name="agreement_id" required>
                            <option value="">Select an Agreement</option>
                            @foreach ($agreements as $agreement)
                                <option value="{{ $agreement->id }}" @if($document->agreement_id == $agreement->id) selected @endif>
                                    {{ $agreement->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- The uploaded_by_user_id field has been removed as it's automatically set. --}}

                {{-- Description field (description) --}}
                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            Description
                        </label>
                        <textarea class="kt-input" name="description" placeholder="Enter document description" rows="4">{{ old('description', $document->description) }}</textarea>
                    </div>
                </div>

                {{-- Submit button --}}
                <div class="flex justify-end pt-2.5">
                    <button class="kt-btn kt-btn-primary" type="submit">
                        Update Document
                    </button>
                </div>
            </div>
        </div>
    </form>

    @endsection

    {{-- JavaScript for alerts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.getElementById('success-alert');
            const errorAlert = document.getElementById('error-alert');

            if (successAlert || errorAlert) {
                setTimeout(function() {
                    if (successAlert) successAlert.remove();
                    if (errorAlert) errorAlert.remove();
                }, 3000);
            }
        });
    </script>
</body>
</html>
