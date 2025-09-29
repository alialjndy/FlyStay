<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Flight Booking Cancelled</title>
</head>

<body>
    <div class="container">
        <h1>Your Flight Booking Has Been <strong>Cancelled</strong></h1>
        <p>Dear {{ $booking_data['user'] ?? 'Customer' }},</p>
        <p>We confirm that your booking has been cancelled. Please find the details below:</p>

        <div class="section">
            <h3>Booking Details</h3>
            <ul class="details-list">
                <li><strong>Flight Number:</strong> {{ $booking_data['flight_number'] }}</li>
                <li><strong>Cabin Class:</strong> {{ $booking_data['flightCabin'] }}</li>
                <li><strong>Departure:</strong> {{ $booking_data['departure'] }}</li>
                <li><strong>Arrival:</strong> {{ $booking_data['arrival'] }}</li>
                <li><strong>Booking Date:</strong> {{ $booking_data['booking_date'] }}</li>
                <li><strong>Seat Number:</strong> {{ $booking_data['seat_number'] }}</li>
                <li><strong>Status:</strong> {{ $booking_data['status'] }}</li>
            </ul>
        </div>
        <h3>
            The money will be refunded is <bold>{{ $payment_data['payment_amount'] }} </bold>
        </h3>
        <bold>please visit our office with this email to process your refund.</bold>

        <div class="footer">
            <p>Thank you for choosing our service.</p>
        </div>
    </div>
</body>

</html>