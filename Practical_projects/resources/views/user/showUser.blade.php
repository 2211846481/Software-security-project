<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
</head>
<body>
@extends('layout.BaseLayout')

@section('content')

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
            <div class="flex items-center flex-wrap gap-1 lg:gap-5">
                <h1 class="font-medium text-lg text-mono">
                    User Management
                </h1>
            </div>
        </div>
    </div>

    <div class="kt-card kt-card-grid min-w-full">
        <div class="kt-card-header py-5 flex-wrap gap-2">
            <h3 class="kt-card-title">
                Users List
            </h3>
            <div class="flex gap-6">
                {{-- حقل البحث التلقائي --}}
                <div class="kt-input">
                    <i class="ki-filled ki-magnifier"></i>
                    <input placeholder="Search Users by Name" type="text" id="auto-search-input" value="{{ request('search') }}">
                </div>
                {{-- زر إضافة مستخدم جديد --}}
                <a class="kt-btn kt-btn-primary kt-btn-sm" href="adduser">
                    <span class="kt-btn-icon">
                        <i class="ki-filled ki-add-files"></i>
                    </span>
                    <span class="kt-btn-title">
                        Add New User
                    </span>
                </a>
            </div>
        </div>
        
        <div class="kt-card-content">
            <div class="grid" data-kt-datatable="true" data-kt-datatable-page-size="10">
                <div class="kt-scrollable-x-auto">
                    <table class="kt-table kt-table-border" data-kt-datatable-table="true" id="users-table">
                        <thead>
                            <tr class="bg-accent/50">
                                <th class="text-start font-medium min-w-[250px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">User</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th class="text-start font-medium min-w-[120px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Department</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th class="text-start font-medium min-w-[103px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Role</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th class="text-center w-[60px]">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            @forelse ($employees as $employee)
                                <tr>
                                    <td class="text-start">
                                        <div class="flex items-center gap-2.5">
                                            <div class="rounded-full size-9 shrink-0 bg-primary/20 flex items-center justify-center text-primary font-semibold text-sm">
                                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <a class="text-sm font-semibold text-mono hover:text-primary mb-px" href="#">
                                                    {{ $employee->name }}
                                                </a>
                                                <span class="text-xs font-normal text-secondary-foreground">
                                                    {{ $employee->email }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        <span class="text-sm font-normal text-secondary-foreground">
                                            {{ $employee->department->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{-- تنسيق عمود الدور --}}
                                        @if($employee->role == 0)
                                            <span class="kt-badge kt-badge-outline kt-badge-primary">Viewer</span>
                                        @elseif($employee->role == 1)
                                            <span class="kt-badge kt-badge-outline kt-badge-warning">Department User</span>
                                        @elseif($employee->role == 2)
                                            <span class="kt-badge kt-badge-outline kt-badge-success">Admin</span>
                                        @else
                                            <span class="kt-badge kt-badge-outline kt-badge-danger">Unknown</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-secondary" href="{{ route('employees.edit', $employee->id) }}" title="Edit">
                                                <i class="ki-filled ki-pencil"></i>
                                            </a>
                                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-danger" onclick="return confirm('Are you sure you want to delete the user?');" title="Remove">
                                                    <i class="ki-filled ki-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-secondary-foreground py-4">No users found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="kt-card-footer justify-center">
            {{-- التأكد من وجود الترقيم وإضافة query string --}}
            @if(isset($employees) && $employees)
                {{ $employees->appends(['search' => request('search')])->links() }}
            @endif
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

            // كود البحث التلقائي الجديد
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
                }, 500); // 500ms تأخير بعد التوقف عن الكتابة
            });
        });
    </script>
@endsection
</body>
</html>
