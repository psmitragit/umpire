@extends('mail.layouts.main')
@section('main-container')
    Dear {{ $ump->name }},
    <br>
    <br>
    This message was from the League admin of {{ $league->leaguename }}.
    <br>
    <br>
    <b>
        {{ $leaguemsg }}
    </b>
    <br>
    <br>
    If you have any questions or issues accessing your messages, please don't hesitate to contact our support team.
    <br>
    <br>
    Thank you for being a vital part of the Umpire Central community and ensuring the success of our baseball games.
    <br>
    <br>
    Best regards,
    <br>
    Umpire Central Support
@endsection
