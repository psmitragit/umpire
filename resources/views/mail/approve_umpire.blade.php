@extends('mail.layouts.main')
@section('main-container')
    Dear {{ $umpire->name }},
    <br>
    <br>
    We're excited to inform you that your request to join {{ $league->leaguename }} has been accepted.
    <br>
    <br>
    Head over to UmpireCentral.com and familiarize yourself with the league.
    <br>
    <br>
    Ensure your notification settings are correctly configured so you're always informed about new games, reschedules, and
    other important updates.
    <br>
    <br>
    If you have questions or need assistance, please feel free to reach out to us at support@umpirecentral.com.
    <br>
    <br>
    Thank you for being a valuable member of the Umpire Central community!
    <br>
    <br>
    Warm regards,<br>
    Umpire Central Support
@endsection
