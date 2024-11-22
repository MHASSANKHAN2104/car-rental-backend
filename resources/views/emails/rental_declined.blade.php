<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Request Declined</title>
</head>
<body>
    <h1>{{ $companyName }}</h1>
    <p>Date: {{ $date }}</p>
    <p>Day: {{ $day }}</p>

    <h3>Rental Request Declined</h3>
    <p>Dear {{ $customer->first_name }} {{ $customer->last_name }},</p>
    <p>{{ $declineMessage }}</p>  <!-- This will display the rejection message -->
</body>
</html>
