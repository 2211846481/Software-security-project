{{--
    This is the Agreement Types Management view, designed to match the styling of your provided page.
    It uses similar classes and Blade directives for a consistent look and feel.
--}}
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agreement Types Management</title>
    <!-- Assuming your layout.BaseLayout already includes necessary CSS/JS libraries like Tailwind and Metronic assets -->
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
                Agreement Types Management
            </h1>
        </div>
    </div>

    <div class="kt-card kt-card-grid min-w-full">
        <div class="kt-card-header py-5 flex-wrap gap-2">
            <h3 class="kt-card-title">
                Agreement Types List
            </h3>
            <div class="flex gap-6">
                {{-- Auto-search input --}}
                <div class="kt-input">
                    <i class="ki-filled ki-magnifier"></i>
                    <input placeholder="Search Agreement Types by Name" type="text" id="auto-search-input" value="{{ request('search') }}">
                </div>
                {{-- Add button is only visible to users with role 2 --}}
                @if (auth()->check() && auth()->user()->role === 2)
                    <a class="kt-btn kt-btn-primary kt-btn-sm" href="{{ route('agreement_types.create') }}">
                        <span class="kt-btn-icon">
                            <i class="ki-filled ki-add-files"></i>
                        </span>
                        <span class="kt-btn-title">
                            Add New Agreement Type
                        </span>
                    </a>
                @endif
            </div>
        </div>
        <div class="kt-card-content">
            <div class="grid" data-kt-datatable="true" data-kt-datatable-page-size="10">
                <div class="kt-scrollable-x-auto">
                    <table class="kt-table kt-table-border" data-kt-datatable-table="true" id="agreement-types-table">
                        <thead>
                            <tr class="bg-accent/50">
                                <th class="text-start font-medium min-w-[250px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Agreement Type</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th class="text-start font-medium min-w-[250px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Description</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th class="text-start font-medium min-w-[120px]">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Agreements Count</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                {{-- Actions column is only visible to users with role 2 --}}
                                @if (auth()->check() && auth()->user()->role === 2)
                                    <th class="text-center w-[60px]">
                                        Actions
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            {{-- Loop through the agreement types collection --}}
                            @forelse ($agreementTypes as $agreementType)
                                <tr>
                                    <td class="text-start">
                                        <div class="flex items-center grow gap-2.5">
                                            {{-- Display the first letter of the agreement type name --}}
                                            <div class="rounded-full size-9 shrink-0 bg-primary/20 flex items-center justify-center text-primary font-semibold text-sm">
                                                {{ strtoupper(substr($agreementType->name, 0, 1)) }}
                                            </div>
                                            <span class="text-sm font-semibold text-mono mb-px">
                                                {{ $agreementType->name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        <span class="text-sm font-normal text-secondary-foreground">
                                            {{ Str::limit($agreementType->description, 50) ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-start">
                                        <span class="text-sm font-normal text-secondary-foreground">
                                            {{ $agreementType->agreements_count }}
                                        </span>
                                    </td>
                                    {{-- Action buttons are only visible to users with role 2 --}}
                                    @if (auth()->check() && auth()->user()->role === 2)
                                        <td class="text-center">
                                            <div class="flex justify-center gap-2">
                                                {{-- Edit button --}}
                                                <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-secondary" href="{{ route('agreement_types.edit', $agreementType->id) }}" title="Edit">
                                                    <i class="ki-filled ki-pencil"></i>
                                                </a>
                                                {{-- Delete form/button --}}
                                                <form action="{{ route('agreement_types.destroy', $agreementType->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-danger" onclick="return confirm('Are you sure you want to delete this agreement type?');" title="Remove">
                                                        <i class="ki-filled ki-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-secondary-foreground py-4">No agreement types found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="kt-card-footer justify-center">
            {{-- Ensure pagination exists and append the search query string --}}
            @if(isset($agreementTypes) && $agreementTypes->lastPage() > 1)
                {{ $agreementTypes->appends(['search' => request('search')])->links() }}
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Alert message timer
            const successAlert = document.getElementById('success-alert');
            const errorAlert = document.getElementById('error-alert');

            if (successAlert || errorAlert) {
                setTimeout(function() {
                    successAlert?.remove();
                    errorAlert?.remove();
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
