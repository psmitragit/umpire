@extends('mail.layouts.main')
@section('main-container')
    Dear {{ $league->name }},
    <br>
    <br>
    An umpire has been successfully scheduled for your upcoming baseball game.
    <br>
    <br>
    Umpire Name: {{ $umpire->name }} <br>
    Game Date: {{ date('D m/d/y', strtotime($gamedata->gamedate)) }} <br>
    Time: {{ date('h:i a', strtotime($gamedata->gamedate_toDisplay)) }} <br>
    Location: {{ $gamedata->location->ground }}
    <br>
    <br>
    All additional game-related information, including umpire contact details, can be found on our website.
    <br>
    <br>
    As always, we are here to assist you with any aspect of your game management. Should you need further assistance or have
    any inquiries, please do not hesitate to reach out.
    <br>
    <br>
    Thank you for using Umpire Central to manage your baseball games. We're committed to providing you with the best
    possible service.
    <br>
    <br>
    Best regards,
    <br>
    Umpire Central
@endsection
