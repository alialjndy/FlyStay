<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hotel Booking Cancelled</title>
</head>

<body>
    <div class="container">
        <h1>Your Hotel Booking Has Been Cancelled</h1>
        <p>Dear {{ $booking_data['user_name'] }},</p>
        <p>We confirm that your booking has been cancelled. Please find the details below:</p>

        <p><strong>Hotel Name:</strong> {{ $booking_data['room_details']['Hotel Name'] }}</p>
        <p><strong>Room Type:</strong> {{ $booking_data['room_details']['room_type'] }}</p>
        <p><strong>Price per Night:</strong> ${{ $booking_data['room_details']['price_per_night'] }}</p>
        <p><strong>Capacity:</strong> {{ $booking_data['room_details']['capacity'] }}</p>
        <p><strong>Check-In Date:</strong> {{ $booking_data['check_in_date'] }}</p>
        <p><strong>Check-Out Date:</strong> {{ $booking_data['check_out_date'] }}</p>
        <p><strong>Status:</strong> {{ $booking_data['status'] }}</p>

        <h3>
            The money will be refunded is <bold>{{ $payment_data['payment_amount'] }} </bold>
        </h3>
        <p>please visit our office with this email to process your refund.</p>

        <div class="footer">
            <p>Thank you for choosing our service.</p>
        </div>
    </div>
</body>


</html>
