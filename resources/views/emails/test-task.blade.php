@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => url('/start-test/' . $uniqueKey)])
    Start Task
@endcomponent

@component('mail::button', ['url' => url('/finish-test/' . $uniqueKey)])
    Finish Task
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
