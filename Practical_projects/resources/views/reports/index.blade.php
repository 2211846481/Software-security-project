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

    <div class="mb-5 lg:mb-7.5">
        <div class="kt-container-fluid flex items-center justify-between flex-wrap gap-5">
            <h1 class="font-medium text-lg text-mono">
                Reports Dashboard
            </h1>
        </div>
    </div>

    {{-- بطاقة خيارات التصفية والتصدير --}}
    <div class="kt-card min-w-full mb-5">
        <div class="kt-card-header">
            <h3 class="kt-card-title">Filter and Export Reports</h3>
        </div>
        <div class="kt-card-content pt-4 pb-3">
            <form action="{{ route('reports.index') }}" method="GET">
                <div class="flex flex-wrap gap-4 items-end">
                    
                    {{-- 🟢 حقل فلترة حسب القسم (يظهر فقط للمسؤول) --}}
                    @if ($user->role == 2)
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                            <select id="department" name="department_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    
                    {{-- حقل فلترة حسب نوع الاتفاقية --}}
                    <div>
                        <label for="agreement_type" class="block text-sm font-medium text-gray-700">Agreement Type</label>
                        <select id="agreement_type" name="agreement_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">All Types</option>
                            @foreach($agreementTypes as $type)
                                <option value="{{ $type->id }}" @selected(request('agreement_type_id') == $type->id)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- حقل فلترة حسب الحالة --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">All Statuses</option>
                            <option value="1" @selected(request('status') == '1')>Active</option>
                            <option value="0" @selected(request('status') == '0')>Draft</option>
                            <option value="2" @selected(request('status') == '2')>Expired</option>
                        </select>
                    </div>

                    {{-- زر تطبيق الفلتر --}}
                    <div class="flex items-center gap-2">
                        <button type="submit" class="kt-btn kt-btn-primary">Apply Filters</button>
                        <a href="{{ route('reports.index') }}" class="kt-btn kt-btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    {{-- بطاقة عرض التقرير --}}
    <div class="kt-card min-w-full">
        <div class="kt-card-header">
            <h3 class="kt-card-title">Filtered Agreements ({{ $filteredAgreements->count() }})</h3>
            
            {{-- أزرار التصدير --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('reports.export.pdf', request()->query()) }}" class="kt-btn kt-btn-secondary kt-btn-sm">
                    <span class="kt-btn-title">Export PDF</span>
                </a>
                <a href="{{ route('reports.export.excel', request()->query()) }}" class="kt-btn kt-btn-secondary kt-btn-sm">
                    <span class="kt-btn-title">Export Excel</span>
                </a>
            </div>
        </div>
        <div class="kt-card-table kt-scrollable-x-auto">
            <table class="kt-table align-middle text-secondary-foreground">
                <thead>
                    <tr class="bg-accent/50">
                        <th scope="col" class="text-start font-medium min-w-52">Title</th>
                        <th scope="col" class="text-start font-medium min-w-36">Department</th>
                        <th scope="col" class="text-start font-medium min-w-32">Type</th>
                        <th scope="col" class="text-start font-medium min-w-32">Status</th>
                        <th scope="col" class="text-start font-medium min-w-32">Expiry Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($filteredAgreements as $agreement)
                        <tr>
                            <td>{{ $agreement->title }}</td>
                            <td>{{ $agreement->department->name ?? 'N/A' }}</td>
                            <td>{{ $agreement->agreementType->name ?? 'N/A' }}</td>
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
                            <td>{{ $agreement->expiry_date->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary-foreground py-4">No agreements found with the selected filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection