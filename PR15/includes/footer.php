    <!-- Footer -->
    <footer class="bg-navy text-white pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-cyan mb-3"><i class="fas fa-graduation-cap me-2"></i><?= SITE_NAME ?></h5>
                    <p class="text-white-50">Smart College Management & Student Portal engineered for seamless academic operations, modern tracking, and role-based excellence.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="text-uppercase fw-bold text-cyan mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= BASE_URL ?>" class="text-white-50 text-decoration-none">Home</a></li>
                        <li><a href="<?= BASE_URL ?>about.php" class="text-white-50 text-decoration-none">About Us</a></li>
                        <li><a href="<?= BASE_URL ?>contact.php" class="text-white-50 text-decoration-none">Contact</a></li>
                        <li><a href="<?= BASE_URL ?>faq.php" class="text-white-50 text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="text-uppercase fw-bold text-cyan mb-3">Portals</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= BASE_URL ?>login.php" class="text-white-50 text-decoration-none">Student Portal</a></li>
                        <li><a href="<?= BASE_URL ?>login.php" class="text-white-50 text-decoration-none">Faculty Portal</a></li>
                        <li><a href="<?= BASE_URL ?>login.php" class="text-white-50 text-decoration-none">Admin Control</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="text-uppercase fw-bold text-cyan mb-3">Contact Info</h6>
                    <p class="text-white-50 mb-1"><i class="fas fa-map-marker-alt me-2 text-cyan"></i> University Campus Sector, Changa, Gujarat</p>
                    <p class="text-white-50 mb-1"><i class="fas fa-envelope me-2 text-cyan"></i> support@studenthub.edu</p>
                    <p class="text-white-50"><i class="fas fa-phone me-2 text-cyan"></i> +91 98765 43210</p>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="row text-center text-white-55">
                <div class="col-md-12">
                    <p class="mb-0">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All Rights Reserved. Built with PHP, MySQL & Bootstrap 5.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom JS -->
    <script src="<?= BASE_URL ?>assets/js/app.js"></script>
    <script src="<?= BASE_URL ?>assets/js/validation.js"></script>
    <script src="<?= BASE_URL ?>assets/js/api.js"></script>
</body>
</html>
