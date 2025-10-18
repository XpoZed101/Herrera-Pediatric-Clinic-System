document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('flash-status');
  if (!el) return;

  const message = el.dataset.message || '';

  const fireAlert = () => {
    if (window.Swal && typeof window.Swal.fire === 'function') {
      window.Swal.fire({
        title: 'Registration Submitted',
        text: message,
        icon: 'success',
        confirmButtonText: 'OK',
      });
    } else {
      // Wait until SweetAlert2 CDN loads
      setTimeout(fireAlert, 50);
    }
  };

  fireAlert();
});