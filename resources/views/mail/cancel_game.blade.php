@extends('mail.layouts.main')
@section('main-container')
    Dear {{ $umpire->name }},
    <br>
    <br>
    We regret to inform you that the following game you were scheduled to umpire has been canceled:
    <br>
    <br>
    League: {{ $game->league->leaguename }} <br>
    Game Date: {{ date('D m/d/y', strtotime($game->gamedate)) }} <br>
    Time: {{ date('h:i a', strtotime($game->gamedate_toDisplay)) }} <br>
    Location: {{ $game->location->ground }}
    <br>
    <br>
    The cancellation has been initiated by {{ $game->league->leaguename }}. For more details regarding this cancellation or if you
    have any questions, we encourage you to contact the league directly.
    <br>
    <br>
    We understand that game cancellations can be inconvenient, and we appreciate your understanding and flexibility. Your
    schedule on Umpire Central has been updated to reflect this change.
    <br>
    <br>
    Thank you for your commitment to providing professional umpiring services. We value your contribution to the Umpire
    Central community and look forward to your continued participation in upcoming games.
    <br>
    <br>
    Best regards,
    <br>
    Umpire Central Support
@endsection
