/**
 * Documentation Search Module
 * Enhanced search functionality for documentation
 */

class DocumentationSearch {
    constructor() {
        this.searchInput = document.querySelector('input[name="search"]');
        this.searchForm = document.querySelector('form[action*="documentation"]');
        this.resultsContainer = document.querySelector('.search-results');
        this.debounceTimer = null;
        this.minSearchLength = 2;

        this.init();
    }

    init() {
        if (!this.searchInput) return;

        // Real-time search with debounce
        this.searchInput.addEventListener('input', (e) => {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.handleSearch(e.target.value);
            }, 500);
        });

        // Handle form submit
        if (this.searchForm) {
            this.searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const query = this.searchInput.value.trim();
                if (query.length >= this.minSearchLength) {
                    this.performSearch(query);
                }
            });
        }

        // Clear search
        const clearButton = document.querySelector('button[href*="documentation.index"]');
        if (clearButton && this.searchInput.value) {
            clearButton.addEventListener('click', () => {
                this.searchInput.value = '';
                this.searchInput.focus();
            });
        }
    }

    handleSearch(query) {
        query = query.trim();

        if (query.length < this.minSearchLength) {
            this.hideResults();
            return;
        }

        // Show loading state
        this.showLoading();

        // Perform AJAX search
        this.performAjaxSearch(query);
    }

    showLoading() {
        if (!this.resultsContainer) return;

        this.resultsContainer.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status"></div>
                <div class="text-muted">Searching documentation...</div>
            </div>
        `;
    }

    hideResults() {
        if (!this.resultsContainer) return;
        this.resultsContainer.innerHTML = '';
    }

    performAjaxSearch(query) {
        fetch(`${this.searchForm.action}?q=${encodeURIComponent(query)}&ajax=1`)
            .then(response => response.text())
            .then(html => {
                this.updateResults(html);
            })
            .catch(error => {
                console.error('Search error:', error);
                this.showError();
            });
    }

    updateResults(html) {
        // Parse response and extract results
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const results = doc.querySelector('.search-results');

        if (results) {
            this.resultsContainer.innerHTML = results.innerHTML;
        }
    }

    showError() {
        if (!this.resultsContainer) return;

        this.resultsContainer.innerHTML = `
            <div class="alert alert-warning" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8l0 4" /><path d="M12 16l0 .01" /><path d="M3 12a9 9 0 1 1 18 0a9 9 0 0 1 -18 0" /></svg>
                <div>Search failed. Please try again.</div>
            </div>
        `;
    }

    performSearch(query) {
        window.location.href = `${this.searchForm.action}?q=${encodeURIComponent(query)}`;
    }

    // Highlight search results
    highlightText(text, query) {
        if (!query) return text;

        const regex = new RegExp(`(${this.escapeRegExp(query)})`, 'gi');
        return text.replace(regex, '<mark class="search-highlight">$1</mark>');
    }

    escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
}

/**
 * Table of Contents Module
 * Enhanced TOC with scroll spy and smooth scrolling
 */
class TableOfContents {
    constructor() {
        this.tocNav = document.getElementById('toc-nav');
        this.docContent = document.getElementById('doc-content');
        this.headings = [];
        this.activeIndex = -1;
        this.observer = null;

        this.init();
    }

    init() {
        if (!this.tocNav || !this.docContent) return;

        this.extractHeadings();
        this.generateTOC();
        this.initScrollSpy();
        this.initSmoothScroll();
    }

    extractHeadings() {
        const headingElements = this.docContent.querySelectorAll('h1[id], h2[id], h3[id], h4[id]');
        this.headings = Array.from(headingElements).map((heading, index) => ({
            element: heading,
            id: heading.id || `heading-${index}`,
            level: parseInt(heading.tagName.charAt(1)),
            text: heading.textContent.trim(),
            index: index
        }));

        // Ensure all headings have IDs
        this.headings.forEach((heading, index) => {
            if (!heading.element.id) {
                heading.element.id = heading.id;
            }
        });
    }

    generateTOC() {
        if (this.headings.length === 0) {
            this.tocNav.innerHTML = '<div class="text-center text-muted py-3"><small>No sections found</small></div>';
            return;
        }

        this.tocNav.innerHTML = '';

        this.headings.forEach((heading) => {
            const link = document.createElement('a');
            link.href = `#${heading.id}`;
            link.className = 'nav-link py-1';
            link.dataset.index = heading.index;
            link.textContent = heading.text;
            link.title = heading.text;

            // Indentation based on level
            const indent = (heading.level - 1) * 12;
            link.style.paddingLeft = `${indent + 8}px`;
            link.style.fontSize = heading.level === 1 ? '0.95rem' : '0.875rem';

            this.tocNav.appendChild(link);
        });
    }

    initScrollSpy() {
        if (this.headings.length === 0) return;

        const options = {
            root: null,
            rootMargin: '-100px 0px -60% 0px',
            threshold: 0
        };

        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const index = this.headings.findIndex(h => h.element === entry.target);
                    this.setActiveLink(index);
                }
            });
        }, options);

        this.headings.forEach(heading => {
            this.observer.observe(heading.element);
        });
    }

    setActiveLink(index) {
        if (this.activeIndex === index) return;

        // Remove active class from all links
        const links = this.tocNav.querySelectorAll('.nav-link');
        links.forEach(link => link.classList.remove('active'));

        // Add active class to current link
        if (index >= 0 && index < links.length) {
            const activeLink = links[index];
            activeLink.classList.add('active');

            // Scroll TOC to keep active item visible
            activeLink.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }

        this.activeIndex = index;
    }

    initSmoothScroll() {
        const links = this.tocNav.querySelectorAll('a[href^="#"]');

        links.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();

                const targetId = link.getAttribute('href').substring(1);
                const target = document.getElementById(targetId);

                if (target) {
                    const offset = 100; // Header offset
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });

                    // Update URL without scrolling
                    history.pushState(null, null, `#${targetId}`);
                }
            });
        });
    }

    destroy() {
        if (this.observer) {
            this.observer.disconnect();
        }
    }
}

/**
 * Copy Code Button Module
 * Add copy buttons to all code blocks
 */
class CopyCodeButtons {
    constructor() {
        this.docContent = document.getElementById('doc-content');
        this.init();
    }

    init() {
        if (!this.docContent) return;

        const codeBlocks = this.docContent.querySelectorAll('pre');

        codeBlocks.forEach(pre => {
            this.addCopyButton(pre);
        });
    }

    addCopyButton(pre) {
        const button = document.createElement('button');
        button.className = 'btn btn-sm btn-ghost-primary position-absolute top-0 end-0 m-2';
        button.innerHTML = this.getCopyIcon();
        button.title = 'Copy code';
        button.style.cssText = 'z-index: 10;';

        pre.style.position = 'relative';
        pre.appendChild(button);

        button.addEventListener('click', async () => {
            const code = pre.querySelector('code') || pre;
            const text = code.textContent;

            try {
                await navigator.clipboard.writeText(text);
                button.innerHTML = this.getSuccessIcon();

                setTimeout(() => {
                    button.innerHTML = this.getCopyIcon();
                }, 2000);

                // Show toast notification
                if (typeof showSuccessMessage === 'function') {
                    showSuccessMessage('Code copied to clipboard!');
                }
            } catch (err) {
                console.error('Failed to copy:', err);
                button.innerHTML = this.getErrorIcon();

                setTimeout(() => {
                    button.innerHTML = this.getCopyIcon();
                }, 2000);
            }
        });
    }

    getCopyIcon() {
        return `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" /><path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" /></svg>`;
    }

    getSuccessIcon() {
        return `<svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>`;
    }

    getErrorIcon() {
        return `<svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 10l0 4" /><path d="M12 16l0 .01" /></svg>`;
    }
}

/**
 * Anchor Links Module
 * Add clickable anchor links to headings
 */
class AnchorLinks {
    constructor() {
        this.docContent = document.getElementById('doc-content');
        this.init();
    }

    init() {
        if (!this.docContent) return;

        const headings = this.docContent.querySelectorAll('h1[id], h2[id], h3[id], h4[id]');

        headings.forEach(heading => {
            this.addAnchorLink(heading);
        });
    }

    addAnchorLink(heading) {
        const anchor = document.createElement('a');
        anchor.href = '#' + heading.id;
        anchor.className = 'anchor-link';
        anchor.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5" /><path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5" /></svg>`;
        anchor.title = 'Copy link to heading';

        heading.appendChild(anchor);

        anchor.addEventListener('click', (e) => {
            e.preventDefault();

            const url = window.location.href.split('#')[0] + '#' + heading.id;
            navigator.clipboard.writeText(url).then(() => {
                if (typeof showSuccessMessage === 'function') {
                    showSuccessMessage('Link copied to clipboard!');
                }
            });
        });
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize search
    new DocumentationSearch();

    // Initialize TOC
    new TableOfContents();

    // Initialize copy code buttons
    new CopyCodeButtons();

    // Initialize anchor links
    new AnchorLinks();
});

// Export for use in other modules
window.DocumentationSearch = DocumentationSearch;
window.TableOfContents = TableOfContents;
window.CopyCodeButtons = CopyCodeButtons;
window.AnchorLinks = AnchorLinks;
