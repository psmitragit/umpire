@extends('mail.layouts.main')
@section('main-container')
    Hello,
    <br>
    <br>
    We're excited to welcome you to Umpire Central, your one-stop solution for umpire scheduling! We’re committed to making
    the management of your games as seamless as possible.
    <br>
    <br>
    <strong>Getting Started</strong>
    <br>
    To get your league set up and running, simply click on the link below. This is a one-time link that will guide you
    through the process of creating your league on our platform.
    <br>
    <br>
    <strong><a href="{{ url('league-signup/' . $email) }}">JOIN</a></strong>
    <br>
    <br>
    If you need any help with your league setup, please contact support.
    <br>
    <br>
    We’re thrilled to have you as part of the Umpire Central community. Let's make this baseball season the best one yet!
    <br>
    <br>
    Best regards,
    <br>
    Umpire Central Support
@endsection
