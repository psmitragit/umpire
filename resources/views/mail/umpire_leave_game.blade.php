@extends('mail.layouts.main')
@section('main-container')
    @php
        $league = $data['league'];
        $umpire = $data['umpire'];
        $game = $data['game'];
    @endphp
    Dear {{ $league->name }},
    <br>
    <br>
    We regret to inform you that an umpire has canceled their game assignment for an upcoming game. Please see the details
    below:
    <br>
    <br>
    League: {{ $league->leaguename }} <br>
    Game Date: {{ date('D m/d/y', strtotime($game->gamedate)) }} <br>
    Time: {{ date('h:i a', strtotime($game->gamedate_toDisplay)) }} <br>
    Umpire: {{ $umpire->name }}
    <br>
    <br>
    We understand the importance of having a complete umpire crew for your games and apologize for any inconvenience this
    may cause. To assist you in finding a replacement, please log in to your Umpire Central account where you can view
    available umpires and reschedule as needed.
    <br>
    <br>
    <a href='{{ url('league/games') }}'>Schedule Games</a>
    <br>
    <br>
    We appreciate your understanding and prompt attention to this matter. If you require additional support, please do not
    hesitate to reach out to us.
    <br>
    <br>
    Thank you for your cooperation and for being an integral part of the Umpire Central community.
    <br>
    <br>
    Warm regards,
    <br>
    Umpire Central Support
@endsection
