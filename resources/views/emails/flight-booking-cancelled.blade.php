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
        <h1>Your Flight Booking Has Been Cancelled</h1>
        <p>Dear {{ $data['user'] ?? 'Customer' }},</p>
        <p>We confirm that your booking has been cancelled. Please find the details below:</p>

        <div class="details">
            <table>
                <tr>
                    <th>Flight Cabin</th>
                    <td>{{ $data['flightCabin'] ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Flight Number</th>
                    <td>{{ $data['flight_number'] ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Departure</th>
                    <td>{{ $data['departure'] ?? '-' }}
                        {{-- {{ $flightBooking->flight->departure_time ?? '-' }} --}}
                    </td>
                </tr>
                <tr>
                    <th>Arrival</th>
                    <td>{{ $data['arrival'] ?? '-' }}
                        {{-- {{ $flightBooking->flight->arrival_time ?? '-' }} --}}
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><strong style="color:#c0392b;">Cancelled</strong></td>
                </tr>
            </table>
        </div>

        <p>please visit our office with this email to process your refund.</p>

        <div class="footer">
            <p>Thank you for choosing our service.</p>
        </div>
    </div>
</body>

</html>