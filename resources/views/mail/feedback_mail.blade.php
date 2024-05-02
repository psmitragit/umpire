@extends('mail.layouts.main')
@section('main-container')
Name: {{ $data['feedbackSenderName'] }}<br>
Email: {{ $data['feedbackSenderEmail'] }}<br>
Message: {{ $data['feedback_message'] }}
@endsection
