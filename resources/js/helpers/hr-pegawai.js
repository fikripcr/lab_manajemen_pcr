/**
 * hr-pegawai.js
 * JS untuk halaman Detail Pegawai (hr/pegawai/show.blade.php)
 *
 * Requires: Bootstrap 5 (sudah di-expose tabler.js)
 */

/**
 * Initialize section switcher — show/hide content sections via nav-pills.
 * Used on hr/pegawai/show.blade.php multi-section layout.
 *
 * @param {string} [navSelector]      - CSS selector for nav-pill links, default '.nav-pills .nav-link'
 * @param {string} [sectionSelector]  - CSS selector for content sections, default '.content-section'
 */
window.initHrSectionSwitcher = function (navSelector = '.nav-pills .nav-link', sectionSelector = '.content-section') {
    function switchSection(targetId) {
        document.querySelectorAll(sectionSelector).forEach(section => {
            section.style.display = 'none';
        });

        const targetSection = document.querySelector(targetId);
        if (targetSection) targetSection.style.display = 'block';

        document.querySelectorAll(navSelector).forEach(item => {
            if (item.getAttribute('href') === targetId) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }

    document.querySelectorAll(navSelector).forEach(link => {
        link.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href && href.startsWith('#section-')) {
                e.preventDefault();
                switchSection(href);
                history.pushState(null, null, href);
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const hash = window.location.hash;
        if (hash && hash.startsWith('#section-') && document.querySelector(hash)) {
            switchSection(hash);
        } else {
            const firstLink = document.querySelector(navSelector);
            if (firstLink) switchSection(firstLink.getAttribute('href'));
        }
    });
};
