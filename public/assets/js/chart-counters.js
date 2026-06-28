/* global Chart */
'use strict';

window.VetCheckCharts = (function () {
    let chartInstance = null;
    let chartData     = null;

    const COLORS = [
        '#1877f2', '#42b883', '#e63946', '#f7c59f', '#a8dadc',
        '#457b9d', '#e9c46a', '#f4a261', '#264653', '#2a9d8f',
        '#6d6875', '#e76f51', '#023e8a', '#80b918', '#f72585',
    ];

    const LEGENDS = {
        bar:   'Total par événement',
        line:  'Évolution journalière des comptages',
        donut: 'Répartition par utilisateur',
    };

    function destroy() {
        if (chartInstance) {
            chartInstance.destroy();
            chartInstance = null;
        }
    }

    function setActiveButton(type) {
        document.querySelectorAll('[data-chart-type]').forEach(function (btn) {
            btn.classList.toggle('active',        btn.dataset.chartType === type);
            btn.classList.toggle('btn-primary',        btn.dataset.chartType === type);
            btn.classList.toggle('btn-outline-primary', btn.dataset.chartType !== type);
        });
        const legend = document.getElementById('chart-legend');
        if (legend) legend.textContent = LEGENDS[type] || '';
    }

    function render(type) {
        destroy();
        const canvas = document.getElementById('counterChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');

        setActiveButton(type);

        if (type === 'bar')   { chartInstance = renderBar(ctx);   return; }
        if (type === 'line')  { chartInstance = renderLine(ctx);  return; }
        if (type === 'donut') { chartInstance = renderDonut(ctx); return; }
    }

    function renderBar(ctx) {
        const labels = chartData.byItem.map(function (d) { return d.item_title; });
        const values = chartData.byItem.map(function (d) { return parseInt(d.total, 10); });

        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Comptages',
                    data: values,
                    backgroundColor: 'rgba(24, 119, 242, 0.75)',
                    borderColor: '#1877f2',
                    borderWidth: 1,
                    borderRadius: 4,
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false },
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                },
            },
        });
    }

    function renderLine(ctx) {
        const labels = chartData.byDay.map(function (d) { return d.day; });
        const values = chartData.byDay.map(function (d) { return parseInt(d.total, 10); });

        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Comptages par jour',
                    data: values,
                    fill: true,
                    backgroundColor: 'rgba(24, 119, 242, 0.12)',
                    borderColor: '#1877f2',
                    tension: 0.3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'top' },
                    tooltip: { mode: 'index', intersect: false },
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                },
            },
        });
    }

    function renderDonut(ctx) {
        const labels = chartData.byUser.map(function (d) { return d.user_name; });
        const values = chartData.byUser.map(function (d) { return parseInt(d.total, 10); });
        const bg     = COLORS.slice(0, labels.length);

        return new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: bg,
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                var total = ctx.dataset.data.reduce(function (a, b) { return a + b; }, 0);
                                var pct   = total > 0 ? Math.round((ctx.parsed / total) * 100) : 0;
                                return ' ' + ctx.label + ' : ' + ctx.parsed + ' (' + pct + ' %)';
                            },
                        },
                    },
                },
            },
        });
    }

    function init(data) {
        chartData = data;
        render('bar');

        document.querySelectorAll('[data-chart-type]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                render(btn.dataset.chartType);
            });
        });
    }

    return { init: init, render: render };
}());
