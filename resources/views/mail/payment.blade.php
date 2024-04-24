@extends('mail.layouts.main')
@section('main-container')
    Dear {{ $umpire->name }},
    <br>
    <br>
    We're writing to inform you that a payment for your recent umpiring services has been processed by {{ $league->leaguename }}.
    <br>
    <br>
    For more info on your payment, please visit our website's <a href="{{ url('umpire/earning') }}">“My Earnings”</a> page.
    <br>
    <br>
    Please note that while we notify you of payments, Umpire Central does not handle the financial transactions directly.
    All payment matters, including processing and disputes, are managed independently between you and the league
    administrators.
    <br>
    <br>
    Should you have any questions or concerns regarding your payment, we advise reaching out directly to the league
    administrator for assistance.
    <br>
    <br>
    Thank you for your continued commitment and professionalism as a part of Umpire Central. Your contribution is invaluable
    to the success of the baseball community.
    <br>
    <br>
    Best regards,
    <br>
    Umpire Central Support
@endsection
