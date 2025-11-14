<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>XSS</title>
</head>

<body>
    <form method="POST" action="/XSS">
        @csrf
        <input name="amount" value="100">
        <button type="submit">Send</button>
    </form>
</body>

</html>