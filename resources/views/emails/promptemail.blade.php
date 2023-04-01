@component('mail::message')
# Customer's Feedback for the day

Hello branch manager {{$details->email}}.
The total feedback for the day is from your clients.
The negative feedback is. And positive feedback is.
Lets work harder for clients to appreciate our hardwork.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
