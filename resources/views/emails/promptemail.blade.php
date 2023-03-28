@component('mail::message')
# Customer's Feedback for the day

Hello branch manager {{$details->email}}.
The daily feedback from your clients wasn't encouraging.
The Negative feedback received was more than the positive feedback.
Lets work harder for clients to appreciate our hardwork.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
