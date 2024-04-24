<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Umpire Central{{ $title }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/images/fabicon.png') }}">
    <link rel="stylesheet" href="{{ asset('storage/font-awesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/css/toastr.min.css') }}">
    <script src="{{ asset('storage/js/jquery-3.6.4.min.js') }}"></script>
    <script src="{{ asset('storage/js/toastr.min.js') }}"></script>
    <script src="{{ asset('storage/frontend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('storage/frontend/js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(":input[type='text']:first, :input[type='number']:first, textarea:first").focus();
        });
    </script>
</head>
<body>
    @include('general.layouts.error_alert')
