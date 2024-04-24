@extends('mail.layouts.main')
@section('main-container')
    Dear Umpire,
    <br>
    <br>
    Welcome to Umpire Central! We're thrilled to have you join our community. To complete your signup process and get
    started, please use the One-Time Password (OTP) provided below:
    <br>
    <br>
    Your OTP: <b>{{ $otp }}</b>
    <br>
    <br>
    Enter this code on the registration page to verify your email and continue with the signup.
    <br>
    <br>
    If you didn't request this OTP or if you're facing any issues, please reach out to our support team immediately at
    support@umpirecentral.com.
    <br>
    <br>
    Once your account is set up, you'll be able to access schedules, connect with league administrators, and more. We're
    excited to streamline your umpiring experience with our platform!
    <br>
    <br>
    Thank you for choosing Umpire Central. Let's make this baseball season memorable!
    <br>
    <br>
    Warm regards,<br>
    Umpire Central Support
@endsection
