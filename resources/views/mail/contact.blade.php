@extends('mail.layouts.main')
@section('main-container')
    <div>Name:{{ $data['name'] }}</div>
    <div>League Name:{{ $data['league_name'] }}</div>
    <div>Email:{{ $data['email'] }}</div>
    <div>Phone:{{ $data['phone'] }}</div>
    <div>Message:{{ $data['message'] }}</div>
@endsection
