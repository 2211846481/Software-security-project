@extends('layout.BaseLayout')

@section('content')
<style>
    /* Custom styles for badges */
    .kt-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        line-height: 1;
        text-transform: capitalize;
    }
    .kt-badge-outline {
        border-width: 1px;
    }
    .kt-badge-success {
        color: #16a34a;
        background-color: #dcfce7;
        border-color: #86efac;
    }
    .kt-badge-warning {
        color: #f59e0b;
        background-color: #fffbeb;
        border-color: #fcd34d;
    }
    .kt-badge-danger {
        color: #dc2626;
        background-color: #fee2e2;
        border-color: #fca5a5;
    }
</style>

<div class="kt-container-fluid flex flex-col h-full px-4 md:px-8 lg:px-12 w-full">

    {{-- رسائل النجاح/الخطأ --}}
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
                Agreement Management
            </h1>

            <div class="flex items-center gap-5">
                {{-- زر إضافة اتفاقية جديدة (للمدراء فقط) --}}
                                @if (isset($userRole) && ($userRole == 2 || $userRole == 1))
                    <a href="{{ route('agreements.create') }}" class="kt-btn kt-btn-primary kt-btn-sm">
                        <span class="kt-btn-icon">
                            <i class="ki-filled ki-add-files"></i>
                        </span>
                        <span class="kt-btn-title">
                            Add New Agreement
                        </span>
                    </a>
                @endif

                {{-- 🟢 هذا هو زر التقارير الجديد 🟢 --}}
                <a href="{{ route('reports.index') }}" class="kt-btn kt-btn-primary kt-btn-sm">
                    <span class="kt-btn-icon">
                        <i class="ki-filled ki-file"></i>
                    </span>
                    <span class="kt-btn-title">
                        Reports
                    </span>
                </a>
                
                
                {{-- Dropdown menu for sorting options --}}
                <div class="relative inline-block text-left z-20">
                    <div>
                        <button type="button" class="kt-btn kt-btn-secondary kt-btn-sm" id="sort-menu-button" aria-expanded="true" aria-haspopup="true">
                            <span class="kt-btn-icon">
                                <i class="ki-filled ki-sort-down"></i>
                            </span>
                            <span class="kt-btn-title">
                                Sorting
                            </span>
                        </button>
                    </div>
                    <div id="sort-menu" class="origin-top-right absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="sort-menu-button" tabindex="-1">
                        <div class="py-1" role="none">
                            <a href="#" class="sort-link text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" data-sort="start_date" data-order="asc" role="menuitem" tabindex="-1">Start Date (Asc)</a>
                            <a href="#" class="sort-link text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" data-sort="start_date" data-order="desc" role="menuitem" tabindex="-1">Start Date (Desc)</a>
                            <a href="#" class="sort-link text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" data-sort="end_date" data-order="asc" role="menuitem" tabindex="-1">End Date (Asc)</a>
                            <a href="#" class="sort-link text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" data-sort="end_date" data-order="desc" role="menuitem" tabindex="-1">End Date (Desc)</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="kt-card min-w-full">
        <div class="kt-card-header">
            <h3 class="kt-card-title">
                Agreements List
            </h3>
        </div>
        <div class="kt-card-table kt-scrollable-x-auto">
            <div class="kt-scrollable-auto">
                <table class="kt-table align-middle text-secondary-foreground">
                    <thead>
                        <tr class="bg-accent/50">
                            <th scope="col" class="text-start font-medium min-w-52">
                                Agreement Title
                            </th>
                            <th scope="col" class="text-start font-medium min-w-36">
                                Responsible Department
                            </th>
                            <th scope="col" class="text-start font-medium min-w-32">
                                Start Date
                            </th>
                            <th scope="col" class="text-start font-medium min-w-32">
                                End Date
                            </th>
                            <th scope="col" class="text-center font-medium min-w-24">
                                Status
                            </th>
                            <th scope="col" class="text-center min-w-24">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="agreements-table-body">
                        {{-- Loop through agreements --}}
                        @forelse($agreements as $agreement)
                            <tr>
                                <td class="text-start">
                                    <span class="text-sm font-semibold text-mono mb-px">
                                        {{ $agreement->title }}
                                    </span>
                                </td>
                                <td class="text-start">
                                    <span class="text-sm font-normal text-secondary-foreground">
                                        {{ $agreement->department->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="start_date_cell text-start">
                                    <span class="text-sm font-normal text-secondary-foreground">
                                        {{ $agreement->effective_date ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="end_date_cell text-start">
                                    <span class="text-sm font-normal text-secondary-foreground">
                                        {{ $agreement->expiry_date ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if ($agreement->status == 1)
                                        <span class="kt-badge kt-badge-outline kt-badge-success">Active</span>
                                    @elseif ($agreement->status == 0)
                                        <span class="kt-badge kt-badge-outline kt-badge-warning">Draft</span>
                                    @elseif ($agreement->status == 2)
                                        <span class="kt-badge kt-badge-outline kt-badge-danger">Expired</span>
                                    @else
                                        <span class="kt-badge kt-badge-outline kt-badge-danger">Data Error</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="flex justify-center gap-2">
                                        {{-- View details button (visible to all roles) --}}
                                        <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-secondary" href="{{ route('agreements.show', $agreement->id) }}" title="View Details">
                                            <i class="ki-filled ki-eye"></i>
                                        </a>
                                        {{-- Edit and Delete buttons (visible to Admin and Supervisor roles only) --}}
                                        @if (isset($userRole) && ($userRole == 2 || $userRole == 1))
                                            {{-- Edit button --}}
                                            <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-secondary" href="{{ route('agreements.edit', $agreement->id) }}" title="Edit">
                                                <i class="ki-filled ki-pencil"></i>
                                            </a>
                                            {{-- Delete button --}}
                                            <button type="button" class="delete-agreement-button kt-btn kt-btn-sm kt-btn-icon kt-btn-danger" data-delete-url="{{ route('agreements.destroy', $agreement->id) }}" title="Remove">
                                                <i class="ki-filled ki-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary-foreground py-4">
                                    No agreements to display.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="delete-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden" aria-modal="true" role="dialog">
    {{-- Shaded background --}}
    <div class="absolute inset-0 bg-gray-500 bg-opacity-50"></div>
    <div class="relative bg-white rounded-lg p-8 shadow-xl max-w-xs w-full mx-auto border-2 border-gray-300">
        <h3 class="text-lg font-bold text-gray-900 mb-4 text-center">
            Confirm Deletion
        </h3>
        <p class="text-sm text-gray-500 mb-6 text-center">
            Are you sure you want to delete this agreement? This action cannot be undone.
        </p>
        <div class="flex justify-center gap-3">
            <button type="button" id="cancel-delete-button" class="kt-btn kt-btn-secondary kt-btn-sm">
                Cancel
            </button>
            <form id="delete-agreement-form" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="kt-btn kt-btn-danger kt-btn-sm">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // مؤقت رسائل التنبيه
        const successAlert = document.getElementById('success-alert');
        const errorAlert = document.getElementById('error-alert');

        if (successAlert || errorAlert) {
            setTimeout(function() {
                successAlert?.remove();
                errorAlert?.remove();
            }, 3000);
        }

        // Dropdown menu logic for sorting
        const sortDropdownButton = document.getElementById('sort-menu-button');
        const sortDropdownMenu = document.getElementById('sort-menu');
        const tableBody = document.getElementById('agreements-table-body');
        const sortLinks = document.querySelectorAll('.sort-link');

        // Toggle visibility of the dropdown
        sortDropdownButton.addEventListener('click', function(e) {
            e.stopPropagation();
            sortDropdownMenu.classList.toggle('hidden');
        });

        // Add event listeners for sorting links
        sortLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const sortBy = this.dataset.sort;
                const sortOrder = this.dataset.order;
                const rows = Array.from(tableBody.querySelectorAll('tr'));
                rows.sort((a, b) => {
                    let aValue, bValue;
                    if (sortBy === 'start_date') {
                        aValue = new Date(a.querySelector('.start_date_cell').textContent);
                        bValue = new Date(b.querySelector('.start_date_cell').textContent);
                    } else if (sortBy === 'end_date') {
                        aValue = new Date(a.querySelector('.end_date_cell').textContent);
                        bValue = new Date(b.querySelector('.end_date_cell').textContent);
                    }
                    if (sortOrder === 'asc') {
                        return aValue - bValue;
                    } else {
                        return bValue - aValue;
                    }
                });
                tableBody.innerHTML = '';
                rows.forEach(row => tableBody.appendChild(row));
                sortDropdownMenu.classList.add('hidden');
            });
        });

        // Delete confirmation modal logic (re-implemented)
        const deleteButtons = document.querySelectorAll('.delete-agreement-button');
        const deleteModal = document.getElementById('delete-modal');
        const cancelDeleteButton = document.getElementById('cancel-delete-button');
        const deleteAgreementForm = document.getElementById('delete-agreement-form');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.dataset.deleteUrl;
                deleteAgreementForm.action = url;
                deleteModal.classList.remove('hidden');
            });
        });

        cancelDeleteButton.addEventListener('click', function() {
            deleteModal.classList.add('hidden');
        });

        window.addEventListener('click', function(e) {
            // Close dropdown if click is outside
            if (sortDropdownButton && sortDropdownMenu && !sortDropdownButton.contains(e.target) && !sortDropdownMenu.contains(e.target)) {
                sortDropdownMenu.classList.add('hidden');
            }
            // Close modal if click is outside
            if (e.target === deleteModal) {
                deleteModal.classList.add('hidden');
            }
        });
    });
</script>
@endsection
