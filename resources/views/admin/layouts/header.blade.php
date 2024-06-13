<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title><?= $title ?></title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/images/fabicon.png') }}">
    <link rel="stylesheet" href="{{ asset('storage/templete/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/templete/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/templete/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/templete/vendors/typicons/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/templete/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/templete/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/templete/css/vertical-layout-light/style.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/font-awesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/css/admin-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/css/toastr.min.css') }}">
    @livewireStyles()
    @livewireScripts()
    <script src="{{ asset('storage/js/jquery-3.6.4.min.js') }}"></script>
    <script src="{{ asset('storage/js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('storage/js/toastr.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(":input[type='text']:first, :input[type='number']:first, textarea:first").focus();
        });
    </script>
</head>

<body>
    {!! stagingMark() !!}
    <div class="container-scroller">
        @include('admin.layouts.error_alert')
        @if ($title !== 'Login')
            @include('admin.layouts.menubar')
            @include('admin.layouts.sidebar')
        @endif
