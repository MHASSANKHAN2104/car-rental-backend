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
    <p>{{ $declineMessage }}</p>

    <h3>Rental Details:</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Vehicle Information</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                <td>{{ $customer->phone_number }}</td>
                <td>{{ $customer->address }}</td>
                <td>{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->reg_number }})</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
