@component('mail::message')
# Customer's Feedback for the day

Hello branch manager {{$details->email}}.
We received a total of {{$details->comment}} feedback from clients at your branch today.
From the feedback, {{$details->No}} were negative feedback and  {{$details->Yes}} were positive feedback.
Lets work harder for clients to appreciate our hardwork.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
