@extends('mail.layouts.main')
@section('main-container')
    Dear User,
    <br>
    <br>
    We received a request to reset the password for your account associated with this email address. If you made this
    request, please click the link below to create a new password:
    <br>
    <br>
    <a href='{{ $url }}'>Reset Password</a>
    <br>
    <br>
    (If the link doesn't work, you can copy and paste it into your browser. Please note: For security reasons, this link
    will expire in 24 hours.)
    <br>
    <br>
    If you did not request a password reset or if you remember your original password and would like to keep it, you can
    ignore this email, and your password will remain unchanged.
    <br>
    <br>
    For your security, we recommend:<br>
    Creating a strong password that includes a mix of letters, numbers, and symbols.
    Not using the same password for multiple sites.
    Changing your password periodically.
    <br>
    <br>
    If you have any concerns or need further assistance, please reach out to our support team at support@umpirecentral.com.
    <br>
    <br>
    Stay safe, and thank you for being a part of the Umpire Central community!
    <br>
    <br>
    Warm regards,<br>
    Umpire Central Support
@endsection
