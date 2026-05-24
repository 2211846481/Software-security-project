<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Partner Institutions</title>
</head>
<body>
@extends('layout.BaseLayout')

@section('content')

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="kt-alert kt-alert-success mb-4" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="kt-alert kt-alert-danger mb-4" id="error-alert">
            <span class="text-destructive uppercase">
                Warning:
            </span>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="kt-alert kt-alert-danger mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-5 lg:mb-7.5">
        <div class="kt-container-fluid flex items-center justify-between flex-wrap gap-5">
            <h1 class="font-medium text-lg text-mono">
                Partner Institutions Management
            </h1>
        </div>
    </div>

    <div class="kt-card kt-card-grid min-w-full">
        <div class="kt-card-header py-5 flex-wrap gap-2">
            <h3 class="kt-card-title">
                Partner Institutions List
            </h3>
            <div class="flex gap-6">
                {{-- Auto-search input --}}
                <div class="kt-input">
                    <i class="ki-filled ki-magnifier"></i>
                    <input placeholder="Search by name, country, or sector" type="text" id="auto-search-input" value="{{ request('search') }}">
                </div>
                {{-- Add new partner button - For Admin only --}}
                @if(auth()->user()->role == 2 || auth()->user()->role == 1)
                    <a class="kt-btn kt-btn-primary kt-btn-sm" href="{{ route('partners.create') }}">
                        <span class="kt-btn-icon">
                            <i class="ki-filled ki-add-files"></i>
                        </span>
                        <span class="kt-btn-title">
                            Add New Partner Institution
                        </span>
                    </a>
                @endif
            </div>
        </div>
        <div class="kt-card-content">
            <div class="grid" data-kt-datatable="true">
                <div class="kt-scrollable-x-auto">
                    <table class="kt-table kt-table-border" data-kt-datatable-table="true" id="partners-table">
                        <thead>
                            <tr class="bg-accent/50">
                                <th class="text-start font-medium min-w-[250px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Institution</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th class="text-start font-medium min-w-[120px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Country</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th class="text-start font-medium min-w-[150px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Email</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th class="text-start font-medium min-w-[150px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Website</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th class="text-start font-medium min-w-[150px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Sector</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                {{-- Agreements count column - Admin only --}}
                                @if(auth()->user()->role == 2)
                                    <th class="text-start font-medium min-w-[150px]">
                                        <span class="kt-table-col">
                                            <span class="kt-table-col-label">Agreements Count</span>
                                            <span class="kt-table-col-sort"></span>
                                        </span>
                                    </th>
                                @endif
                                {{-- Actions column - Admin and Dept. Managers only --}}
                                @if(auth()->user()->role == 2 || auth()->user()->role == 1)
                                    <th class="text-center w-[60px]">
                                        Actions
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            @forelse ($partners as $partner)
                                <tr>
                                    <td class="text-start">
                                        <div class="flex items-center grow gap-2.5">
                                            <div class="rounded-full size-9 shrink-0 bg-primary/20 flex items-center justify-center text-primary font-semibold text-sm">
                                                {{ strtoupper(substr($partner->name, 0, 1)) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <a class="text-sm font-semibold text-mono hover:text-primary mb-px" href="#">
                                                    {{ $partner->name }}
                                                </a>
                                                <span class="text-xs font-normal text-secondary-foreground">
                                                    {{ $partner->contact_name }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        <span class="text-sm font-normal text-secondary-foreground">
                                            {{ $partner->country }}
                                        </span>
                                    </td>
                                    <td class="text-start">
                                        <span class="text-sm font-normal text-secondary-foreground">
                                            {{ $partner->contact_email }}
                                        </span>
                                    </td>
                                    <td class="text-start">
                                        <span class="text-sm font-normal text-secondary-foreground">
                                            <a href="{{ $partner->website }}" target="_blank" class="text-blue-600 hover:underline">{{ $partner->website }}</a>
                                        </span>
                                    </td>
                                    <td class="text-start">
                                        <span class="kt-badge kt-badge-sm kt-badge-outline">
                                            {{ $partner->sector }}
                                        </span>
                                    </td>
                                    {{-- Agreements count cell - Admin only --}}
                                    @if(auth()->user()->role == 2)
                                        <td class="text-start">
                                            <span class="text-sm font-normal text-secondary-foreground">
                                                {{ $partner->agreements_count }}
                                            </span>
                                        </td>
                                    @endif
                                    {{-- Actions buttons --}}
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            {{-- Edit button - Admin and Dept. Managers --}}
                                            @if(auth()->user()->role == 2 || auth()->user()->role == 1)
                                                <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-secondary" href="{{ route('partners.edit', $partner->id) }}" title="Edit">
                                                    <i class="ki-filled ki-pencil"></i>
                                                </a>
                                            @endif
                                            
                                            {{-- Delete button - Admin only --}}
                                            @if(auth()->user()->role == 2)
                                                <form action="{{ route('partners.destroy', $partner->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-danger" onclick="return confirm('Are you sure you want to delete this partner institution?');" title="Delete">
                                                        <i class="ki-filled ki-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- Adjust colspan based on user role --}}
                                    <td colspan="{{ auth()->user()->role == 2 ? '7' : '6' }}" class="text-center text-secondary-foreground py-4">No partner institutions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="kt-card-footer justify-center">
            {{-- Check for and apply pagination links --}}
            @if(isset($partners) && $partners->lastPage() > 1)
                {{ $partners->appends(['search' => request('search')])->links() }}
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Alert message timeout
            const successAlert = document.getElementById('success-alert');
            const errorAlert = document.getElementById('error-alert');
            if (successAlert || errorAlert) {
                setTimeout(function() {
                    if (successAlert) successAlert.remove();
                    if (errorAlert) errorAlert.remove();
                }, 3000);
            }

            // Auto-search code
            const searchInput = document.getElementById('auto-search-input');
            let timeout = null;
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
                }, 500); // 500ms delay after typing stops
            });
        });
    </script>

@endsection
</body>
</html>