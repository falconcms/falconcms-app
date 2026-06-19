(function () {
    'use strict';

    const MSG = 'This action is disabled on the demo site.';
    const path = window.location.pathname;

    // ── Tooltip ──────────────────────────────────────────────────────────────
    let tip = null;
    function getTip() {
        if (tip) return tip;
        tip = document.createElement('div');
        tip.style.cssText = [
            'position:fixed',
            'background:#1d2327',
            'color:#fff',
            'padding:5px 10px',
            'border-radius:4px',
            'font-size:12px',
            'z-index:99999',
            'pointer-events:none',
            'white-space:nowrap',
            'display:none',
            'font-family:sans-serif',
        ].join(';');
        document.body.appendChild(tip);
        return tip;
    }

    function bindTooltip(el, message) {
        el.addEventListener('mouseenter', function () {
            const t = getTip();
            t.textContent = message;
            t.style.display = 'block';
        });
        el.addEventListener('mousemove', function (e) {
            const t = getTip();
            t.style.left = (e.clientX + 14) + 'px';
            t.style.top  = (e.clientY - 34) + 'px';
        });
        el.addEventListener('mouseleave', function () {
            getTip().style.display = 'none';
        });
    }

    function disableInput(el, message) {
        if (!el) return;
        el.disabled = true;
        el.style.cursor = 'not-allowed';
        el.style.opacity = '0.55';
        bindTooltip(el, message || MSG);
    }

    function disableButton(btn, message) {
        if (!btn) return;
        btn.disabled = true;
        btn.style.cursor = 'not-allowed';
        btn.style.opacity = '0.55';
        btn.style.pointerEvents = 'auto'; // keep so tooltip works
        btn.addEventListener('click', function (e) { e.preventDefault(); });
        bindTooltip(btn, message || MSG);
    }

    function disableLink(a, message) {
        if (!a) return;
        a.style.pointerEvents = 'none';
        a.style.cursor = 'not-allowed';
        a.style.opacity = '0.55';
        a.addEventListener('click', function (e) { e.preventDefault(); });
        bindTooltip(a, message || MSG);
    }

    // ── Users index (/admin/users) ────────────────────────────────────
    if (/\/admin\/users\/?$/.test(path)) {
        document.querySelectorAll('a[href*="users/create"]').forEach(function (a) {
            disableLink(a, MSG);
        });
    }

    // ── Users edit (/admin/users/{id}/edit) ───────────────────────────
    if (/\/admin\/users\/\d+\/edit/.test(path)) {
        // Disable submit button
        disableButton(document.querySelector('button[type="submit"]'), MSG);

        // Disable password fields
        disableInput(document.getElementById('password'), MSG);
        disableInput(document.getElementById('password_confirmation'), MSG);

        // Disable role checkboxes
        document.querySelectorAll('input[name="roles[]"]').forEach(function (cb) {
            disableInput(cb, MSG);
        });

        // Disable name / email / username fields too
        ['username', 'name', 'email'].forEach(function (id) {
            disableInput(document.getElementById(id), MSG);
        });
    }

    // ── Users create (/admin/users/create) ─────────────────────────────
    if (/\/admin\/users\/create/.test(path)) {
        disableButton(document.querySelector('button[type="submit"]'), MSG);
        document.querySelectorAll('input, select, textarea').forEach(function (el) {
            disableInput(el, MSG);
        });
    }

    // ── Settings page (/admin/settings) ───────────────────────────────
    if (/\/admin\/settings/.test(path)) {
        const mdCheckbox = document.getElementById('allow_multi_device');
        const maxDevices = document.getElementById('max_devices');

        if (mdCheckbox) {
            mdCheckbox.checked  = true;
            mdCheckbox.disabled = true;
            mdCheckbox.style.cursor  = 'not-allowed';
            mdCheckbox.style.opacity = '0.7';
            bindTooltip(mdCheckbox, MSG);
        }

        if (maxDevices) {
            // Show -1 (unlimited) but backend stores 9999
            maxDevices.value   = '-1';
            maxDevices.min     = '-1';
            maxDevices.disabled = true;
            maxDevices.style.cursor  = 'not-allowed';
            maxDevices.style.opacity = '0.7';
            bindTooltip(maxDevices, 'Unlimited — demo site restriction');

            // Update the description text
            const desc = maxDevices.nextElementSibling;
            if (desc) desc.textContent = '-1 = Unlimited concurrent sessions (demo site default).';
        }
    }

    // ── My Account — password tab (frontend) ─────────────────────────────
    // Target by form action since the URL slug is dynamic
    document.querySelectorAll('form[action*="account-password-update"]').forEach(function (form) {
        form.querySelectorAll('input').forEach(function (el) { disableInput(el, MSG); });
        form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach(function (btn) {
            disableButton(btn, MSG);
        });
    });
})();
