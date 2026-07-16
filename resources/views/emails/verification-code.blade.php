@component('mail::message')
# Verify Your Email

Hi {{ $name }},

Thanks for registering with **Quiet Hours**. Use the code below to verify your email address:

@component('mail::panel')
<div style="font-size: 32px; letter-spacing: 8px; text-align: center; font-weight: bold;">
{{ $code }}
</div>
@endcomponent

This code will expire in **10 minutes**. If you didn't create an account, you can safely ignore this email.

Best regards,

**Quiet Hours Team**

---

*This is an automated email. Please do not reply directly to this message.*
@endcomponent
