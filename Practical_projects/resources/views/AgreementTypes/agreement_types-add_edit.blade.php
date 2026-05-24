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

    {{-- Determine if we are adding or editing based on the presence of $agreementType --}}
    @php
        $isEdit = isset($agreementType);
        $title = $isEdit ? 'Update Agreement Type' : 'Add New Agreement Type';
        $formAction = $isEdit ? route('agreement_types.update', $agreementType->id) : route('agreement_types.store');
        $buttonText = $isEdit ? 'Update Agreement Type' : 'Add Agreement Type';
    @endphp

    <div class="mb-5 lg:mb-7.5">
        <div class="kt-container-fluid flex items-center justify-between flex-wrap gap-5">
            <h1 class="font-medium text-lg text-mono">
                {{ $title }}
            </h1>
        </div>
    </div>

    <div class="kt-card kt-card-grid min-w-full">
        <form method="POST" action="{{ $formAction }}">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="kt-card-content py-5">
                <div class="grid gap-8">
                    {{-- Agreement Type Name Input --}}
                    <div class="form-group flex items-center gap-6">
                        <label for="name" class="shrink-0 w-[200px] text-end font-semibold text-mono">
                            Agreement Type Name
                        </label>
                        <div class="grow">
                            <input type="text" id="name" name="name" class="kt-input" placeholder="Enter agreement type name" value="{{ old('name', $agreementType->name ?? '') }}" required>
                            @error('name')
                                <p class="text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Description Input (Optional) --}}
                    <div class="form-group flex items-center gap-6">
                        <label for="description" class="shrink-0 w-[200px] text-end font-semibold text-mono">
                            Description
                        </label>
                        <div class="grow">
                            <textarea id="description" name="description" class="kt-textarea" placeholder="Enter a description (optional)">{{ old('description', $agreementType->description ?? '') }}</textarea>
                            @error('description')
                                <p class="text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-card-footer flex justify-end gap-2.5">
                <a href="{{ route('agreement_types.index') }}" class="kt-btn kt-btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="kt-btn kt-btn-primary">
                    {{ $buttonText }}
                </button>
            </div>
        </form>
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
        });
    </script>
@endsection
