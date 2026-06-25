(function () {
    function initDatatable(options) {
        var table = document.getElementById(options.tableId);
        if (!table) {
            return;
        }

        var tbody = table.querySelector('tbody');
        var allRows = Array.prototype.slice.call(tbody.querySelectorAll('tr[data-row="1"]'));
        var emptyRow = tbody.querySelector('tr[data-empty="1"]');

        var searchInput = document.getElementById(options.searchInputId);
        var pageSizeSelect = document.getElementById(options.pageSizeSelectId);

        var state = {
            allRows: allRows,
            filteredRows: allRows.slice(),
            sortColumn: null,
            sortDirection: 'asc',
            currentPage: 1,
            pageSize: parseInt(pageSizeSelect ? pageSizeSelect.value : '10', 10) || 10,
            paginationId: options.paginationId,
            render: render
        };

        function getCellValue(row, index) {
            var cell = row.children[index];
            return (cell ? cell.textContent : '').trim().toLowerCase();
        }

        function applyFilters() {
            var q = (searchInput ? searchInput.value : '').trim().toLowerCase();
            state.filteredRows = state.allRows.filter(function (row) {
                if (!q) {
                    return true;
                }
                return row.textContent.toLowerCase().indexOf(q) !== -1;
            });
        }

        function applySort() {
            if (state.sortColumn === null) {
                return;
            }

            state.filteredRows.sort(function (a, b) {
                var av = getCellValue(a, state.sortColumn);
                var bv = getCellValue(b, state.sortColumn);
                if (av < bv) return state.sortDirection === 'asc' ? -1 : 1;
                if (av > bv) return state.sortDirection === 'asc' ? 1 : -1;
                return 0;
            });
        }

        function renderRows() {
            state.allRows.forEach(function (row) {
                row.style.display = 'none';
            });

            if (emptyRow) {
                emptyRow.style.display = state.filteredRows.length === 0 ? '' : 'none';
            }

            var start = (state.currentPage - 1) * state.pageSize;
            var end = start + state.pageSize;
            state.filteredRows.slice(start, end).forEach(function (row) {
                row.style.display = '';
            });
        }

        function render() {
            applyFilters();
            applySort();
            state.currentPage = Math.max(1, state.currentPage);
            renderRows();

            if (window.VetCheckPagination && typeof window.VetCheckPagination.renderPagination === 'function') {
                window.VetCheckPagination.renderPagination(state);
            }
        }

        table.querySelectorAll('[data-sort-index]').forEach(function (header) {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function () {
                var index = parseInt(header.getAttribute('data-sort-index'), 10);
                if (state.sortColumn === index) {
                    state.sortDirection = state.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    state.sortColumn = index;
                    state.sortDirection = 'asc';
                }
                state.currentPage = 1;
                render();
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                state.currentPage = 1;
                render();
            });
        }

        if (pageSizeSelect) {
            pageSizeSelect.addEventListener('change', function () {
                state.pageSize = parseInt(pageSizeSelect.value, 10) || 10;
                state.currentPage = 1;
                render();
            });
        }

        render();
    }

    window.VetCheckDatatable = {
        initDatatable: initDatatable
    };
})();
