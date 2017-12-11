@component('mail::message')
<h2 style="text-align: center">{{ __("Account Activation") }}</h2>

<p style="text-align: center">{{ __('To continue your registration please click on the button below') }}</p>

@component('mail::button', ['url' => 'http://url.com', 'color' => 'green'])
Activate your account
@endcomponent

Thanks,<br>
{{ config('app.name') }} team
@endcomponent