/**
 * Practical 14: Client-Side Interactive Controller
 */
document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Sidebar Toggle
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.app-sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            if (overlay) overlay.classList.toggle('show');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    // 2. Demo Credentials Auto-Fill (Login Page)
    document.querySelectorAll('[data-demo-email]').forEach(function (button) {
        button.addEventListener('click', function () {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            if (emailInput && passwordInput) {
                emailInput.value = this.getAttribute('data-demo-email');
                passwordInput.value = this.getAttribute('data-demo-password');
                emailInput.focus();
            }
        });
    });

    // 3. Quick Table Search Filter
    const searchInputs = document.querySelectorAll('[data-table-search]');
    searchInputs.forEach(function (input) {
        input.addEventListener('input', function () {
            const targetTableId = this.getAttribute('data-table-search');
            const query = this.value.toLowerCase().trim();
            const table = document.getElementById(targetTableId);
            if (!table) return;

            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(function (row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    });

    // 4. Analytics Chart Initialization (Chart.js)
    const monthlyCanvas = document.getElementById('monthlyRegistrationsChart');
    if (monthlyCanvas && typeof Chart !== 'undefined') {
        const rawLabels = monthlyCanvas.getAttribute('data-labels') || '[]';
        const rawData = monthlyCanvas.getAttribute('data-values') || '[]';

        try {
            const labels = JSON.parse(rawLabels);
            const data = JSON.parse(rawData);

            new Chart(monthlyCanvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Registrations',
                        data: data,
                        backgroundColor: 'rgba(79, 70, 229, 0.85)',
                        borderColor: '#4338ca',
                        borderWidth: 1.5,
                        borderRadius: 8,
                        hoverBackgroundColor: '#4f46e5'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 12,
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 12 },
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { size: 11 } },
                            grid: { color: '#f1f5f9' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        } catch (e) {
            console.error('Failed to parse chart data:', e);
        }
    }

    // Role Distribution Doughnut Chart
    const roleCanvas = document.getElementById('roleDistributionChart');
    if (roleCanvas && typeof Chart !== 'undefined') {
        const rawRoles = roleCanvas.getAttribute('data-roles') || '[]';
        const rawCounts = roleCanvas.getAttribute('data-counts') || '[]';

        try {
            const roleLabels = JSON.parse(rawRoles);
            const roleCounts = JSON.parse(rawCounts);

            new Chart(roleCanvas, {
                type: 'doughnut',
                data: {
                    labels: roleLabels.map(r => r.toUpperCase()),
                    datasets: [{
                        data: roleCounts,
                        backgroundColor: [
                            '#8b5cf6', // admin (purple)
                            '#0284c7', // editor (blue)
                            '#64748b'  // viewer (slate)
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, font: { size: 11 } }
                        }
                    },
                    cutout: '68%'
                }
            });
        } catch (e) {
            console.error('Failed to parse role distribution chart data:', e);
        }
    }
});
