<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>URL Generation</title>
</head>

<body>
    {{ url()->current() }} <br>
    {{ url("/hotels/1") }} <br>
    {{ url()->query("/hotels/1", ['available' => true]) }} <br>
    {{ url()->query("/hotels/1?available=1", ['room_count' => 20]) }} <br>
    {{ url()->query('/posts', ['columns' => ['title', 'body']]) }} <br>
    {{ URL::signedRoute('login', ['user' => 1], absolute: false) }} <br>
    {{ URL::temporarySignedRoute('login', now()->addMinutes(10), ['user' => 1], absolute: false) }} <br>

    <div style="background-color: red">
        {{ urlencode(url()->query("/hotels/1?available=1", ['room_count' => 20])) }}
    </div>
</body>

</html>