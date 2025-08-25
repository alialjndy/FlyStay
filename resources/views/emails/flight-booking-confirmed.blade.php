<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Booking Confirmation</title>
    <link rel="stylesheet" href="{{ asset('css/flight-booking-confirmed.css') }}">
</head>

<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="header">
            <h1>✈️ Flight Booking Confirmed</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Hello {{ $data['user_name'] }},</h2>
            <p>Your flight booking has been <strong>confirmed</strong>. Below are your booking and payment details:</p>

            <!-- Booking Details -->
            <div class="section">
                <h3>Booking Details</h3>
                <ul class="details-list">
                    <li><strong>Flight Number:</strong> {{ $data['flight_number'] }}</li>
                    <li><strong>Cabin Class:</strong> {{ $data['flightCabin'] }}</li>
                    <li><strong>Departure:</strong> {{ $data['departure'] }}</li>
                    <li><strong>Arrival:</strong> {{ $data['arrival'] }}</li>
                    <li><strong>Booking Date:</strong> {{ $data['booking_date'] }}</li>
                    <li><strong>Seat Number:</strong> {{ $data['seat_number'] }}</li>
                    <li><strong>Status:</strong> {{ $data['status'] }}</li>
                </ul>
            </div>

            <!-- Payment Details -->
            <div class="section">
                <h3>Payment Details</h3>
                <ul class="details-list">
                    <li><strong>Amount:</strong> {{ $data['payment_amount'] }}</li>
                    <li><strong>Date:</strong> {{ $data['payment_date'] }}</li>
                    <li><strong>Method:</strong> {{ $data['payment_method'] }}</li>
                    <li><strong>Status:</strong> {{ $data['payment_status'] }}</li>
                </ul>
            </div>

            <p>We wish you a pleasant journey and thank you for choosing our service!</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} Flight Booking System. All rights reserved.
        </div>
    </div>
</body>

</html>
