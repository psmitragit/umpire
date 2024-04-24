@extends('mail.layouts.main')
@section('main-container')
Dear {{ $ump->name }},
<br>
<br>
You have a new message from the administration of {{ $league->leaguename }}.
<br>
<br>
{{ $leaguemsg }}
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
