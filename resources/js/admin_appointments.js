// resources/js/admin_appointments.js
// SweetAlert feedback for admin appointments index after status updates

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

async function initAdminAppointmentsPage() {
  const root = document.getElementById('admin-appointments-page');
  if (!root) return;

  const statusMsg = root.dataset.statusUpdated || '';
  const emailMsg = root.dataset.emailSent || '';

  if (statusMsg || emailMsg) {
    const Swal = await loadSweetAlert();
    const isEmail = !!emailMsg;
    Swal.fire({
      icon: isEmail ? 'success' : 'success',
      title: isEmail ? 'Email Sent' : 'Status Updated',
      text: isEmail ? emailMsg : statusMsg,
      confirmButtonText: 'OK',
    });
  }
}

document.addEventListener('DOMContentLoaded', initAdminAppointmentsPage);