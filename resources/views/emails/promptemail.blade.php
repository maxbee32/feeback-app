
@component('mail::message')
# Customer's Feedback

Hello branch manager {{$details->email}}.
We received these feedback today on {{$details->date}} from your {{$details->branch}} branch.



| Customer's feedback for the day       |
| ------------------------------------  |
| Negative feedback {{$details->No}}.   |
| Positive feedback {{$details->Yes}}.  |
| Total feedback {{$details->comment}}. |




Lets work harder for clients to appreciate our hardwork.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
