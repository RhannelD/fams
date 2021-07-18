<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>FAMS</title>
    <link rel="icon" href="{{ asset('img/scholarship-icon.png') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    {{-- <link rel="dns-prefetch" href="//fonts.gstatic.com"> --}}
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    {{-- <script src="{{ asset('js/livewire-sortable.js') }}"></script> --}}
    
    @yield('styles')
</head>
<body>
    @include('sweet::alert')

    <div id="app" class="full-height">
        <main class="full-height">

          @yield('contents')

        </main>
    </div>
    
    @yield('scripts')

    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
  
        window.addEventListener('swal:modal', event => { 
            swal({
              title: event.detail.message,
              text: event.detail.text,
              icon: event.detail.type,
            });
        });
        
        window.addEventListener('remove:modal-backdrop', event => { 
            $('.modal-backdrop').remove();
        });
        
        document.addEventListener('DOMContentLoaded', function () {
            window.livewire.on('url_update', (url) => {
                history.replaceState(null, null, url);
            });

            window.livewire.on('url_push', (url) => {
                history.pushState(null, null, url);
            });
        });
    </script>
</body>
</html>
