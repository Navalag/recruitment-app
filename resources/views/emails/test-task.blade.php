{{--@component('mail::message')--}}
{{--# Introduction--}}

{{--The body of your message.--}}

{{--@component('mail::button', ['url' => url('/start-test/')])--}}
{{--    Start Task--}}
{{--@endcomponent--}}

{{--@component('mail::button', ['url' => url('/finish-test/')])--}}
{{--    Finish Task--}}
{{--@endcomponent--}}

{{--Thanks,<br>--}}
{{--{{ config('app.name') }}--}}
{{--@endcomponent--}}

<html>
<head></head>
<body style="background: black; color: white">
<h1>test</h1>
<p>{{ $uniqueKey }}</p>
</body>
</html>