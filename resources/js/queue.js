/* Queue page interactions: reordering and real-time refresh */
(() => {
  const getToken = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  const getReorderUrl = () => document.getElementById('queue-config')?.dataset.reorderUrl || '';

  const loadSwal = () => new Promise((resolve) => {
    if (window.Swal) return resolve(window.Swal);
    const s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    s.async = true;
    s.onload = () => resolve(window.Swal);
    document.head.appendChild(s);
  });

  const attachHandlers = () => {
    document.querySelectorAll('tr[data-appointment-id] .js-reorder').forEach((btn) => {
      if (btn.__bound) return;
      btn.__bound = true;
      btn.addEventListener('click', async (e) => {
        const btnEl = e.currentTarget;
        const row = btnEl.closest('tr[data-appointment-id]');
        const appointmentId = Number(row?.dataset.appointmentId);
        const direction = btnEl.dataset.direction;
        const url = getReorderUrl();
        if (!url || !appointmentId) return;
        const token = getToken();

        const posEl = row.querySelector('.js-position');
        const currentPos = posEl ? Number((posEl.textContent || '').trim()) : null;

        btnEl.disabled = true;
        try {
          const resp = await fetch(url, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json',
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ appointment_id: appointmentId, direction })
          });
          const data = await resp.json();
          const Swal = await loadSwal();
          if (data?.ok) {
            if (posEl) posEl.textContent = data.queue_position ?? '—';
            const tbody = row?.parentElement;
            if (tbody) {
              if (direction === 'up') {
                const prev = row.previousElementSibling;
                if (prev) tbody.insertBefore(row, prev);
                const prevPosEl = prev?.querySelector('.js-position');
                if (prevPosEl && currentPos != null) prevPosEl.textContent = String(currentPos);
              } else {
                const next = row.nextElementSibling;
                if (next) tbody.insertBefore(row, next.nextElementSibling);
                const nextPosEl = next?.querySelector('.js-position');
                if (nextPosEl && currentPos != null) nextPosEl.textContent = String(currentPos);
              }
            }
            Swal.fire({ icon: 'success', title: 'Queue updated', text: 'Position adjusted successfully.', timer: 1200, showConfirmButton: false });
          } else {
            const message = data?.message || 'Unable to update queue.';
            const Swal = await loadSwal();
            Swal.fire({ icon: 'error', title: 'Update failed', text: message });
          }
        } catch (err) {
          const Swal = await loadSwal();
          Swal.fire({ icon: 'error', title: 'Network error', text: 'Please try again.' });
        } finally {
          btnEl.disabled = false;
        }
      });
    });
  };

  const refreshQueueTable = async () => {
    try {
      const res = await fetch(location.href, {
        headers: { 'Accept': 'text/html', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
      });
      const html = await res.text();
      const doc = new DOMParser().parseFromString(html, 'text/html');
      const newTbody = doc.querySelector('table tbody');
      const tbody = document.querySelector('table tbody');
      if (newTbody && tbody) {
        tbody.innerHTML = newTbody.innerHTML;
        attachHandlers();
      }
    } catch (e) {
      // Silently ignore refresh errors
    }
  };

  let pollTimer;
  const startPolling = () => {
    if (pollTimer) clearInterval(pollTimer);
    pollTimer = setInterval(refreshQueueTable, 10000); // 10s interval
  };

  const init = () => {
    attachHandlers();
    startPolling();
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
  } else {
    init();
  }
  document.addEventListener('livewire:navigated', init);
  window.addEventListener('pageshow', init);
})();