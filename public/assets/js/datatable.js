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
            return (cell ? cell.textContent : '').trim();
        }

        function toSortableValue(rawValue) {
            var value = (rawValue || '').trim();

            // yyyy-mm-dd or yyyy-mm-dd hh:mm:ss
            if (/^\d{4}-\d{2}-\d{2}(?:[ T]\d{2}:\d{2}:\d{2})?$/.test(value)) {
                var normalized = value.replace(' ', 'T');
                var timestamp = Date.parse(normalized);
                if (!Number.isNaN(timestamp)) {
                    return { type: 'number', value: timestamp };
                }
            }

            // dd/mm/yyyy or dd/mm/yyyy hh:mm:ss
            var frenchDateMatch = value.match(/^(\d{2})\/(\d{2})\/(\d{4})(?:\s+(\d{2}):(\d{2}):(\d{2}))?$/);
            if (frenchDateMatch) {
                var dd = frenchDateMatch[1];
                var mm = frenchDateMatch[2];
                var yyyy = frenchDateMatch[3];
                var hh = frenchDateMatch[4] || '00';
                var mi = frenchDateMatch[5] || '00';
                var ss = frenchDateMatch[6] || '00';
                var isoLike = yyyy + '-' + mm + '-' + dd + 'T' + hh + ':' + mi + ':' + ss;
                var frTimestamp = Date.parse(isoLike);
                if (!Number.isNaN(frTimestamp)) {
                    return { type: 'number', value: frTimestamp };
                }
            }

            var numeric = Number(value.replace(',', '.'));
            if (!Number.isNaN(numeric) && value !== '') {
                return { type: 'number', value: numeric };
            }

            return { type: 'string', value: value.toLowerCase() };
        }

        function compareCells(aValue, bValue) {
            var av = toSortableValue(aValue);
            var bv = toSortableValue(bValue);

            if (av.type === 'number' && bv.type === 'number') {
                if (av.value < bv.value) return -1;
                if (av.value > bv.value) return 1;
                return 0;
            }

            if (av.value < bv.value) return -1;
            if (av.value > bv.value) return 1;
            return 0;
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
                var base = compareCells(
                    getCellValue(a, state.sortColumn),
                    getCellValue(b, state.sortColumn)
                );

                // For history table, date column often has duplicates.
                // Use timestamp column as tie-breaker so Date sorting visibly changes rows.
                if (base === 0 && table.id === 'history-table' && state.sortColumn === 0) {
                    base = compareCells(getCellValue(a, 4), getCellValue(b, 4));
                }

                if (base < 0) return state.sortDirection === 'asc' ? -1 : 1;
                if (base > 0) return state.sortDirection === 'asc' ? 1 : -1;
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
