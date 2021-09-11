<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $response_file->file_name }}</title>
    <link rel="icon" href="{{ asset('img/scholarship-icon.png') }}">
    <style>
        html, body {
            height: 100%;
            padding: 0;
            margin: 0;
        }
        
        .full-height {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body> 
    @php
        $is_desktop = new \Jenssegers\Agent\Agent();
        $is_desktop = $is_desktop->isDesktop();
    @endphp 
    @if ($is_desktop)
        <iframe src="{{ Storage::disk('files')->url($response_file->file_url) }}"  class="full-height" frameborder="0"></iframe>
    @endif
</body>
</html>
