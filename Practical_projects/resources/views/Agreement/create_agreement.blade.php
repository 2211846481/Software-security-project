{{-- File: resources/views/agreements/create_agreement.blade.php --}}

@extends('layout.BaseLayout')

@section('content')
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Agreement</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Tom Select CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'kt-card': '#ffffff',
                        'kt-card-header': '#f9fafb',
                        'kt-card-title': '#1f2937',
                        'kt-input': '#e5e7eb',
                        'kt-btn-primary': '#2563eb',
                        'kt-btn-secondary': '#e5e7eb',
                        'destructive': '#dc2626',
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
        .kt-card {
            background-color: var(--kt-card);
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 2rem;
            width: 100%;
            max-width: 96rem;
            direction: ltr;
        }
        .kt-card-header {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        .kt-card-title {
            font-size: 1.5rem;
            font-weight: 600;
        }
        .kt-form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #4b5563;
        }
        .kt-input {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            background-color: #ffffff;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            width: 100%;
            text-align: left;
        }
        .kt-input::placeholder {
            text-align: left;
        }
        .kt-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }
        .kt-btn {
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.15s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .kt-btn-primary {
            background-color: #2563eb;
            color: #ffffff;
        }
        .kt-btn-primary:hover {
            background-color: #1d4ed8;
        }
        .text-destructive {
            color: #dc2626;
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* تعديل أنماط Tom Select لتتناسب مع التصميم الحالي */
        .ts-wrapper {
            width: 100%;
        }
        .ts-control {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            background-color: #ffffff !important;
            padding: 0.25rem 0.75rem !important;
        }
        .ts-control.focus {
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2) !important;
            outline: none !important;
        }
        .item {
            background-color: #e5e7eb;
            color: #1f2937;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
            margin: 0 0.25rem 0.25rem 0;
            display: inline-flex;
            align-items: center;
        }
        .item .remove {
            color: #9ca3af;
            font-size: 1rem;
            margin-left: 0.25rem;
            cursor: pointer;
        }
        .dropdown-content {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="flex justify-center items-center min-h-screen p-4">

    <div class="kt-card max-w-4xl mx-auto">
        <div class="kt-card-header" id="agreement_settings">
            <h3 class="kt-card-title">
                Create New Agreement ({{ $userRole == 2 ? 'Admin' : 'Department Manager' }})
            </h3>
        </div>

        <div id="validation-errors" class="hidden bg-destructive/10 border-s-4 border-destructive text-red-700 p-4 mb-4 fade-in text-left" role="alert">
            <p class="font-bold">Please correct the following errors:</p>
            <ul id="error-list" class="mt-1 list-disc list-inside"></ul>
        </div>

        <div id="success-message" class="hidden bg-green-100 border-s-4 border-green-500 text-green-700 p-4 mb-4 fade-in text-left" role="alert">
            <p class="font-bold">Form submitted successfully!</p>
        </div>
        
        <form action="{{ route('agreements.store') }}" method="POST" id="agreement-form" novalidate class="kt-card-content grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @csrf
            
            <div class="w-full">
                <div class="flex flex-col gap-2.5">
                    <label for="title" class="kt-form-label flex items-center gap-1">Agreement Title <span class="text-destructive">*</span></label>
                    <input type="text" name="title" id="title" class="kt-input" placeholder="Enter agreement title" required>
                </div>
            </div>

            <div class="w-full">
                <div class="flex flex-col gap-2.5">
                    <label for="reference_code" class="kt-form-label flex items-center gap-1">Reference Code <span class="text-destructive">*</span></label>
                    <input type="text" name="reference_code" id="reference_code" class="kt-input" placeholder="Enter reference code" required>
                </div>
            </div>

            <div class="w-full">
                <div class="flex flex-col gap-2.5">
                    <label for="agreement_type_id" class="kt-form-label flex items-center gap-1">Agreement Type <span class="text-destructive">*</span></label>
                    <select name="agreement_type_id" id="agreement_type_id" class="kt-input" required>
                        <option value="">-- Select agreement type --</option>
                        @foreach ($agreementTypes as $agreementType)
                        <option value="{{ $agreementType->id }}">{{ $agreementType->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            @if ($userRole === 2)
            <div class="w-full" id="department-field">
                <div class="flex flex-col gap-2.5">
                    <label for="department_id" class="kt-form-label flex items-center gap-1">Responsible Department <span class="text-destructive">*</span></label>
                    <select name="department_id" id="department_id" class="kt-input" required>
                        <option value="">-- Select department --</option>
                        @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @else
            <input type="hidden" name="department_id" id="department_id" value="{{ auth()->user()->department_id }}">
            @endif

            <div class="w-full">
                <div class="flex flex-col gap-2.5">
                    <label for="signing_date" class="kt-form-label flex items-center gap-1">Signing Date <span class="text-destructive">*</span></label>
                    <input type="date" name="signing_date" id="signing_date" class="kt-input" required>
                </div>
            </div>

            <div class="w-full">
                <div class="flex flex-col gap-2.5">
                    <label for="effective_date" class="kt-form-label flex items-center gap-1">Effective Date <span class="text-destructive">*</span></label>
                    <input type="date" name="effective_date" id="effective_date" class="kt-input" required>
                </div>
            </div>

            <div class="w-full">
                <div class="flex flex-col gap-2.5">
                    <label for="expiry_date" class="kt-form-label flex items-center gap-1">Expiry Date <span class="text-destructive">*</span></label>
                    <input type="date" name="expiry_date" id="expiry_date" class="kt-input" required>
                </div>
            </div>
            
            <div class="w-full">
                <div class="flex flex-col gap-2.5">
                    <label for="partner_institutions_select" class="kt-form-label flex items-center gap-1">Partner Institutions</label>
                    <select name="partner_institutions[]" id="partner_institutions_select" multiple class="tom-select">
                        <option value="">-- Select one or more partners --</option>
                        @foreach ($partners as $partner)
                        <option value="{{ $partner->id }}">{{ $partner->name }} ({{ $partner->country }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="w-full md:col-span-2 lg:col-span-3">
                <div class="flex flex-col gap-2.5">
                    <label for="description" class="kt-form-label flex items-center gap-1">Agreement Description <span class="text-destructive">*</span></label>
                    <textarea name="description" id="description" rows="5" class="kt-input text-left" placeholder="Enter agreement description" required></textarea>
                </div>
            </div>

            <div class="flex justify-end pt-2.5 md:col-span-2 lg:col-span-3">
                <button type="submit" class="kt-btn kt-btn-primary">
                    Save Agreement
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('agreement-form');
            const validationErrorsDiv = document.getElementById('validation-errors');
            const errorList = document.getElementById('error-list');
            const successMessageDiv = document.getElementById('success-message');

            // Initialize Tom Select for partner institutions
            new TomSelect("#partner_institutions_select", {
                plugins: ['remove_button'],
                persist: false,
                create: false
            });

            function showFieldError(element, message) {
                const parentDiv = element.closest('.w-full');
                if (!parentDiv) return;
                const existingError = parentDiv.querySelector('.error-message');
                if (existingError) existingError.remove();
                element.classList.add('border-destructive');
                element.classList.add('focus:border-red-500');
                const errorElement = document.createElement('span');
                errorElement.classList.add('text-destructive', 'text-sm', 'mt-1', 'block', 'text-left', 'error-message');
                errorElement.textContent = message;
                parentDiv.appendChild(errorElement);
            }

            function clearFieldError(element) {
                const parentDiv = element.closest('.w-full');
                if (!parentDiv) return;
                element.classList.remove('border-destructive');
                element.classList.remove('focus:border-red-500');
                const errorElement = parentDiv.querySelector('.error-message');
                if (errorElement) errorElement.remove();
            }
            
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                let formIsValid = true;
                
                form.querySelectorAll('input, select, textarea').forEach(input => clearFieldError(input));
                validationErrorsDiv.classList.add('hidden');
                errorList.innerHTML = '';
                successMessageDiv.classList.add('hidden');

                const requiredFields = form.querySelectorAll('[required]:not([disabled])');
                requiredFields.forEach(field => {
                    if (field.value.trim() === '') {
                        showFieldError(field, 'This field is required.');
                        formIsValid = false;
                        const labelText = field.labels[0] ? field.labels[0].textContent.replace('*', '').trim() : field.name;
                        errorList.innerHTML += `<li>${labelText} is required.</li>`;
                    }
                });

                const signingDateInput = form.signing_date;
                const effectiveDateInput = form.effective_date;
                const expiryDateInput = form.expiry_date;
                
                const signingDate = new Date(signingDateInput.value);
                const effectiveDate = new Date(effectiveDateInput.value);
                const expiryDate = new Date(expiryDateInput.value);

                if (effectiveDateInput.value && signingDateInput.value && effectiveDate < signingDate) {
                    showFieldError(effectiveDateInput, 'Effective date cannot be before signing date.');
                    formIsValid = false;
                    errorList.innerHTML += '<li>Effective date cannot be before signing date.</li>';
                }

                if (expiryDateInput.value && effectiveDateInput.value && expiryDate < effectiveDate) {
                    showFieldError(expiryDateInput, 'Expiry date cannot be before effective date.');
                    formIsValid = false;
                    errorList.innerHTML += '<li>Expiry date cannot be before effective date.</li>';
                }

                if (!formIsValid) {
                    validationErrorsDiv.classList.remove('hidden');
                    const firstInvalidField = form.querySelector('.border-destructive');
                    if (firstInvalidField) {
                        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                } else {
                    form.submit();
                }
            });

            form.querySelectorAll('input:not([disabled]), select:not([disabled]), textarea:not([disabled])').forEach(field => {
                field.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && this.value.trim() === '') {
                        showFieldError(this, 'This field is required.');
                    } else {
                        clearFieldError(this);
                    }
                });
            });
            
            const dateInputs = [form.signing_date, form.effective_date, form.expiry_date];
            dateInputs.forEach(input => {
                input.addEventListener('change', () => {
                    const signingDate = new Date(form.signing_date.value);
                    const effectiveDate = new Date(form.effective_date.value);
                    const expiryDate = new Date(form.expiry_date.value);

                    clearFieldError(form.effective_date);
                    clearFieldError(form.expiry_date);

                    if (form.effective_date.value && form.signing_date.value && effectiveDate < signingDate) {
                        showFieldError(form.effective_date, 'Effective date cannot be before signing date.');
                    }
                    if (form.expiry_date.value && form.effective_date.value && expiryDate < effectiveDate) {
                        showFieldError(form.expiry_date, 'Expiry date cannot be before effective date.');
                    }
                });
            });
        });
    </script>
</body>
</html>
@endsection