<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Details</title>
</head>
<body>
    @extends('layout.BaseLayout')

    @section('content')
    
    <div class="kt-card min-w-full max-w-2xl mx-auto">
        <div class="kt-card-header" id="basic_settings">
            <h3 class="kt-card-title">
                Edit User Details: {{ $employee->name }}
            </h3>
        </div>
        <form method="POST" action="{{ route('employees.update', $employee->id) }}">
            @csrf
            @method('PUT')
            <div class="kt-card-content grid gap-5">

                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            Name
                        </label>
                        <input class="kt-input grow" type="text" name="name" id="name" placeholder="Enter user name" value="{{ old('name', $employee->name) }}" required>
                    </div>
                    @error('name')
                        <span class="text-destructive text-sm mt-1 block text-end">{{ $message }}</span>
                    @enderror
                </div>

                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            Email
                        </label>
                        <input class="kt-input grow" type="email" name="email" id="email" value="{{ $employee->email }}" readonly disabled>
                        <input type="hidden" name="email" value="{{ $employee->email }}">
                    </div>
                </div>

                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            New Password (Optional)
                        </label>
                        <input class="kt-input grow" type="password" name="password" id="password" placeholder="Leave blank to not change">
                    </div>
                    @error('password')
                        <span class="text-destructive text-sm mt-1 block text-end">{{ $message }}</span>
                    @enderror
                </div>

                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            Confirm New Password
                        </label>
                        <input class="kt-input grow" type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm new password">
                    </div>
                    @error('password_confirmation')
                        <span class="text-destructive text-sm mt-1 block text-end">{{ $message }}</span>
                    @enderror
                </div>

                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            Role
                        </label>
                        <select class="kt-input grow" name="role" id="role" required>
                            <option value="">Select Role</option>
                            <option value="0" {{ old('role', $employee->role) == '0' ? 'selected' : '' }}>Viewer</option>
                            <option value="1" {{ old('role', $employee->role) == '1' ? 'selected' : '' }}>Department User</option>
                            <option value="2" {{ old('role', $employee->role) == '2' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    @error('role')
                        <span class="text-destructive text-sm mt-1 block text-end">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            Department
                        </label>
                        <select class="kt-input grow" name="department_id" id="department_id" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('department_id')
                        <span class="text-destructive text-sm mt-1 block text-end">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end pt-2.5">
                    <a href="{{ route('employees.list') }}" class="kt-btn kt-btn-secondary me-3">Cancel</a>
                    <button type="submit" class="kt-btn kt-btn-primary">
                        Update User
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endsection
</body>
</html>