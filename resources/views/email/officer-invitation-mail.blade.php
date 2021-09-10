<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>{{ $details->scholarship }}</h1>
    <p>
        <a href="{{ route('invite', [$details->token]) }}">
            {{ route('invite', [$details->token]) }}
        </a>
    </p>
</body>
</html>
