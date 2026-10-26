<form id="otpResendForm" method="POST" action="{{ $action }}" data-sends-code hidden>
    @csrf
</form>

<script>
    (function () {
        const box = document.querySelector('[data-resend-wait]');
        if (!box) return;

        const button = box.querySelector('[data-resend-btn]');
        const timer = box.querySelector('[data-resend-timer]');
        let wait = Number(box.dataset.resendWait) || 0;

        if (!wait) return;

        const tick = setInterval(() => {
            wait -= 1;
            if (wait > 0) {
                timer.textContent = ' in ' + wait + 's';
                return;
            }
            clearInterval(tick);
            timer.textContent = '';
            button.disabled = false;
        }, 1000);
    })();
</script>
