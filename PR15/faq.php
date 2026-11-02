<?php
$page_title = "FAQ";
require_once 'includes/header.php';
?>
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-navy">Frequently Asked Questions</h2>
        <p class="text-muted">Find answers to common questions regarding StudentHub portal features and navigation.</p>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="accordion shadow-sm rounded-4 overflow-hidden" id="faqAccordion">
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button fw-bold text-navy" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            How do I log in to my StudentHub account?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            You can log in using your registered email address and secure password via the <a href="login.php">Login Page</a>. Select your role (Student, Faculty, or Admin) to access your specific dashboard.
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed fw-bold text-navy" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            How is attendance percentage calculated?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Attendance percentage is calculated automatically using the formula: <code>(Present Classes / Total Classes) × 100</code>. Subjects with attendance below 75% are automatically highlighted in red.
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed fw-bold text-navy" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                            Can students modify their attendance or marks?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            No. Role-Based Access Control (RBAC) ensures that only authorized faculty and admin members can record attendance, create assignments, and publish grades. Students have read-only access to their records.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>