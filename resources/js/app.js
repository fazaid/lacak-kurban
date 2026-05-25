// NPC Kurban Tracker — global JS

document.addEventListener('DOMContentLoaded', () => {

    // ── Flash messages: auto-dismiss after 4 s ───────────────────────
    document.querySelectorAll('[data-flash]').forEach(el => {
        const dismiss = () => {
            el.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            el.style.opacity    = '0';
            el.style.transform  = 'translateY(-6px)';
            setTimeout(() => el.remove(), 300);
        };

        // Manual close button (if present)
        el.querySelector('[data-flash-close]')?.addEventListener('click', dismiss);

        // Auto-dismiss
        setTimeout(dismiss, 4000);
    });

    // ── CSRF token for fetch() calls ─────────────────────────────────
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    window.fetchWithCsrf = (url, options = {}) =>
        fetch(url, {
            ...options,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                ...(options.headers ?? {}),
            },
        });

    // ── Confirm-delete: data-confirm attribute on forms ──────────────
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', e => {
            const msg = form.dataset.confirm || 'Yakin ingin melanjutkan?';
            if (!confirm(msg)) e.preventDefault();
        });
    });

    // ── Active nav link highlight (admin sidebar) ────────────────────
    const currentPath = window.location.pathname;
    document.querySelectorAll('[data-nav-link]').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('bg-white/10', 'font-semibold');
        }
    });

});
