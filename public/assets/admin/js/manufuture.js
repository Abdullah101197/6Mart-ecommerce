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

  function injectCleanStyles(doc) {
    if (!doc || !doc.head || doc.getElementById('mf-embed-clean-style')) return;
    const style = doc.createElement('style');
    style.id = 'mf-embed-clean-style';
    style.textContent = `
      /* Hide embedded vendor chrome */
      /* IMPORTANT: avoid generic 'header/aside/footer' selectors (they can be used inside page content) */
      #headerMain, #headerFluid, #headerDouble,
      #sidebarMain,
      .js-navbar-vertical-aside,
      .footer {
        display: none !important;
      }
      /* Remove vendor layout paddings/margins */
      body { padding: 0 !important; margin: 0 !important; overflow-x: hidden !important; }
      main, #content, .main {
        margin: 0 !important;
        padding: 0 !important;
      }
      /* Common vendor "main" wrapper */
      .main { width: 100% !important; }
    `;
    doc.head.appendChild(style);
  }

  function tryCleanFrame(frame) {
    const mode = frame.getAttribute('data-mf-clean') || '';
    if (!mode) return;
    try {
      const doc = frame.contentDocument || frame.contentWindow?.document;
      if (!doc) return;
      if (mode === 'vendor-panel') {
        injectCleanStyles(doc);
      }
    } catch (e) {
      // Ignore cross-origin access errors
    }
  }

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

  frames.forEach((frame) => {
    frame.addEventListener('load', () => {
      tryCleanFrame(frame);
    });
    // In case it's already loaded from cache
    setTimeout(() => tryCleanFrame(frame), 50);
  });

  window.addEventListener('resize', resizeAll);
  resizeAll();
})();

