<div class="busy-toast" id="busyToast" role="status" aria-live="polite" hidden>
    <span class="busy-spinner" aria-hidden="true"></span>
    <span id="busyToastText">Working…</span>
</div>

<script>
    (function () {
        const toast = document.getElementById('busyToast');
        const toastText = document.getElementById('busyToastText');

        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (event.defaultPrevented || form.hasAttribute('data-no-busy')) return;

            if (form.dataset.busy) {
                event.preventDefault();
                return;
            }

            form.dataset.busy = '1';

            const files = [...form.querySelectorAll('input[type="file"]')].reduce((count, input) => count + (input.files ? input.files.length : 0), 0);
            const method = (form.querySelector('input[name="_method"]')?.value || form.method || '').toUpperCase();
            const button = event.submitter || form.querySelector('[type="submit"]');

            let label = 'Saving…';
            if (files) label = files > 1 ? 'Uploading & optimising ' + files + ' photos…' : 'Uploading & optimising photo…';
            else if (form.hasAttribute('data-sends-code')) label = 'Sending code…';
            else if (method === 'DELETE') label = 'Deleting…';

            if (button) {
                button.dataset.label = button.textContent;
                button.textContent = label;
                button.classList.add('is-busy');
                setTimeout(() => { button.disabled = true; }, 0);
            }

            if (files || form.hasAttribute('data-sends-code')) {
                toastText.textContent = files
                    ? 'Uploading and optimising your photos. Large photos take a few seconds each — please keep this page open.'
                    : 'Sending the code to your email. This can take a few seconds.';
                toast.hidden = false;
            }
        });

        window.addEventListener('pageshow', (event) => {
            if (!event.persisted) return;
            toast.hidden = true;
            document.querySelectorAll('form[data-busy]').forEach((form) => {
                delete form.dataset.busy;
                form.querySelectorAll('.is-busy').forEach((button) => {
                    button.textContent = button.dataset.label;
                    button.disabled = false;
                    button.classList.remove('is-busy');
                });
            });
        });
    })();
</script>
