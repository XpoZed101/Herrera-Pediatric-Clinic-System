// Use global SweetAlert2 (from CDN) when available; no direct imports.
(function () {
  function withSwal(callback) {
    if (window.Swal && typeof window.Swal.fire === 'function') {
      callback(window.Swal);
      return;
    }
    // Load CDN if not present
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    script.async = true;
    script.onload = function () { callback(window.Swal); };
    document.head.appendChild(script);
  }

  function initFlashToasts(Swal) {
    const el = document.getElementById('flash-messages');
    if (!el) return;

    const status = el.dataset.status || '';
    const statusUpdated = el.dataset.statusUpdated || '';
    const emailSent = el.dataset.emailSent || '';
    const errorMsg = el.dataset.error || '';

    const showToast = (title, text, icon = 'success') => {
      Swal.fire({
        icon,
        title,
        text,
        toast: true,
        position: 'top-end',
        timer: 2500,
        showConfirmButton: false,
      });
    };

    if (errorMsg) {
      showToast('Error', errorMsg, 'error');
    } else if (emailSent) {
      showToast('Email Sent', emailSent, 'success');
    } else if (statusUpdated) {
      showToast('Status Updated', statusUpdated, 'success');
    } else if (status) {
      showToast('Success', status, 'success');
    }
  }

  function initConfirmForms(Swal) {
    const forms = Array.from(document.querySelectorAll('form.js-confirm, form[data-confirm]'));
    forms.forEach((form) => {
      if (form.__swalHooked) return; // avoid double-binding
      form.__swalHooked = true;

      form.addEventListener('submit', async (e) => {
        // Skip confirmation for programmatic submits
        if (form.__swalSubmitting) return;

        e.preventDefault();

        const title = form.getAttribute('data-confirm-title') || 'Are you sure?';
        let text = form.getAttribute('data-confirm-text') || 'This will change records.';
        const confirmText = form.getAttribute('data-confirm-submit-text') || 'Confirm';

        const statusSelect = form.querySelector('select[name="status"]');
        const statusLabel = statusSelect ? (statusSelect.options[statusSelect.selectedIndex]?.text || statusSelect.value) : '';
        if (statusLabel) {
          text = `${text} New status: ${statusLabel}.`;
        }

        const result = await Swal.fire({
          icon: 'question',
          title,
          text,
          showCancelButton: true,
          confirmButtonText: confirmText,
          cancelButtonText: 'Cancel',
          confirmButtonColor: '#2563eb',
        });

        if (result.isConfirmed) {
          form.__swalSubmitting = true;
          form.submit();
        }
      });
    });
  }

  function boot(Swal) {
    const init = () => {
      initFlashToasts(Swal);
      initConfirmForms(Swal);
    };
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', init);
    } else {
      init();
    }
  }

  withSwal(boot);
})();