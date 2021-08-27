@extends('layouts.app')

@section('styles')
    @livewireStyles 
@endsection


@section('contents')

<div>
    @yield('content')     
</div>

@endsection


@section('scripts')
    @livewireScripts
@endsection