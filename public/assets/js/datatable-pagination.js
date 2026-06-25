(function () {
    function renderPagination(state) {
        var totalPages = Math.max(1, Math.ceil(state.filteredRows.length / state.pageSize));
        state.currentPage = Math.min(state.currentPage, totalPages);

        var container = document.getElementById(state.paginationId);
        if (!container) {
            return;
        }

        container.innerHTML = '';

        function addItem(label, page, disabled, active) {
            var li = document.createElement('li');
            li.className = 'page-item' + (disabled ? ' disabled' : '') + (active ? ' active' : '');

            var a = document.createElement('button');
            a.type = 'button';
            a.className = 'page-link';
            a.textContent = label;
            a.disabled = disabled;

            if (!disabled) {
                a.addEventListener('click', function () {
                    state.currentPage = page;
                    state.render();
                });
            }

            li.appendChild(a);
            container.appendChild(li);
        }

        addItem('«', Math.max(1, state.currentPage - 1), state.currentPage === 1, false);

        var start = Math.max(1, state.currentPage - 2);
        var end = Math.min(totalPages, start + 4);
        start = Math.max(1, end - 4);

        for (var i = start; i <= end; i++) {
            addItem(String(i), i, false, i === state.currentPage);
        }

        addItem('»', Math.min(totalPages, state.currentPage + 1), state.currentPage === totalPages, false);
    }

    window.VetCheckPagination = {
        renderPagination: renderPagination
    };
})();
