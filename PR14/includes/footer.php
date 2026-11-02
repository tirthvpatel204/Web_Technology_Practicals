        </main>
        <!-- Footer -->
        <footer class="py-3 px-4 bg-white border-top text-muted small d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div>
                &copy; <?= date('Y') ?> <strong>Practical 14 Admin Portal</strong> &bull; Role-Based Access Control, Dynamic Menus &amp; Audit Trail
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-secondary border">PHP <?= phpversion() ?></span>
                <span class="badge bg-light text-secondary border">MySQL (PDO)</span>
                <span class="text-secondary">Session ID: <code class="text-muted"><?= substr(session_id(), 0, 8) ?>...</code></span>
            </div>
        </footer>
    </div>
</div>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js for Advanced Analytics Extension -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<!-- App Interactivity Controller -->
<script src="<?= $assetPrefix ?? '' ?>assets/js/app.js"></script>
</body>
</html>
