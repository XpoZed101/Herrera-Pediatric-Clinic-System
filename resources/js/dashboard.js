(() => {
  const ensureChart = (cb) => {
    if (typeof Chart !== 'undefined') return cb();
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js';
    script.onload = cb;
    document.head.appendChild(script);
  };

  const palette = ['#3b82f6', '#ef4444', '#f59e0b', '#10b981', '#6366f1', '#22c55e'];

  const makeDoughnut = (canvasId, colors) => {
    const el = document.getElementById(canvasId);
    if (!el) return;

    const labels = (() => { try { return JSON.parse(el.dataset.labels || '[]'); } catch { return []; } })();
    const counts = (() => { try { return JSON.parse(el.dataset.counts || '[]'); } catch { return []; } })();

    ensureChart(() => {
      const existing = Chart.getChart(el);
      if (existing) existing.destroy();
      const ctx = el.getContext('2d');
      new Chart(ctx, {
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
    });
  };

  const init = () => {
    makeDoughnut('statusDoughnut', palette);
    makeDoughnut('visitTypeDoughnut', palette);
  };

  // Handle various SPA-like navigation events so charts render without manual refresh
  document.addEventListener('DOMContentLoaded', init);
  window.addEventListener('pageshow', init);
  document.addEventListener('turbo:load', init);
  document.addEventListener('livewire:load', init);
  // Ensure charts re-initialize after Livewire SPA navigation clicks
  document.addEventListener('livewire:navigated', init);
})();