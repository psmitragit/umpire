@extends('mail.layouts.main')
@section('main-container')
    Dear {{ $umpire->name }},
    <br>
    <br>
    You've been scheduled for an upcoming baseball game. Here are the details:
    <br>
    <br>
    League: {{ $gamedata->league->leaguename }} <br>
    Game Date: {{ date('D m/d/y', strtotime($gamedata->gamedate)) }} <br>
    Time: {{ date('h:i a', strtotime($gamedata->gamedate_toDisplay)) }} <br>
    Location: {{ $gamedata->location->ground }}
    <br>
    <br>
    All other game info can be viewed on the website.
    <br>
    <br>
    Thank you for being an essential part of Umpire Central. Your dedication to the sport makes every game a success!
    <br>
    <br>
    Warm regards,
    <br>
    Umpire Central
@endsection
