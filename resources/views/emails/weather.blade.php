<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Weather Forecast</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding:20px;">

    <div
        style="max-width: 600px; margin:0 auto; background:#ffffff; border-radius:8px; padding:20px; box-shadow:0 2px 6px rgba(0,0,0,0.1);">

        <h2 style="text-align:center; color:#1e293b; margin-bottom:20px;">
            🌤️ Weather Forecast for Your Trip
        </h2>

        @if(isset($weather_data['status']) && $weather_data['status'] === 'success')
            <div style="text-align:center;">
                <h3 style="margin:0; color:#0f172a;">{{ $weather_data['weather_data']['location'] }}</h3>
                <p style="margin:5px 0; color:#64748b;">Date: {{ $weather_data['weather_data']['date'] }}</p>
                <img src="https:{{ $weather_data['weather_data']['icon'] }}" alt="Weather Icon"
                    style="width:80px; height:80px;">
                <p style="font-size:18px; font-weight:bold; margin:10px 0;">
                    {{ $weather_data['weather_data']['condition'] }}
                </p>
                <p style="margin:0; font-size:16px; color:#334155;">
                    Max Temp: {{ $weather_data['weather_data']['maxtemp'] }} <br>
                    Min Temp: {{ $weather_data['weather_data']['mintemp'] }}
                </p>
            </div>
        @else
            <p style="color:#b91c1c; text-align:center; font-size:16px;">
                {{ $weather_data['message'] ?? 'Weather information not available.' }}
            </p>
        @endif

        <hr style="margin:20px 0;">

        <p style="font-size:14px; color:#6b7280; text-align:center;">
            This is an automated message from <strong>FlyStay</strong>.
            If you have any questions, contact our support team at <a
                href="mailto:support@FlyStay.com">support@FlyStay.com</a>.
        </p>

    </div>

</body>

</html>
