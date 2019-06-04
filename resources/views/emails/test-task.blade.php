@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => url($startTestLink)])
    Start Task
@endcomponent

{{--@component('mail::button', ['url' => url($finishTestLink)])--}}
{{--    Finish Task--}}
{{--@endcomponent--}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
