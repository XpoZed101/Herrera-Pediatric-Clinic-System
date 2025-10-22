document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('vitals-modal');
  if (!modal) return;
  const form = modal.querySelector('.js-vitals-form');
  const overlay = modal.querySelector('.js-modal-overlay');
  const closeBtn = modal.querySelector('.js-close-modal');

  const open = (url) => {
    if (form) {
      form.setAttribute('action', url);
    }
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  };

  const close = () => {
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  };

  document.querySelectorAll('.js-open-vitals').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const url = btn.getAttribute('data-vitals-url');
      if (url) open(url);
    });
  });

  if (overlay) overlay.addEventListener('click', close);
  if (closeBtn) closeBtn.addEventListener('click', close);
});