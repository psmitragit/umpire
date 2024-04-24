@extends('mail.layouts.main')
@section('main-container')
@php
    $league = $data['league'];
    $umpire = $data['umpire_data'];
@endphp
Dear {{ $league->name }},
<br>
<br>
We are pleased to notify you that an umpire has applied to join your league. Here are the preliminary details of the applicant:
<br>
<br>
Name: {{ $umpire->name }} <br>
Age: {{ get_age($umpire->dob) }}
<br>
<br>
Next Steps:
To review the full application, verify qualifications, and accept the umpire into your league, please log in to your Umpire Central account and visit your Dashboard.
<br>
<br>
<a href="{{ url('league/view-new-applicants') }}">Review Application</a>
<br>
<br>
We recommend reviewing the application at your earliest convenience to maintain efficient game scheduling. If you have any questions or require assistance during the review process, our support team is here to help.
<br>
<br>
Thank you for choosing Umpire Central to find professional umpires for your games. We are committed to supporting you in every step of the process.
<br>
<br>
Best regards,
<br>
Umpire Central Support
@endsection
