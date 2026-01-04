
/**
 * HoverDropdown.js
 * Enables hover interaction for Bootstrap 5 dropdowns on desktop.
 * Uses native Bootstrap API to ensure Popper.js positioning is correct.
 */

import * as bootstrap from 'bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    // Only enable on desktop/large screens
    const isDesktop = () => window.innerWidth >= 992;

    const dropdowns = document.querySelectorAll('.navbar .dropdown');

    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
        if (!toggle) return;

        dropdown.addEventListener('mouseenter', () => {
            if (isDesktop()) {
                const instance = bootstrap.Dropdown.getOrCreateInstance(toggle);
                instance.show();
            }
        });

        dropdown.addEventListener('mouseleave', () => {
            if (isDesktop()) {
                const instance = bootstrap.Dropdown.getOrCreateInstance(toggle);
                instance.hide();
            }
        });
    });
});
