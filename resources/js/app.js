import './bootstrap';

/**
 * Barangay San Jose - Integrated System
 * Main JavaScript Entry Point
 */

// ----------------- Debounce helper -----------------
function debounce(fn, wait = 400) {
    let t;
    return function (...args) {
        clearTimeout(t);
        t = setTimeout(() => fn.apply(this, args), wait);
    };
}

document.addEventListener('DOMContentLoaded', function () {

    // -------------------------
    // Mobile Sidebar Toggle
    // -------------------------
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar       = document.getElementById('sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 1024) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.add('-translate-x-full');
                }
            }
        });
    }

    // -------------------------
    // Flash Message Auto-dismiss
    // -------------------------
    ['flash-success', 'flash-error', 'flash-warning'].forEach(id => {
        const el = document.getElementById(id);
        if (el) setTimeout(() => {
            el.style.transition = 'opacity 0.4s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }, 5000);
    });

    // -------------------------
    // FIX 2: AUTO-SEARCH on filter forms
    // Forms tagged data-auto-search submit on input/change.
    // Text inputs are debounced (400ms).
    // -------------------------
    document.querySelectorAll('form[data-auto-search]').forEach(form => {
        const submit = debounce(() => form.submit(), 450);
        form.querySelectorAll('input[type="text"], input[type="search"]').forEach(el => {
            el.addEventListener('input', submit);
        });
        form.querySelectorAll('select').forEach(el => {
            el.addEventListener('change', () => form.submit());
        });
        // Hide manual filter buttons in auto-search forms — submit handles itself
        form.querySelectorAll('[data-manual-filter]').forEach(btn => {
            btn.style.display = 'none';
        });
    });

    // -------------------------
    // Confirm Delete Buttons
    // -------------------------
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            const msg = this.dataset.confirm || 'Are you sure?';
            if (!confirm(msg)) e.preventDefault();
        });
    });

    // -------------------------
    // FIX 6 & 9: Generic "Other" conditional fields
    // Any <select data-conditional-target="#fieldId" data-conditional-value="other">
    // will show/hide and require its target when matching value is selected.
    // -------------------------
    document.querySelectorAll('[data-conditional-target]').forEach(trigger => {
        const targetSel = trigger.dataset.conditionalTarget;
        const matchVal  = trigger.dataset.conditionalValue;
        const target    = document.querySelector(targetSel);
        if (!target) return;

        const toggle = () => {
            const triggered = Array.isArray(matchVal.split(','))
                ? matchVal.split(',').map(s => s.trim()).includes(trigger.value)
                : trigger.value === matchVal;
            target.classList.toggle('hidden', !triggered);
            const input = target.querySelector('input, textarea, select');
            if (input) {
                if (triggered) {
                    input.setAttribute('required', 'required');
                } else {
                    input.removeAttribute('required');
                    input.value = '';
                }
            }
        };

        trigger.addEventListener('change', toggle);
        toggle(); // initial state
    });

    // -------------------------
    // FIX 10: Blotter status conditional — show resolved date
    // -------------------------
    const statusSelect = document.getElementById('status');
    if (statusSelect) {
        const toggleResolution = (val) => {
            const group = document.getElementById('resolved_date_group');
            if (group) group.classList.toggle('hidden', val !== 'resolved');
        };
        statusSelect.addEventListener('change', e => toggleResolution(e.target.value));
        toggleResolution(statusSelect.value);
    }

    // -------------------------
    // FIX 7: Searchable Resident Combobox
    // <div data-combobox> with hidden select listing options
    // -------------------------
    document.querySelectorAll('[data-combobox]').forEach(wrapper => {
        const input    = wrapper.querySelector('[data-combobox-input]');
        const hidden   = wrapper.querySelector('[data-combobox-value]');
        const dropdown = wrapper.querySelector('[data-combobox-dropdown]');
        if (!input || !hidden || !dropdown) return;

        const items = Array.from(dropdown.querySelectorAll('[data-combobox-item]'));

        // Pre-select if hidden has a value
        const preselected = items.find(i => i.dataset.value === hidden.value);
        if (preselected) input.value = preselected.dataset.label;

        const filter = (q) => {
            const query = q.toLowerCase().trim();
            let visible = 0;
            items.forEach(item => {
                const label = (item.dataset.searchKeywords || item.dataset.label || '').toLowerCase();
                const match = query === '' || label.includes(query);
                item.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            let empty = dropdown.querySelector('.combobox-empty');
            if (visible === 0) {
                if (!empty) {
                    empty = document.createElement('div');
                    empty.className = 'combobox-empty';
                    empty.textContent = 'No matches found.';
                    dropdown.appendChild(empty);
                }
            } else if (empty) empty.remove();
        };

        const open  = () => { dropdown.classList.remove('hidden'); filter(input.value); };
        const close = () => setTimeout(() => dropdown.classList.add('hidden'), 150);

        input.addEventListener('focus', open);
        input.addEventListener('input', () => {
            hidden.value = ''; // typing invalidates previous selection
            open();
        });
        input.addEventListener('blur', close);

        items.forEach(item => {
            item.addEventListener('mousedown', (e) => {
                e.preventDefault(); // prevent input blur
                hidden.value = item.dataset.value;
                input.value  = item.dataset.label;
                dropdown.classList.add('hidden');
                hidden.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });

        // Validate on form submit — must have a real selection
        const form = wrapper.closest('form');
        if (form) {
            form.addEventListener('submit', (e) => {
                if (hidden.required && !hidden.value) {
                    e.preventDefault();
                    input.focus();
                    input.classList.add('border-red-400');
                    showToast('Please select a valid resident from the list.', 'error');
                }
            });
        }
    });

    // -------------------------
    // FIX 8: Purpose "Others" quick-fill
    // Clicking "Others" clears the field and focuses for free input
    // -------------------------
    document.querySelectorAll('[data-purpose-other]').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.querySelector('[name="purpose"]');
            if (input) {
                input.value = '';
                input.placeholder = 'Type your custom purpose here...';
                input.focus();
            }
        });
    });

    // -------------------------
    // Standard purpose quick-fill buttons
    // -------------------------
    document.querySelectorAll('.purpose-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = document.querySelector('[name="purpose"]');
            if (input) input.value = this.textContent.trim();
        });
    });

    // -------------------------
    // Copy to clipboard helper
    // -------------------------
    window.copyToClipboard = function (text) {
        navigator.clipboard.writeText(text).then(() => showToast('Copied to clipboard!'))
            .catch(() => {
                const el = document.createElement('textarea');
                el.value = text;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                showToast('Copied to clipboard!');
            });
    };

    // -------------------------
    // Toast notification
    // -------------------------
    window.showToast = function (message, type = 'success') {
        const colors = {
            success: 'bg-green-600',
            error:   'bg-red-600',
            info:    'bg-blue-600',
            warning: 'bg-orange-500',
        };
        const toast = document.createElement('div');
        toast.className = `fixed bottom-5 right-5 z-50 ${colors[type] || colors.success} text-white text-sm px-4 py-2.5 rounded-lg shadow-lg transition-all duration-300`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(10px)';
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    };

    // -------------------------
    // Form loading state
    // -------------------------
    document.querySelectorAll('form[method="POST"]:not([data-auto-search])').forEach(form => {
        form.addEventListener('submit', function () {
            const btn = this.querySelector('button[type="submit"]');
            if (btn && !btn.disabled) {
                btn.disabled = true;
                const original = btn.innerHTML;
                btn.dataset.originalHtml = original;
                btn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg> Processing...`;
                // Re-enable after 10s as a safety net
                setTimeout(() => {
                    if (btn.disabled) {
                        btn.disabled = false;
                        btn.innerHTML = original;
                    }
                }, 10000);
            }
        });
    });
});
