{{-- Resend code: the form lives outside the main OTP form and the button points at it. --}}
<div class="otp-resend" data-resend-wait="{{ $resendWait }}">
    <span class="otp-resend-text">Didn't get the code, or did it expire?</span>
    <button type="submit" form="otpResendForm" class="otp-resend-btn" data-resend-btn {{ $resendWait ? 'disabled' : '' }}>
        Resend code<span data-resend-timer>{{ $resendWait ? ' in ' . $resendWait . 's' : '' }}</span>
    </button>
</div>
