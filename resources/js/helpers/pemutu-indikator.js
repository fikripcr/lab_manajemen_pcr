/**
 * pemutu-indikator.js
 * JS untuk form create/edit Indikator (pemutu/indikators/create-edit-ajax.blade.php)
 *
 * Requires: jQuery, window.initOfflineSelect2 (dari tabler.js)
 */

/**
 * Initialize indicator form tabs and KPI repeater.
 *
 * @param {object} config
 * @param {number} config.kpiInitialIndex    - Jumlah KPI yang sudah ada (untuk index repeater)
 * @param {string} config.pegawaiOptionsHtml - HTML string <option> untuk select pegawai
 */
window.initPemutuIndikatorForm = function (config) {
    const { kpiInitialIndex = 0, pegawaiOptionsHtml = '' } = config;

    const runInit = function () {
        const typeSelector = document.getElementById('type-selector');
        const cardPerforma = document.getElementById('card-performa');
        const cardTarget = document.getElementById('card-target');
        const parentIdSelector = document.getElementById('parent-id-selector');

        function getCurrentType() {
            if (typeSelector) return typeSelector.value;
            const hidden = document.querySelector('input[type="hidden"][name="type"]');
            return hidden ? hidden.value : '';
        }

        function toggleTabs() {
            const type = getCurrentType();
            if (!cardPerforma || !cardTarget || !parentIdSelector) return;

            // Restrict Penilaian Skala visibility
            const tabSkalaContainer = document.getElementById('tab-link-skala-container');
            const tabSkalaLink = document.getElementById('tab-link-skala');
            const tabInfoLink = document.querySelector('a[href="#tab-informasi-umum"]');

            if (tabSkalaContainer) {
                if (type === 'renop') {
                    tabSkalaContainer.style.display = 'none';
                    // If Scale tab is active, switch to Information tab
                    if (tabSkalaLink && tabSkalaLink.classList.contains('active')) {
                        if (tabInfoLink) {
                            const tab = new bootstrap.Tab(tabInfoLink);
                            tab.show();
                        }
                    }
                } else {
                    tabSkalaContainer.style.display = 'block';
                }
            }

            if (type === 'performa') {
                cardPerforma.style.display = 'block';
                cardTarget.style.display = 'none';
                parentIdSelector.setAttribute('required', 'required');
            } else if (type === 'standar') {
                cardPerforma.style.display = 'none';
                cardTarget.style.display = 'block';
                parentIdSelector.removeAttribute('required');
            } else {
                cardPerforma.style.display = 'none';
                cardTarget.style.display = 'none';
                parentIdSelector.removeAttribute('required');
            }
        }

        function initSelect2Ajax() {
            $('.select2-ajax').each(function () {
                const $container = $(this);
                const $el = $container.is('select') ? $container : $container.find('select');

                if ($el.length === 0 || $el.hasClass('select2-hidden-accessible')) return;

                const url = $container.data('ajax-url') || $el.data('ajax-url');
                const placeholder = $container.data('placeholder') || $el.data('placeholder') || 'Pilih...';
                const isMultiple = $el.attr('multiple') === 'multiple' || $el.prop('multiple');

                $el.select2({
                    theme: 'bootstrap-5',
                    placeholder: placeholder,
                    allowClear: true,
                    closeOnSelect: !isMultiple,
                    dropdownParent: $el.closest('.modal').length ? $el.closest('.modal') : $(document.body),
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: (params.page * 30) < (data.total_count || 0)
                                }
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 0,
                });
            });
        }

        if (typeSelector) typeSelector.addEventListener('change', toggleTabs);
        toggleTabs();
        initSelect2Ajax();

        // --- KPI Assignments Repeater ---
        const kpiBody = document.getElementById('kpi-repeater-body');
        const btnAddKpi = document.getElementById('btn-add-kpi');
        let kpiIndex = kpiInitialIndex;

        if (btnAddKpi && kpiBody) {
            btnAddKpi.addEventListener('click', function () {
                const tr = document.createElement('tr');
                tr.className = 'kpi-row';
                tr.innerHTML = `
                    <td>
                        <input type="hidden" name="kpi_assign[${kpiIndex}][selected]" value="1">
                        <select class="form-select select2-offline" name="kpi_assign[${kpiIndex}][pegawai_id]" required data-placeholder="Pilih pegawai...">
                            ${pegawaiOptionsHtml}
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control mb-2" name="kpi_assign[${kpiIndex}][target_value]" placeholder="Nilai Target">
                        <input type="text" class="form-control" name="kpi_assign[${kpiIndex}][unit_ukuran]" placeholder="%, org, dll (Satuan)">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-icon btn-danger btn-sm btn-remove-row" title="Hapus"><i class="ti ti-trash"></i></button>
                    </td>
                `;
                kpiBody.appendChild(tr);

                if (typeof window.initOfflineSelect2 === 'function') {
                    window.initOfflineSelect2();
                }

                initSelect2Ajax();

                kpiIndex++;
            });

            kpiBody.addEventListener('click', function (e) {
                if (e.target.closest('.btn-remove-row')) {
                    e.target.closest('tr').remove();
                }
            });
        }

        // --- Unit Selection Filtering ---
        const unitSearch = document.getElementById('unit-search');
        const unitFilters = document.querySelectorAll('.btn-unit-filter');
        const unitRows = document.querySelectorAll('.unit-row');
        let currentFilter = 'all'; // 'all' or 'selected'

        function applyUnitFilters() {
            const searchTerm = unitSearch ? unitSearch.value.toLowerCase() : '';

            unitRows.forEach(row => {
                const title = row.getAttribute('data-title') || '';
                const code = row.getAttribute('data-code') || '';
                const isSelected = row.classList.contains('is-assigned');

                let visible = true;

                // Filter by type
                if (currentFilter === 'selected' && !isSelected) {
                    visible = false;
                }

                // Filter by search
                if (visible && searchTerm) {
                    if (!title.includes(searchTerm) && !code.includes(searchTerm)) {
                        visible = false;
                    }
                }

                row.style.display = visible ? '' : 'none';
            });
        }

        if (unitSearch) {
            unitSearch.addEventListener('input', applyUnitFilters);
        }

        unitFilters.forEach(btn => {
            btn.addEventListener('click', function () {
                unitFilters.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.getAttribute('data-filter');
                applyUnitFilters();
            });
        });

        // --- Unit Kerja Checkbox → Target Input Toggle & Filtering ---
        document.querySelectorAll('.unit-checkbox').forEach(function (checkbox) {
            const row = checkbox.closest('.unit-row');
            checkbox.addEventListener('change', function () {
                const targetId = this.dataset.id;
                const targetInput = document.getElementById('target-' + targetId);

                if (this.checked) {
                    if (targetInput) targetInput.removeAttribute('disabled');
                    if (row) row.classList.add('is-assigned');
                } else {
                    if (targetInput) {
                        targetInput.setAttribute('disabled', 'disabled');
                        targetInput.value = '';
                    }
                    if (row) row.classList.remove('is-assigned');
                }

                // If currently filtering by selected, Hide if unchecked
                if (currentFilter === 'selected') {
                    applyUnitFilters();
                }
            });
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runInit);
    } else {
        runInit();
    }
};
