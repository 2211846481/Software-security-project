@extends('layout.BaseLayout')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }
    .kt-card {
        background-color: #ffffff;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        padding: 2rem;
    }
    .kt-card-header {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .kt-card-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #212529;
    }
    .kt-btn {
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out, transform 0.1s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .kt-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .kt-btn-primary {
        background-color: #007bff;
        color: #ffffff;
    }
    .kt-btn-primary:hover {
        background-color: #0056b3;
    }
    .kt-btn-secondary {
        background-color: #e9ecef;
        color: #495057;
    }
    .kt-btn-secondary:hover {
        background-color: #dee2e6;
    }
    .kt-alert {
        padding: 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }
    .kt-alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .kt-alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .kt-details-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 1.5rem 1rem;
    }
    .kt-details-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
        text-align: left;
    }
    .kt-details-value {
        font-size: 1rem;
        color: #212529;
        font-weight: 600;
        text-align: left;
    }
    .kt-document-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #e9ecef;
    }
    .kt-document-item:last-child {
        border-bottom: none;
    }
    .kt-file-icon-wrapper {
        background-color: #f1f3f5;
        border-radius: 0.5rem;
        padding: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .kt-file-icon {
        width: 1.5rem;
        height: 1.5rem;
        color: #495057;
    }
    @media (max-width: 768px) {
        .kt-details-grid {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        .kt-details-label {
            margin-bottom: 0;
        }
    }
</style>

{{-- Success and Error Messages --}}
@if(session('success'))
    <div class="kt-alert kt-alert-success mb-4" id="success-alert">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="kt-alert kt-alert-danger mb-4" id="error-alert">
        <span class="font-bold">Warning:</span>
        {{ session('error') }}
    </div>
@endif

<div class="kt-container-fluid p-4 md:p-8 lg:p-12 max-w-7xl mx-auto">

    <div class="mb-8 flex items-center justify-between flex-wrap gap-4">
        <h1 class="kt-card-title">Agreement Details</h1>
        <div>
            <button type="button" class="kt-btn kt-btn-primary" onclick="window.print()">
                Print Agreement
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
            </button>
        </div>
    </div>

    {{-- Agreement Information Card --}}
    <div class="kt-card mb-8">
        <div class="kt-card-header">
            <h3 class="kt-card-title text-xl font-semibold">Agreement Information</h3>
        </div>
        <div class="kt-card-content">
            <div class="kt-details-grid">
                {{-- Agreement Title --}}
                <div class="kt-details-label">Agreement Title:</div>
                <div class="kt-details-value">{{ $agreement->title ?? 'Data Error' }}</div>

                {{-- Responsible Department --}}
                <div class="kt-details-label">Responsible Department:</div>
                <div class="kt-details-value">{{ $agreement->department->name ?? 'Not specified' }}</div>

                {{-- Effective Date --}}
                <div class="kt-details-label">Effective Date:</div>
                <div class="kt-details-value">{{ $agreement->effective_date?->format('Y-m-d') ?? 'Data Error' }}</div>

                {{-- Expiry Date --}}
                <div class="kt-details-label">Expiry Date:</div>
                <div class="kt-details-value">{{ $agreement->expiry_date?->format('Y-m-d') ?? 'Data Error' }}</div>

                {{-- Reference Code --}}
                <div class="kt-details-label">Reference Code:</div>
                <div class="kt-details-value">{{ $agreement->reference_code ?? 'Data Error' }}</div>

                {{-- Status --}}
                <div class="kt-details-label">Status:</div>
                <div class="kt-details-value">
                    @if ($agreement->status == 1)
                        Active
                    @elseif ($agreement->status == 0)
                        Expired
                    @elseif ($agreement->status == 2)
                        Draft
                    @else
                        Data Error
                    @endif
                </div>

                {{-- Signing Date --}}
                <div class="kt-details-label">Signing Date:</div>
                <div class="kt-details-value">{{ $agreement->signing_date?->format('Y-m-d') ?? 'Data Error' }}</div>

                {{-- Agreement Type --}}
                <div class="kt-details-label">Agreement Type:</div>
                <div class="kt-details-value">{{ $agreement->agreementType->name ?? 'Not specified' }}</div>

                {{-- Description --}}
                <div class="kt-details-label">Description:</div>
                <div class="kt-details-value">{{ $agreement->description ?? 'No description available.' }}</div>

                {{-- Partner Institutions --}}
                <div class="kt-details-label">Partner Institutions:</div>
                <div class="kt-details-value">
                    @if($agreement->partners->count() > 0)
                        <ul class="list-disc list-inside">
                            @foreach($agreement->partners as $partner)
                                <li>{{ $partner->name }}</li>
                            @endforeach
                        </ul>
                    @else
                        <span class="text-gray-500">No partner institutions assigned.</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Attached Documents Card --}}
    <div class="kt-card">
        <div class="kt-card-header">
            <h3 class="kt-card-title text-xl font-semibold">Attached Documents</h3>
        </div>
        <div class="kt-card-content">
            @forelse($agreement->documents as $document)
                <div class="kt-document-item">
                    <div class="flex items-center gap-4">
                        <div class="kt-file-icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="kt-file-icon">
                                <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-semibold text-lg text-gray-800">{{ $document->file_name }}</span>
                            <span class="text-sm text-gray-500">{{ number_format($document->file_size / 1024, 2) }} KB</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('documents.download', ['document' => $document->id]) }}" class="kt-btn kt-btn-secondary" title="Download">
                        Download File
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                    </a>
                </div>
            @empty
                <div class="text-center text-gray-500 py-4">
                    No documents attached to this agreement.
                </div>
            @endforelse
        </div>
    </div>
    
</div>
@endsection
