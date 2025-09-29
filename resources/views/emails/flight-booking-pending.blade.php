<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Flight Booking Confirmation</title>
    <link rel="stylesheet" href="email-style.css" />
</head>

<body>
    <div class="email-wrapper">
        <!-- Header -->
        <header class="email-header">
            One step to confirm your booking ✈️
        </header>

        <!-- Greeting -->
        <section class="email-content">
            <p>Hello <strong>{{ $booking_data['user_name'] }}</strong>,</p>
            <p>We have successfully received your flight booking. Here are your booking details:</p>
        </section>

        <!-- Booking Details -->
        <section class="booking-details">
            <div class="booking-item">
                <span class="label">Flight Cabin:</span>
                <span class="value">{{ $booking_data['flightCabin'] }}</span>
            </div>
            <div class="booking-item">
                <span class="label">Flight Number:</span>
                <span class="value">{{ $booking_data['flight_number'] }}</span>
            </div>
            <div class="booking-item">
                <span class="label">Departure:</span>
                <span class="value">{{ $booking_data['departure'] }}</span>
            </div>
            <div class="booking-item">
                <span class="label">Arrival:</span>
                <span class="value">{{ $booking_data['arrival'] }}</span>
            </div>
            <div class="booking-item">
                <span class="label">Date:</span>
                <span class="value">{{ $booking_data['booking_date'] }}</span>
            </div>
            <div class="booking-item">
                <span class="label">Seat Number:</span>
                <span class="value">{{ $booking_data['seat_number'] }}</span>
            </div>
            <div class="booking-item">
                <span class="label">Booking Status:</span>
                <span class="value">{{ $booking_data['status'] }}</span>
            </div>
        </section>

        <!-- CTA -->
        <section class="cta-section">
            <a href="#" class="cta-button">View Booking</a>
        </section>

        <!-- Footer -->
        <footer class="email-footer">
            © 2025 Our Company. All rights reserved.
        </footer>
    </div>
</body>

</html>