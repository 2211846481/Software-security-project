<!DOCTYPE html>
<html>
<head>
    <title>Agreements Report</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h1>Agreements Report</h1>
    
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Department</th>
                <th>Effective Date</th>
                <th>Expiry Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($filteredAgreements as $agreement)
            <tr>
                <td>{{ $agreement->title }}</td>
                <td>{{ $agreement->department->name ?? 'N/A' }}</td>
                <td>{{ $agreement->effective_date }}</td>
                <td>{{ $agreement->expiry_date }}</td>
                <td>
                    @if ($agreement->status == 1)
                        Active
                    @elseif ($agreement->status == 0)
                        Draft
                    @elseif ($agreement->status == 2)
                        Expired
                    @else
                        Data Error
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>