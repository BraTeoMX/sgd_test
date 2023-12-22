@extends('layouts.main')

@section('styleBFile')
    <!-- Color Box -->
    <link href="{{ asset('colorbox/colorbox.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Retardos</h3>
        </div>
        <body onload="myFunction()">
           
        </body>
    </div>
    <br>
    <div class="card">

    </div>
@endsection

@section('scriptBFile')
<script>
    function myFunction() {
        window.location.href=("http://128.150.102.131:86/delay")

    }
</script>
@endsection
