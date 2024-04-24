@extends('mail.layouts.main')
@section('main-container')
    Dear {{ $umpire->name }},
    <br>
    <br>
    The league admin of {{ $league->leaguename }} would like more information before making a decision on your application.
    <br>
    <br>
    Please contact the admin at {{ $league->phone }}.
    <br>
    <br>
    From here all communication will be outside of Umpire Central until a decision has been made.
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
