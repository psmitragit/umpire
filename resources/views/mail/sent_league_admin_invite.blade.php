@extends('mail.layouts.main')
@section('main-container')
    @php
        $email = $encData['encryptedEmail'];
        $leagueid = $encData['encryptedLeagueid'];
        $league = $encData['league'];
    @endphp
    Dear User,
    <br>
    <br>
    You are being invited to {{ $league->leaguename }} to join as a second League Admin on our platform.
    <br>
    <br>
    This role is pivotal in assisting with the scheduling and management of umpires for baseball games, and we are excited
    to have you on board.
    <br>
    <br>
    To Get Started:
    Please click the link below to join Umpire Central and begin your journey as a League Admin. This link will guide you
    through the quick process of setting up your account.
    <br>
    <br>
    <a href="{{ url('league-admin-signup/' . $email . '/' . $leagueid) }}">JOIN</a>
    <br>
    <br>
    If you encounter any issues or have questions during the setup process, please feel free to contact our support team for
    assistance.
    <br>
    <br>
    Welcome to Umpire Central! We look forward to your contribution to the {{ $league->leaguename }} and the broader baseball community.
    <br>
    <br>
    Best regards,
    <br>
    Super Admin, Umpire Central
@endsection
