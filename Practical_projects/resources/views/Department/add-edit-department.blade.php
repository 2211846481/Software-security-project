{{--
    This interface is a unified form for adding and editing departments.
    It relies on the presence of the $department variable to determine if it is an edit or add view.
    Save this file as "add-edit-department.blade.php" in the "resources/views" folder.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($department) ? 'Edit Department' : 'Add New Department' }}</title>
</head>
<body>
    @extends('layout.BaseLayout')

    @section('content')
        <div class="kt-card min-w-full max-w-2xl mx-auto">
            <div class="kt-card-header">
                <h3 class="kt-card-title">
                    {{ isset($department) ? 'Edit Department' : 'Add New Department' }}
                </h3>
            </div>
            <form action="{{ isset($department) ? route('departments.update', $department->id) : route('departments.store') }}" method="POST">
                @csrf
                @if(isset($department))
                    @method('PUT')
                @endif
                <div class="kt-card-content grid gap-5">
                    
                    {{-- Department Name field --}}
                    <div class="w-full">
                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="kt-form-label flex items-center gap-1 max-w-56" for="name">
                                Department Name
                            </label>
                            <input class="kt-input grow" type="text" name="name" id="name" placeholder="Enter department name" value="{{ old('name', $department->name ?? '') }}" required autofocus>
                        </div>
                        @error('name')
                            <span class="text-destructive text-sm mt-1 block text-end">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-2.5">
                        <a href="{{ route('departments.index') }}" class="kt-btn kt-btn-secondary me-3">
                            Cancel
                        </a>
                        <button type="submit" class="kt-btn kt-btn-primary">
                            {{ isset($department) ? 'Update Department' : 'Add Department' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endsection
</body>
</html>