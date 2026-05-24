<!DOCTYPE html>
<html>
<head>
    <title>Agreement Expiry Alert</title>
</head>
<body>
    <h1>Agreement Expiry Alert</h1>
    <p>Hello,</p>
    <p>This is a reminder that the following agreement is about to expire:</p>
    <ul>
        <li><strong>Title:</strong> {{ $agreement->title }}</li>
        <li><strong>Reference Code:</strong> {{ $agreement->reference_code }}</li>
        <li><strong>Expiry Date:</strong> {{ $agreement->expiry_date->format('Y-m-d') }}</li>
    </ul>
    <p>Please take the necessary action.</p>
    <p>Regards,<br>IAMS System</p>
</body>
</html>