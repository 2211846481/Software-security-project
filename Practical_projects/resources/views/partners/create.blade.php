<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Partner Institution</title>
</head>
<body>
    @extends('layout.BaseLayout')

    @section('content')
    <div class="kt-card min-w-full max-w-2xl mx-auto">
        <div class="kt-card-header" id="basic_settings">
            <h3 class="kt-card-title">
                Add New Partner Institution
            </h3>
        </div>
        <form method="POST" action="{{ route('partners.store') }}">
            @csrf
            <div class="kt-card-content grid gap-5">

                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            Institution Name:
                        </label>
                        <input class="kt-input grow" type="text" name="name" id="name" placeholder="Enter institution name" value="{{ old('name') }}" required>
                    </div>
                    @error('name')
                        <span class="text-destructive text-sm mt-1 block text-end">{{ $message }}</span>
                    @enderror
                </div>

                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            Country:
                        </label>
                        <input class="kt-input grow" type="text" name="country" id="country" placeholder="Enter country" value="{{ old('country') }}">
                    </div>
                    @error('country')
                        <span class="text-destructive text-sm mt-1 block text-end">{{ $message }}</span>
                    @enderror
                </div>

                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            Contact Person:
                        </label>
                        <input class="kt-input grow" type="text" name="contact_name" id="contact_name" placeholder="Enter contact person's name" value="{{ old('contact_name') }}">
                    </div>
                    @error('contact_name')
                        <span class="text-destructive text-sm mt-1 block text-end">{{ $message }}</span>
                    @enderror
                </div>

                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            Email:
                        </label>
                        <input class="kt-input grow" type="email" name="contact_email" id="contact_email" placeholder="Enter email address" value="{{ old('contact_email') }}">
                    </div>
                    @error('contact_email')
                        <span class="text-destructive text-sm mt-1 block text-end">{{ $message }}</span>
                    @enderror
                </div>



                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            Website:
                        </label>
                        <input class="kt-input grow" type="url" name="website" id="website" placeholder="Enter website URL" value="{{ old('website') }}">
                    </div>
                    @error('website')
                        <span class="text-destructive text-sm mt-1 block text-end">{{ $message }}</span>
                    @enderror
                </div>

                <div class="w-full">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label flex items-center gap-1 max-w-56">
                            Sector:
                        </label>
                        <input class="kt-input grow" type="text" name="sector" id="sector" placeholder="Enter sector (e.g., Educational, Private)" value="{{ old('sector') }}">
                    </div>
                    @error('sector')
                        <span class="text-destructive text-sm mt-1 block text-end">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end pt-2.5">
                    <a href="{{ route('partners.index') }}" class="kt-btn kt-btn-secondary me-3">Cancel</a>
                    <button type="submit" class="kt-btn kt-btn-primary">
                        Save Institution
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endsection
</body>
</html>