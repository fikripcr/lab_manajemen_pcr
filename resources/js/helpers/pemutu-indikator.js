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

    document.addEventListener('DOMContentLoaded', function () {
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

        if (typeSelector) typeSelector.addEventListener('change', toggleTabs);
        toggleTabs();

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
                kpiIndex++;
            });

            kpiBody.addEventListener('click', function (e) {
                if (e.target.closest('.btn-remove-row')) {
                    e.target.closest('tr').remove();
                }
            });
        }

        // --- Unit Kerja Checkbox → Target Input Toggle ---
        document.querySelectorAll('.unit-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const targetInput = document.getElementById('target-' + this.dataset.id);
                if (!targetInput) return;
                if (this.checked) {
                    targetInput.removeAttribute('disabled');
                } else {
                    targetInput.setAttribute('disabled', 'disabled');
                    targetInput.value = '';
                }
            });
        });
    });
};
