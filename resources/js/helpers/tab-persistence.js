/**
 * Tab & Accordion Persistence Helper
 * Automatically saves and restores active Bootstrap 5 tabs and accordions using localStorage.
 */
(function() {
    const storageKeyPrefix = 'active_state_';
    const currentPath = window.location.pathname;

    window.restoreTabPersistence = function() {
        const bs = window.bootstrap;
        if (!bs) return;

        // 1. Restore Tabs
        if (bs.Tab) {
            document.querySelectorAll('[role="tablist"]').forEach((tablist, index) => {
                const listId = tablist.id || `tablist-${index}`;
                const key = `${storageKeyPrefix}${currentPath}_${listId}`;
                const savedId = localStorage.getItem(key);

                if (savedId) {
                    const el = tablist.querySelector(`[href="#${savedId}"], [data-bs-target="#${savedId}"]`);
                    // Ensure we don't re-trigger if already show/active to avoid loops
                    if (el && !el.classList.contains('active')) {
                        bs.Tab.getOrCreateInstance(el).show();
                    }
                }
            });
        }

        // 2. Restore Accordions/Collapses
        if (bs.Collapse) {
            document.querySelectorAll('.accordion').forEach((accordion, index) => {
                const accId = accordion.id || `accordion-${index}`;
                const key = `${storageKeyPrefix}${currentPath}_${accId}`;
                const savedId = localStorage.getItem(key);

                if (savedId) {
                    const el = document.getElementById(savedId);
                    if (el && el.closest('.accordion') === accordion && !el.classList.contains('show')) {
                        bs.Collapse.getOrCreateInstance(el, { toggle: false }).show();
                    }
                }
            });
        }
    };

    // Listen for Tab Changes (Event Delegation)
    document.addEventListener('shown.bs.tab', function (event) {
        const tabTriggerEl = event.target;
        const targetId = tabTriggerEl.getAttribute('href')?.replace('#', '') || 
                         tabTriggerEl.getAttribute('data-bs-target')?.replace('#', '');
        
        const tablist = tabTriggerEl.closest('[role="tablist"]');
        if (targetId && tablist) {
            const listId = tablist.id || `tablist-${Array.from(document.querySelectorAll('[role="tablist"]')).indexOf(tablist)}`;
            localStorage.setItem(`${storageKeyPrefix}${currentPath}_${listId}`, targetId);
        }
    });

    // Listen for Accordion/Collapse Changes (Event Delegation)
    document.addEventListener('shown.bs.collapse', function (event) {
        const el = event.target;
        const accordion = el.closest('.accordion');
        if (accordion && el.id) {
            const accId = accordion.id || `accordion-${Array.from(document.querySelectorAll('.accordion')).indexOf(accordion)}`;
            localStorage.setItem(`${storageKeyPrefix}${currentPath}_${accId}`, el.id);
        }
    });

    // Auto restore on load
    document.addEventListener('DOMContentLoaded', () => setTimeout(window.restoreTabPersistence, 150));
    
    // Auto restore on AJAX success (project specific convention)
    document.addEventListener('ajax-form:success', () => setTimeout(window.restoreTabPersistence, 200));
})();
