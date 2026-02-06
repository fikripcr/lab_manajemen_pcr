/**
 * DataTable Utility Functions
 * Provides general purpose functions for DataTable operations
 */

// Object to hold search timers for different DataTables to prevent conflicts
let dataTableSearchTimers = {};

/**
 * Debounced search function for DataTables
 * @param {string} dataTableSelector - Selector for the DataTable instance
 * @param {string} searchInputSelector - Selector for the search input element
 * @param {number} delay - Delay in milliseconds (default 300ms)
 */
function debounceDataTableSearch(dataTableSelector, searchInputSelector, delay = 300) {
    // Ensure the DataTable instance exists
    if ($(dataTableSelector).length === 0) {
        console.warn(`DataTable with selector "${dataTableSelector}" not found.`);
        return;
    }

    // Initialize DataTable if not already initialized
    let table;
    if ($.fn.DataTable.isDataTable(dataTableSelector)) {
        table = $(dataTableSelector).DataTable();
    } else {
        console.warn(`DataTable with selector "${dataTableSelector}" is not initialized.`);
        return;
    }

    // Generate a unique key for the timer based on the selectors
    const timerKey = `${dataTableSelector}_${searchInputSelector}`;

    // Attach keyup event to the search input
    $(document).off('keyup', searchInputSelector).on('keyup', searchInputSelector, function() {
        // Clear previous timer for this specific input/dataTable combination
        if (dataTableSearchTimers[timerKey]) {
            clearTimeout(dataTableSearchTimers[timerKey]);
        }

        // Set new timer
        dataTableSearchTimers[timerKey] = setTimeout(() => {
            table.search(this.value).draw();
            // Clear the timer reference after execution
            delete dataTableSearchTimers[timerKey];
        }, delay);
    });
}

/**
 * Alternative function for when you already have the table instance
 * @param {DataTable} tableInstance - DataTable instance
 * @param {string} searchInputSelector - Selector for the search input element
 * @param {number} delay - Delay in milliseconds (default 300ms)
 */
function attachDebouncedSearch(tableInstance, searchInputSelector, delay = 300) {
    const timerKey = `table_instance_${searchInputSelector}`;

    $(document).off('keyup', searchInputSelector).on('keyup', searchInputSelector, function() {
        if (dataTableSearchTimers[timerKey]) {
            clearTimeout(dataTableSearchTimers[timerKey]);
        }

        dataTableSearchTimers[timerKey] = setTimeout(() => {
            tableInstance.search(this.value).draw();
            delete dataTableSearchTimers[timerKey];
        }, delay);
    });
}

/**
 * Function to handle page length changes for DataTables
 * @param {string} pageLengthSelector - Selector for the page length dropdown
 * @param {DataTable} tableInstance - DataTable instance
 */
function handlePageLengthChange(pageLengthSelector, tableInstance) {
    $(document).off('change', pageLengthSelector).on('change', pageLengthSelector, function() {
        const pageLength = parseInt($(this).val());
        if (pageLength && !isNaN(pageLength)) {
            tableInstance.page.len(pageLength).draw();
        }
    });
}

/**
 * Combined function to initialize common DataTable behaviors
 * @param {DataTable} tableInstance - DataTable instance
 * @param {Object} options - Options object containing selectors and settings
 */
function setupCommonDataTableBehaviors(tableInstance, options = {}) {
    const {
        searchInputSelector = null,
        searchDelay = 300,
        pageLengthSelector = null
    } = options;

    // Attach debounced search functionality if search input selector is provided
    if (searchInputSelector) {
        attachDebouncedSearch(tableInstance, searchInputSelector, searchDelay);
    }

    // Attach page length change functionality if page length selector is provided
    if (pageLengthSelector) {
        handlePageLengthChange(pageLengthSelector, tableInstance);
    }
}

/**
 * Function to clear all search timers (useful for cleanup)
 */
function clearAllSearchTimers() {
    Object.keys(dataTableSearchTimers).forEach(key => {
        clearTimeout(dataTableSearchTimers[key]);
        delete dataTableSearchTimers[key];
    });
}