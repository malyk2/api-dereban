@component('mail::message')
<h2 style="text-align: center">{{ __("Thanks for registration!") }}</h2>

<p>{{ __('Hello'). ', ' . $user->name . ' ' . __('and welcome to Dereban world!') }}</p>

Best regards,<br>
{{ config('app.name') }} team
@endcomponent