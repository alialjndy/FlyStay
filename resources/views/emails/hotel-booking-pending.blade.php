<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Hotel Booking Pending Email</title>
    <link rel="stylesheet" href="hotel-booking-pending.css" />
</head>

<body>
    <div class="email-wrapper">
        <header class="email-header">
            FlyStay - Hotel Booking Pending
        </header>

        <main class="email-body">
            <h2>Hello {{ $booking_data['user_name'] }},</h2>
            <p>
                Thank you for choosing <strong>FlyStay</strong>.
                Your hotel booking is currently
                <span class="pending">Pending Confirmation</span>.
            </p>

            <section class="card">
                <h3>🏨 Room Details</h3>
                <div class="info-list">
                    <div class="info-item">
                        <span class="label">Hotel Name</span>
                        <span class="value">{{ $booking_data['room_details']['Hotel Name'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Room Type</span>
                        <span class="value">{{ $booking_data['room_details']['room_type'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Price/Night</span>
                        <span class="value">${{ $booking_data['room_details']['price_per_night'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Capacity</span>
                        <span class="value">{{ $booking_data['room_details']['capacity'] }} Guests</span>
                    </div>
                </div>
            </section>

            <section class="card">
                <h3>📅 Booking Details</h3>
                <div class="info-list">
                    <div class="info-item">
                        <span class="label">Check-In</span>
                        <span class="value">{{ $booking_data['check_in_date'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Check-Out</span>
                        <span class="value">{{ $booking_data['check_out_date'] }}</span>
                    </div>
                    <div class="info-item total">
                        <span class="label">Total Price</span>
                        <span class="value">${{ $booking_data['total_price'] }}</span>
                    </div>
                </div>
            </section>

            <p class="note">
                We will notify you once your booking is confirmed.
                Please do not reply to this email.
            </p>

            <div class="cta">
                <a href="#">View My Booking</a>
            </div>
        </main>

        <footer class="email-footer">
            &copy; {{ date('Y') }} FlyStay. All rights reserved.
        </footer>
    </div>
</body>

</html>