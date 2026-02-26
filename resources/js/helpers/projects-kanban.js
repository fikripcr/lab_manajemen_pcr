/**
 * projects-kanban.js
 * JS untuk halaman Kanban Board (projects/kanban.blade.php & projects/show.blade.php)
 *
 * Requires: jKanban (via npm bundle), Axios, Bootstrap 5
 *
 * jKanban adalah CommonJS/UMD module. Vite tidak selalu menyediakan default export.
 * Kita import CSS-nya via npm, JS-nya dimuat via dynamic import + window fallback.
 */
import 'jkanban/dist/jkanban.min.css';

/**
 * Dapatkan konstruktor jKanban dengan aman.
 * UMD module mendaftarkan dirinya ke window.jKanban setelah dieksekusi.
 */
async function loadJKanbanClass() {
    // Jika sudah tersedia di window (UMD mode), gunakan langsung
    if (typeof window.jKanban !== 'undefined') return window.jKanban;

    // Dynamic import — Vite akan mem-bundle sebelumnya, interop dicoba otomatis
    const mod = await import('jkanban/dist/jkanban.min.js');
    // CommonJS: mod.default atau mod.jKanban atau langsung mod
    const ctor = mod.default || mod.jKanban || mod;
    if (typeof ctor === 'function') return ctor;

    // Coba sekali lagi dari window setelah script dijalankan
    return window.jKanban || null;
}

/**
 * Initialize a Kanban board.
 *
 * @param {object} config
 * @param {string} config.element       - CSS selector untuk container board
 * @param {string} config.loadUrl       - Endpoint GET untuk fetch tasks
 * @param {string} config.moveUrl       - Endpoint POST untuk move task. Gunakan '__TASK_ID__' sebagai placeholder.
 * @param {string} config.createUrl     - Base URL untuk create task (?status=... ditambahkan)
 * @param {string} [config.widthBoard]  - Lebar board, default '300px'
 * @param {string[]} [config.boards]    - Board IDs, default ['todo','in_progress','done']
 */
window.initProjectsKanban = function (config) {
    const {
        element = '#fullKanban',
        loadUrl,
        moveUrl,
        createUrl,
        widthBoard = '300px',
        boards = ['todo', 'in_progress', 'done'],
    } = config;

    const boardTitles = {
        todo: 'To Do',
        in_progress: 'In Progress',
        review: 'Review',
        done: 'Done',
    };

    let kanban = null;
    let jKanbanClass = null;

    async function ensureJKanban() {
        if (jKanbanClass) return jKanbanClass;
        jKanbanClass = await loadJKanbanClass();
        return jKanbanClass;
    }

    function loadKanbanData() {
        axios.get(loadUrl)
            .then(async response => {
                if (!response.data.success) return;
                const tasks = response.data.data;

                if (kanban) {
                    boards.forEach(boardId => {
                        kanban.removeAllItems(boardId);
                        if (tasks[boardId]) {
                            tasks[boardId].forEach(item => kanban.addElement(boardId, item));
                        }
                    });
                } else {
                    await initBoard(tasks);
                }

                // Re-initialize Bootstrap dropdowns untuk kartu yang baru ditambahkan
                setTimeout(() => {
                    document.querySelectorAll('.kanban-item [data-bs-toggle="dropdown"]').forEach(el => {
                        if (window.bootstrap && !bootstrap.Dropdown.getInstance(el)) {
                            new bootstrap.Dropdown(el);
                        }
                    });
                }, 50);
            })
            .catch(error => {
                console.error('Error fetching Kanban data:', error);
                if (window.showErrorMessage) showErrorMessage('Gagal memuat data Kanban');
            });
    }

    async function initBoard(tasks) {
        const JKanban = await ensureJKanban();
        if (!JKanban) {
            console.error('jKanban class tidak tersedia. Pastikan npm package jkanban ter-install dan ter-build.');
            return;
        }

        kanban = new JKanban({
            element: element,
            gutter: '16px',
            widthBoard: widthBoard,
            dragItems: true,
            itemAddOptions: {
                enabled: true,
                content: '+',
                class: 'kanban-title-button',
                footer: false,
            },
            boards: boards.map(id => ({
                id,
                title: boardTitles[id] || id,
                item: tasks[id] || [],
            })),
            dropEl: function (el, target) {
                const taskId = el.dataset.eid || el.getAttribute('data-eid');
                const newStatus = target.parentElement.dataset.id;
                const url = moveUrl.replace('__TASK_ID__', taskId);

                axios.post(url, { status: newStatus })
                    .then(() => {
                        if (window.showSuccessMessage) showSuccessMessage('Task dipindahkan ke ' + (boardTitles[newStatus] || newStatus));
                    })
                    .catch(error => {
                        console.error('Error moving task:', error);
                        if (window.showErrorMessage) showErrorMessage('Gagal memindahkan task');
                        loadKanbanData(); // Rollback UI
                    });
            },
            buttonClick: function (el, boardId) {
                const url = createUrl + '?status=' + boardId;
                if (window.openAjaxModal) {
                    window.openAjaxModal(url, 'Tambah Task Baru');
                }
            },
        });
    }

    // Initial load
    loadKanbanData();

    // Refresh on AJAX form success
    document.addEventListener('ajax-form:success', function () {
        loadKanbanData();
    });

    if (typeof $ !== 'undefined') {
        $(document).on('ajax-form:success', function (e, responseData) {
            loadKanbanData();
            if (responseData && responseData.redirect && responseData.redirect.includes('#tasks')) {
                const tasksTab = document.querySelector('a[href="#project-tasks"]');
                if (tasksTab && window.bootstrap) new bootstrap.Tab(tasksTab).show();
            }
        });
    }
};

/**
 * Initialize tab persistence untuk project show page.
 * Menyimpan/restore active tab ke localStorage berdasarkan projectId.
 *
 * @param {string|number} projectId
 */
window.initProjectTabPersistence = function (projectId) {
    const storageKey = `project_active_tab_${projectId}`;
    const lastActiveTab = localStorage.getItem(storageKey);

    if (lastActiveTab) {
        const tabEl = document.querySelector(`a[data-tab-id="${lastActiveTab}"]`);
        if (tabEl && window.bootstrap) new bootstrap.Tab(tabEl).show();
    }

    document.querySelectorAll('#projectTabs a[data-bs-toggle="tab"]').forEach(tabLink => {
        tabLink.addEventListener('shown.bs.tab', function (e) {
            const tabId = e.target.getAttribute('data-tab-id');
            if (tabId) localStorage.setItem(storageKey, tabId);
        });
    });
};
