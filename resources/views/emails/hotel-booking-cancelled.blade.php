<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hotel Booking Cancelled</title>
</head>

<body>
    <divs class="container">
        <h1>Your Hotel Booking Has Been Cancelled</h1>
        <p>Dear {{ $data['user_name'] }},</p>
        <p>We confirm that your booking has been cancelled. Please find the details below:</p>

        <p><strong>Hotel Name:</strong> {{ $data['room_details']['Hotel Name'] }}</p>
        <p><strong>Room Type:</strong> {{ $data['room_details']['room_type'] }}</p>
        <p><strong>Price per Night:</strong> ${{ $data['room_details']['price_per_night'] }}</p>
        <p><strong>Capacity:</strong> {{ $data['room_details']['capacity'] }}</p>
        <p><strong>Check-In Date:</strong> {{ $data['check_in_date'] }}</p>
        <p><strong>Check-Out Date:</strong> {{ $data['check_out_date'] }}</p>
        <p><strong>Status:</strong> {{ $data['status'] }}</p>

        <p>please visit our office with this email to process your refund.</p>

        <div class="footer">
            <p>Thank you for choosing our service.</p>
        </div>
    </divs>
</body>


</html>
