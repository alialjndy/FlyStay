<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Hotel Booking Confirmed Email</title>
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <div class="email-wrapper">
        <header class="email-header">
            FlyStay - Hotel Booking Confirmed
        </header>

        <main class="email-body">
            <h2>Hello {{ $data['user_name'] }},</h2>
            <p>
                We are delighted to inform you that your booking with <strong>FlyStay</strong>
                has been <span class="confirmed">Confirmed Successfully 🎉</span>.
            </p>

            <!-- Room Details -->
            <section class="card">
                <h3>🏨 Room Details</h3>
                <div class="info-list">
                    <div class="info-item">
                        <span class="label">Hotel Name</span>
                        <span class="value">{{ $data['room_details']['Hotel Name'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Room Type</span>
                        <span class="value">{{ $data['room_details']['room_type'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Price/Night</span>
                        <span class="value">${{ $data['room_details']['price_per_night'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Capacity</span>
                        <span class="value">{{ $data['room_details']['capacity'] }} Guests</span>
                    </div>
                </div>
            </section>

            <!-- Booking Details -->
            <section class="card">
                <h3>📅 Booking Details</h3>
                <div class="info-list">
                    <div class="info-item">
                        <span class="label">Check-In</span>
                        <span class="value">{{ $data['check_in_date'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Check-Out</span>
                        <span class="value">{{ $data['check_out_date'] }}</span>
                    </div>
                </div>
            </section>

            <!-- Payment Info -->
            <section class="card">
                <h3>💳 Payment Information</h3>
                <div class="info-list">
                    <div class="info-item">
                        <span class="label">Amount</span>
                        <span class="value">${{ $data['Amount'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Date</span>
                        <span class="value">{{ $data['Date'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Payment Method</span>
                        <span class="value">{{ $data['Payment_method'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Payment Status</span>
                        <span class="value">{{ $data['Payment Status'] }}</span>
                    </div>
                </div>
            </section>

            <p class="note">
                Thank you for choosing FlyStay.
                We look forward to hosting you.
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