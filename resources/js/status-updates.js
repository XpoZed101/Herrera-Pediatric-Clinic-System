// resources/js/status-updates.js
// Handles status updates and loading states with SweetAlert2

function loadSweetAlert() {
    return new Promise((resolve) => {
        if (window.Swal) return resolve(window.Swal);
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        script.async = true;
        script.onload = () => resolve(window.Swal);
        document.head.appendChild(script);
    });
}

// Bind delegated listeners once to survive Livewire navigation
(async function bindDelegatesOnce() {
    const Swal = await loadSweetAlert();
    if (window.__statusUpdatesBound) return;

    // Delegated submit handler for forms with js-confirm
    document.addEventListener('submit', async (e) => {
        const form = e.target;
        if (!form || !form.matches('form.js-confirm')) return;
        e.preventDefault();

        const title = form.dataset.confirmTitle || 'Confirm Action';
        const text = form.dataset.confirmText || 'Are you sure you want to proceed?';
        const submitText = form.dataset.confirmSubmitText || 'Proceed';

        const result = await Swal.fire({
            title,
            text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: submitText,
            cancelButtonText: 'Cancel',
        });

        if (!result.isConfirmed) return;

        // Show loading indicator while submitting
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we process your request.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => Swal.showLoading(),
        });

        const isStatusForm = /\/status$/.test(form.action);
        if (isStatusForm) {
            try {
                const fd = new FormData(form);
                const resp = await fetch(form.action, {
                    method: form.method || 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: fd,
                });

                if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
                const data = await resp.json();

                // Close loading and show success
                await Swal.fire({
                    icon: 'success',
                    title: data?.email_sent ? 'Email Queued' : 'Status Updated',
                    text: data?.message || 'Waitlist updated.',
                    confirmButtonText: 'OK',
                });

                // Reload to reflect updated counts/status badges
                window.location.reload();
            } catch (err) {
                // On error, close loading and fall back to standard submit
                Swal.close();
                form.submit();
            }
            return;
        }

        // Non-status forms: fall back to normal submit
        Swal.close();
        form.submit();
    });

    // Delegated change handler for status selects inside status forms
    document.addEventListener('change', async (e) => {
        const select = e.target;
        if (!select || select.tagName !== 'SELECT') return;
        const form = select.closest('form');
        if (!form || !/\/status$/.test(form.action)) return;

        const nextValue = select.value;
        const nextLabel = select.options[select.selectedIndex]?.text || nextValue;
        const prev = select.getAttribute('data-prev') || select.defaultValue || nextValue;

        const confirmResult = await Swal.fire({
            title: 'Update status?',
            text: `Change appointment status to "${nextLabel}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Update',
            cancelButtonText: 'Cancel',
        });

        if (!confirmResult.isConfirmed) {
            select.value = prev;
            return;
        }

        // Loading while updating status
        Swal.fire({
            title: 'Updating status...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => Swal.showLoading(),
        });

        try {
            const fd = new FormData(form);
            const resp = await fetch(form.action, {
                method: form.method || 'POST',
                headers: {
                    'Accept': 'application/json',
                },
                body: fd,
            });

            if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
            const data = await resp.json();

            // Success toast
            await Swal.fire({
                icon: 'success',
                title: 'Status Updated',
                text: data?.message || 'Appointment status updated.',
                confirmButtonText: 'OK',
            });

            // Persist current value for future cancels
            select.setAttribute('data-prev', nextValue);
        } catch (err) {
            // On failure, revert UI and fall back to normal submit
            select.value = prev;
            await Swal.fire({
                icon: 'error',
                title: 'Update failed',
                text: 'Could not update status. Falling back to standard submission.',
            });
            form.submit();
        }
    });

    window.__statusUpdatesBound = true;
})();

async function initStatusUpdates() {
    const Swal = await loadSweetAlert();

    // Show status/email messages from flash container
    const flash = document.getElementById('flash-messages');
    if (!flash) return;
    const { statusUpdated, emailSent, error } = flash.dataset;

    if (error) {
        Swal.fire({ icon: 'error', title: 'Error', text: error, confirmButtonText: 'OK' });
        return;
    }

    if (statusUpdated || emailSent) {
        const message = emailSent || statusUpdated;
        const isEmail = !!emailSent;
        Swal.fire({
            icon: 'success',
            title: isEmail ? 'Email Sent' : 'Status Updated',
            text: message,
            confirmButtonText: 'OK',
        });
    }
}

// Initialize on page load and on Livewire page updates
document.addEventListener('DOMContentLoaded', initStatusUpdates);
document.addEventListener('livewire:navigated', initStatusUpdates);
