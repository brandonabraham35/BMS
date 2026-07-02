@extends('emails.layout')

@section('content')
    <p>Hello,</p>
    <p>You have been invited to join <strong>{{ $companyName }}</strong> on BMS Enterprise.</p>
    <p>To accept your invitation and set up your account, click the button below:</p>
    <p>
        <a href="{{ $acceptUrl }}" class="button">Accept Invitation</a>
    </p>
    <p>This invitation will expire in 7 days.</p>
    <p>If you were not expecting this invitation, you can safely ignore this email.</p>
@endsection
