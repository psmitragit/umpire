@extends('mail.layouts.main')
@section('main-container')
    Dear {{ $umpire->name }},
    <br>
    <br>
    Thank you for expressing interest in joining the {{ $league->leaguename }}. We appreciate the time and effort you took to apply.
    <br>
    <br>
    After careful consideration, we regret to inform you that your application has been denied.
    <br>
    <br>
    We encourage you to:
    <br>
    <br>
    Keep an eye on {{ url('/') }} for future opportunities. New leagues and seasons might have different
    requirements or more openings.
    <br>
    <br>
    Engage with the Community: Being active in our online umpire forums or workshops can increase your visibility and
    provide networking opportunities.
    <br>
    <br>
    Reapply in the Future: Our needs and the dynamics of the leagues we service can change from season to season. We'd be
    glad to see your application again in the future.
    <br>
    <br>
    We truly value your interest in becoming a part of {{ $league->leaguename }}. If you have any questions or would like feedback,
    please feel free to reach out to us at support@umpirecentral.com.
    <br>
    <br>
    Thank you for being a member of the Umpire Central community. We wish you all the best in your umpiring journey and
    hope for possible collaborations in the future.
    <br>
    <br>
    Warm regards,<br>
    Umpire Central Support
@endsection
