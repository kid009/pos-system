import Swal from 'sweetalert2';

// resources/js/app.js (Vanilla JS - No Alpine.js)
document.addEventListener('DOMContentLoaded', function () {
    // Confirm before destructive actions using SweetAlert2
    document.addEventListener('submit', function (event) {
        const form = event.target;
        if (!form || !form.matches('[data-confirm-delete]')) {
            return;
        }

        event.preventDefault();

        const message = form.getAttribute('data-confirm-delete')
            || 'Are you sure you want to delete this record?';

        Swal.fire({
            title: 'Delete Confirmation',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#475569',
            reverseButtons: true,
            focusCancel: true,
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Prevent double-submit and show loading state on buttons with data-disable-on-submit
    document.addEventListener('submit', function (event) {
        if (event.defaultPrevented) {
            return;
        }

        const submitter = event.submitter;
        const button = submitter && submitter.matches('[data-disable-on-submit]')
            ? submitter
            : event.target.querySelector('[data-disable-on-submit]');

        if (!button || button.disabled) {
            return;
        }

        button.disabled = true;

        const loadingText = button.dataset.loadingText;
        if (loadingText) {
            const textSpan = button.querySelector('span');
            if (textSpan) {
                textSpan.textContent = loadingText;
            }
        }

        const spinnerSelector = button.dataset.loadingSpinner;
        if (spinnerSelector) {
            const spinner = document.querySelector(spinnerSelector);
            if (spinner) {
                spinner.classList.remove('hidden');
            }
        }
    });
});
