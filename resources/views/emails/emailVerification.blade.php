@component('mail::message')
# Welcome to {{ config('app.name') }}

Dear {{$user->name}},<br>
Please click the button below to activate your account.
@php
    $verificationUrl = url("/")."/email-verification?email=".$user->email."&token=".$user->email_verification_token;
@endphp
@component('mail::button', ['url' => $verificationUrl])
Activate my account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
