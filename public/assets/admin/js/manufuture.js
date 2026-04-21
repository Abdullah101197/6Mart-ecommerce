(() => {
  const toggleBtn = document.getElementById('mf-sidebar-toggle');
  const sidebar = document.getElementById('mf-sidebar');
  if (!toggleBtn || !sidebar) return;

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('mf-collapsed');
  });
})();

(() => {
  const frames = document.querySelectorAll('iframe[data-mf-frame="1"]');
  if (!frames.length) return;

  function computeHeight(frame) {
    const offsetAttr = frame.getAttribute('data-mf-offset');
    const offset = offsetAttr ? parseInt(offsetAttr, 10) : 220;
    const h = Math.max(320, window.innerHeight - (Number.isFinite(offset) ? offset : 220));
    return `${h}px`;
  }

  function resizeAll() {
    frames.forEach((frame) => {
      frame.style.height = computeHeight(frame);
    });
  }

  window.addEventListener('resize', resizeAll);
  resizeAll();
})();

