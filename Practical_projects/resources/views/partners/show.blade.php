<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل المؤسسة الشريكة</title>
</head>
<body>
@extends('layout.BaseLayout')

@section('content')

    <div class="kt-card min-w-full max-w-2xl mx-auto">
        <div class="kt-card-header">
            <h3 class="kt-card-title">
                تفاصيل المؤسسة الشريكة: {{ $partner->name }}
            </h3>
            <div class="flex items-center gap-5">
                <a class="kt-btn kt-btn-primary kt-btn-sm" href="{{ route('partners.edit', $partner->id) }}">
                    <span class="kt-btn-icon">
                        <i class="ki-filled ki-pencil"></i>
                    </span>
                    <span class="kt-btn-title">
                        تعديل
                    </span>
                </a>
                <a class="kt-btn kt-btn-secondary kt-btn-sm" href="{{ route('partners.index') }}">
                    <span class="kt-btn-icon">
                        <i class="ki-filled ki-arrow-left"></i>
                    </span>
                    <span class="kt-btn-title">
                        العودة للقائمة
                    </span>
                </a>
            </div>
        </div>
        <div class="kt-card-content grid gap-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <strong>الاسم:</strong> {{ $partner->name }}
                </div>
                <div>
                    <strong>البلد:</strong> {{ $partner->country }}
                </div>
                <div>
                    <strong>الشخص المسؤول:</strong> {{ $partner->contact_name }}
                </div>
                <div>
                    <strong>البريد الإلكتروني:</strong> {{ $partner->contact_email }}
                </div>
                <div>
                    <strong>الموقع الإلكتروني:</strong> <a href="{{ $partner->website }}" target="_blank" class="text-blue-600 hover:underline">{{ $partner->website }}</a>
                </div>
                <div>
                    <strong>القطاع:</strong> {{ $partner->sector }}
                </div>
            </div>
            
            <hr class="my-4">
            
            <h4 class="kt-card-title">
                الاتفاقيات المرتبطة
            </h4>

            @if($partner->agreements->count() > 0)
                <table class="kt-table align-middle text-secondary-foreground">
                    <thead>
                        <tr class="bg-accent/50">
                            <th class="text-start font-medium">عنوان الاتفاقية</th>
                            <th class="text-start font-medium">تاريخ البدء</th>
                            <th class="text-start font-medium">تاريخ الانتهاء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partner->agreements as $agreement)
                            <tr>
                                <td class="text-start">{{ $agreement->title }}</td>
                                <td class="text-start">{{ $agreement->start_date }}</td>
                                <td class="text-start">{{ $agreement->end_date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>لا توجد اتفاقيات مرتبطة بهذه المؤسسة الشريكة.</p>
            @endif
        </div>
    </div>
@endsection
</body>
</html>
