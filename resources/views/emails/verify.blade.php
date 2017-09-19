@component('mail::message')
Hello,

You are receiving this email because you created a account on Castle.

@component('mail::button', ['url' => $link])
    Verify
@endcomponent

If you did not request a password reset, no further action is required.

Regards,
Castle<br>

If you’re having trouble clicking the Verify button, copy and paste the URL below
into your web browser: [{{ $link }}]({{ $link }})
@endcomponent
