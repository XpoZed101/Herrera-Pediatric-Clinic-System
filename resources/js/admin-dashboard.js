(() => {
  const ensureChart = (cb) => {
    if (typeof Chart !== 'undefined') return cb();
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js';
    script.onload = cb;
    document.head.appendChild(script);
  };

  const palette = ['#f59e0b', '#3b82f6', '#10b981', '#ef4444', '#6366f1'];

  const charts = {};

  const makeOrUpdateDoughnut = (canvasId, labels, counts, colors) => {
    const el = document.getElementById(canvasId);
    if (!el) return;
    ensureChart(() => {
      const existing = Chart.getChart(el);
      if (existing) {
        existing.data.labels = labels;
        existing.data.datasets[0].data = counts;
        existing.data.datasets[0].backgroundColor = colors.slice(0, counts.length);
        existing.update();
      } else {
        const ctx = el.getContext('2d');
        charts[canvasId] = new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels,
            datasets: [{
              data: counts,
              backgroundColor: colors.slice(0, counts.length),
            }],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { position: 'bottom' },
              tooltip: { enabled: true },
            },
          },
        });
      }
    });
  };

  const initChartsFromDom = () => {
    const statusEl = document.getElementById('statusDoughnut');
    const visitEl = document.getElementById('visitTypeDoughnut');
    const parse = (el, key) => { try { return JSON.parse(el?.dataset?.[key] || '[]'); } catch { return []; } };
    const statusLabels = parse(statusEl, 'labels');
    const statusCounts = parse(statusEl, 'counts');
    const visitLabels = parse(visitEl, 'labels');
    const visitCounts = parse(visitEl, 'counts');
    makeOrUpdateDoughnut('statusDoughnut', statusLabels, statusCounts, palette);
    makeOrUpdateDoughnut('visitTypeDoughnut', visitLabels, visitCounts, palette);
  };

  let pollingTimer;
  const startPolling = () => {
    stopPolling();
    const poll = () => {
      fetch('/admin/dashboard/stats', { credentials: 'same-origin' })
        .then((r) => r.json())
        .then((data) => {
          const { statusLabels = [], statusCounts = [], visitTypeLabels = [], visitTypeCounts = [] } = data || {};
          makeOrUpdateDoughnut('statusDoughnut', statusLabels, statusCounts, palette);
          makeOrUpdateDoughnut('visitTypeDoughnut', visitTypeLabels, visitTypeCounts, palette);
        })
        .catch(() => {});
    };
    poll();
    pollingTimer = setInterval(poll, 15000);
  };
  const stopPolling = () => { if (pollingTimer) { clearInterval(pollingTimer); pollingTimer = null; } };

  const init = () => {
    initChartsFromDom();
    startPolling();
  };

  document.addEventListener('DOMContentLoaded', init);
  window.addEventListener('pageshow', init);
  document.addEventListener('turbo:load', init);
  document.addEventListener('livewire:load', init);
  document.addEventListener('livewire:navigated', init);
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) stopPolling(); else startPolling();
  });
})();