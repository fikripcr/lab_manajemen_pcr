/**
 * pemutu-workspace.js
 * JS untuk halaman Dokumen SPMI (tree + detail panel + drag-drop).
 * Dipanggil dari resources/views/pages/pemutu/dokumens/index.blade.php
 *
 * Requires: jQuery, Axios, SortableJS, Bootstrap 5 (semua sudah di-expose oleh tabler.js)
 */

// --- State Management ---

function pemutuGetExpandedNodes() {
    try {
        return JSON.parse(localStorage.getItem('pemutu_tree_expanded') || '[]');
    } catch (e) {
        return [];
    }
}

function pemutuSaveExpandedNode(id, isExpanded) {
    let expanded = pemutuGetExpandedNodes();
    if (isExpanded) {
        if (!expanded.includes(id)) expanded.push(id);
    } else {
        expanded = expanded.filter(nodeId => nodeId !== id);
    }
    localStorage.setItem('pemutu_tree_expanded', JSON.stringify(expanded));
}

// --- Drag and Drop ---

function pemutuInitDragAndDrop(reorderUrl) {
    const nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable'));
    nestedSortables.forEach(function (el) {
        if (el.sortableInstance) el.sortableInstance.destroy();

        el.sortableInstance = new Sortable(el, {
            group: 'nested',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            handle: '.d-flex',
            onEnd: function () {
                pemutuSaveHierarchy(reorderUrl);
            }
        });
    });
}

function pemutuGetHierarchyFromUl(ul) {
    const items = [];
    Array.from(ul.children).forEach(li => {
        const id = li.dataset.id;
        if (!id) return;
        const item = { id: id, name: li.querySelector('.tree-item-name')?.textContent || '' };
        const nestedUl = li.querySelector('ul.nested-sortable');
        if (nestedUl && nestedUl.children.length > 0) {
            item.children = pemutuGetHierarchyFromUl(nestedUl);
        }
        items.push(item);
    });
    return items;
}

function pemutuSaveHierarchy(reorderUrl) {
    const activeTabPane = document.querySelector('.tab-pane.active.show');
    const rootUl = activeTabPane ? activeTabPane.querySelector('.nested-sortable') : null;
    if (!rootUl) return;
    const hierarchy = pemutuGetHierarchyFromUl(rootUl);
    axios.post(reorderUrl, { hierarchy: hierarchy })
        .catch(error => console.error('Failed to save hierarchy', error));
}

// --- Detail Panel Loader ---

function pemutuLoadDetail(url, docJenis, pushState = true) {
    $('#document-detail-panel').html('<div class="card"><div class="card-body text-center py-5"><div class="spinner-border text-primary" role="status"></div></div></div>');

    axios.get(url, {
        params: { ajax: 1 },
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function (response) {
        $('#document-detail-panel').html(response.data);

        if (pushState) {
            const idMatch = url.match(/\/([a-zA-Z0-9]+)$/);
            if (idMatch) {
                const params = new URLSearchParams(window.location.search);
                params.set('id', idMatch[1]);
                params.set('type', docJenis);
                window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
            }
        }
    })
    .catch(function () {
        $('#document-detail-panel').html('<div class="card-body text-center text-danger">Gagal memuat detail data.</div>');
    });
}

// --- UI Refresh (after AJAX insert/delete) ---

function pemutuRefreshWorkspaceUI(reorderUrl) {
    const url = new URL(window.location);
    url.searchParams.set('ajax', '1');

    axios.get(url.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(response => {
        const $temp = $('<div>').html(response.data);
        const $newTabContent = $temp.find('.tab-content').first();
        if (!$newTabContent.length) return;

        $('.tab-content').first().html($newTabContent.html());
        pemutuInitDragAndDrop(reorderUrl);
        pemutuRestoreExpandedNodes();

        // Re-highlight selected item
        const params = new URLSearchParams(window.location.search);
        const idParam = params.get('id');
        const typeParam = params.get('type');
        if (idParam && typeParam) {
            const selector = typeParam === 'doksub' ? `#tree-node-sub-${idParam}` : `#tree-node-dok-${idParam}`;
            const targetNode = document.querySelector(selector);
            if (targetNode) {
                const row = targetNode.querySelector('.tree-node-row');
                if (row) {
                    row.classList.add('fw-bold', 'bg-primary-lt', 'active-tree-node');
                    const link = row.querySelector('.tree-item-link');
                    if (link) pemutuLoadDetail(link.dataset.url, link.dataset.jenis, false);
                }
            }
        }
    }).catch(err => {
        console.error('Failed to refresh workspace UI', err);
    });
}

// --- Restore Expanded Tree Nodes ---

function pemutuRestoreExpandedNodes() {
    pemutuGetExpandedNodes().forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            const ul = el.querySelector(':scope > ul');
            const icon = el.querySelector(':scope > .d-flex .tree-toggle i, :scope > .d-flex .tree-toggle-custom i');
            if (ul) ul.classList.remove('d-none');
            if (icon) icon.classList.replace('ti-chevron-right', 'ti-chevron-down');
        }
    });
}

// --- Main Init ---

/**
 * Initialize the Pemutu SPMI Document workspace.
 *
 * @param {object} config
 * @param {string} config.reorderUrl  - Route for saving hierarchy (pemutu.dokumens.reorder)
 */
window.initPemutuWorkspace = function (config) {
    const { reorderUrl } = config;

    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);

        // 1. Init Drag & Drop
        pemutuInitDragAndDrop(reorderUrl);

        // 2. Restore expanded tree nodes
        pemutuRestoreExpandedNodes();

        // 3. Restore sub-tab from URL
        const jenisParam = urlParams.get('jenis');
        if (jenisParam) {
            const tabLink = document.querySelector(`.nav-link[data-jenis="${jenisParam}"]`);
            if (tabLink) new bootstrap.Tab(tabLink).show();
        }

        // 4. Restore selected item and scroll to it
        const idParam = urlParams.get('id');
        const typeParam = urlParams.get('type');
        if (idParam && typeParam) {
            const selector = typeParam === 'doksub' ? `#tree-node-sub-${idParam}` : `#tree-node-dok-${idParam}`;
            const targetNode = document.querySelector(selector);
            if (targetNode) {
                const link = targetNode.querySelector('.tree-item-link');
                if (link) {
                    const row = targetNode.querySelector('.tree-node-row');
                    if (row) row.classList.add('fw-bold', 'bg-primary-lt', 'active-tree-node');
                    pemutuLoadDetail(link.dataset.url, link.dataset.jenis, false);

                    $(targetNode).parents('ul').removeClass('d-none').each(function () {
                        const icon = $(this).parent().find('> .d-flex .tree-toggle i, > .d-flex .tree-toggle-custom i');
                        icon.removeClass('ti-chevron-right').addClass('ti-chevron-down');
                    });
                    targetNode.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        }

        // 5. AJAX events
        $(document).off('ajax-form:success.pemutuWorkspace').on('ajax-form:success.pemutuWorkspace', '.ajax-form', function () {
            pemutuRefreshWorkspaceUI(reorderUrl);
        });
        $(document).off('ajax-delete:success.pemutuWorkspace').on('ajax-delete:success.pemutuWorkspace', '.ajax-delete', function () {
            pemutuRefreshWorkspaceUI(reorderUrl);
        });

        // 6. Sub-tab change → sync URL
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            const jenis = $(this).data('jenis');
            if (jenis) {
                const params = new URLSearchParams(window.location.search);
                params.set('jenis', jenis);
                window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
            }
            setTimeout(() => pemutuInitDragAndDrop(reorderUrl), 100);
        });

        // 7. Tree node click
        $(document).on('click', '.tree-node-row', function (e) {
            if ($(e.target).closest('.tree-toggle, .tree-toggle-custom').length) return;
            e.preventDefault();
            $('.tree-node-row').removeClass('fw-bold bg-primary-lt active-tree-node');
            $(this).addClass('fw-bold bg-primary-lt active-tree-node');
            const link = $(this).find('.tree-item-link');
            if (link.length) pemutuLoadDetail(link.data('url'), link.data('jenis'));
        });
        
        // Ensure tree-item-link text/span clicks bubble properly
        $(document).on('click', '.tree-item-link', function(e) {
            e.preventDefault(); 
            // the parent .tree-node-row will handle the logic
        });

        // 8. Toggle tree children
        $(document).on('click', '.tree-toggle, .tree-toggle-custom', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const li = $(this).closest('li');
            const target = li.children('ul');
            const icon = $(this).find('i');
            const nodeId = li.attr('id');

            target.toggleClass('d-none');
            const isExpanded = !target.hasClass('d-none');
            if (isExpanded) {
                icon.removeClass('ti-chevron-right').addClass('ti-chevron-down');
            } else {
                icon.removeClass('ti-chevron-down').addClass('ti-chevron-right');
            }
            if (nodeId) pemutuSaveExpandedNode(nodeId, isExpanded);
        });

        // 9. Search filter
        $('#tree-search').on('keyup', function () {
            const value = $(this).val().toLowerCase();
            $('.nested-sortable li').hide();
            $('.nested-sortable li').each(function () {
                const text = $(this).find('.tree-item-name').text().toLowerCase();
                if (text.includes(value)) {
                    $(this).show().parents('li').show();
                    $(this).find('li').show();
                }
            });
        });

        // 10. Filter param sync (Periode dropdown)
        $('.filter-sync-param').on('change', function () {
            const param = $(this).data('param');
            const value = $(this).val();
            const params = new URLSearchParams(window.location.search);

            if (value) {
                params.set(param, value);
            } else {
                params.delete(param);
            }

            const activeSubTab = document.querySelector('.nav-link.active[data-jenis]');
            if (activeSubTab) params.set('jenis', activeSubTab.dataset.jenis);

            window.location.href = $(this).data('base-url') + '?' + params.toString();
        });
    });
};
