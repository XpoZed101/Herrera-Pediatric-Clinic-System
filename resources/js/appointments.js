// resources/js/appointments.js
// Segregated JS for appointments page: loads SweetAlert, handles confirmation, and status toasts

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

async function initAppointmentsPage() {
  const Swal = await loadSweetAlert();

  const root = document.getElementById('appointments-page');
  if (!root) return;

  const status = root.dataset.status || '';
  const error = root.dataset.error || '';

  // Show busy success toast after appointment submission
  if (status) {
    Swal.fire({
      icon: 'success',
      title: 'Appointment Requested',
      html: `<div style="text-align:left">
        <p>We’re reviewing your request now. Expect a confirmation shortly.</p>
        <ul style="margin-top:.5rem;padding-left:1rem">
          <li>Bring vaccination card and medications list.</li>
          <li>Arrive 10 minutes early for check-in.</li>
          <li>Reschedule easily if anything changes.</li>
        </ul>
      </div>`,
      confirmButtonText: 'Got it',
    });
  }

  // Show error if user has an active appointment not completed
  if (error) {
    Swal.fire({
      icon: 'error',
      title: 'Active Appointment',
      text: error,
      confirmButtonText: 'Ok',
    });
  }

  // Intercept form submit for confirmation dialog
  const form = document.getElementById('appointment-form');
  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const result = await Swal.fire({
        icon: 'question',
        title: 'Submit Appointment?',
        text: 'We’ll notify you as soon as we confirm your visit.',
        showCancelButton: true,
        confirmButtonText: 'Submit',
        cancelButtonText: 'Cancel',
      });
      if (result.isConfirmed) form.submit();
    });
  }

  // Disable booked time options when date changes
  const dateInput = document.getElementById('scheduled_date');
  const timeSelect = document.getElementById('scheduled_time');
  async function refreshAvailability() {
    if (!dateInput || !timeSelect || !dateInput.value) return;
    try {
      const res = await fetch(`/client/appointments/available-times?date=${encodeURIComponent(dateInput.value)}`, {
        headers: { 'Accept': 'application/json' }
      });
      if (!res.ok) return;
      const data = await res.json();
      const booked = new Set(data.booked || []);

      // Reset and disable booked options
      [...timeSelect.options].forEach(opt => {
        if (!opt.value) return; // skip placeholder
        const isBooked = booked.has(opt.value);
        opt.disabled = isBooked;
        opt.textContent = opt.textContent.replace(/\s*\(Booked\)$/,'');
        if (isBooked) opt.textContent += ' (Booked)';
      });

      // If currently selected time became disabled, clear selection
      const selected = timeSelect.value;
      if (selected && booked.has(selected)) {
        timeSelect.value = '';
      }
    } catch (e) {
      // silently ignore network errors
    }
  }

  if (dateInput) {
    dateInput.addEventListener('change', refreshAvailability);
    // Preload availability when page loads if date already chosen
    if (dateInput.value) refreshAvailability();
  }
}

document.addEventListener('DOMContentLoaded', initAppointmentsPage);