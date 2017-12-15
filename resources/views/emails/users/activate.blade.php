@component('mail::message')
<h2 style="text-align: center">{{ __("Account activation") }}</h2>

<p style="text-align: center">{{ __('To continue your registration please click on the button below') }}</p>

@component('mail::button', ['url' => $user->activate_link, 'color' => 'green'])
{{ __('Activate your account') }}
@endcomponent

{{ __('Thanks') }},<br>
{{ __(config('app.name').' team') }}
@endcomponent