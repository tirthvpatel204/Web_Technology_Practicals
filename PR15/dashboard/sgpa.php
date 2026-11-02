<?php
$page_title = "SGPA Calculator";
require_once '../includes/auth.php';
require_role('student');
require_once '../includes/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold text-navy mb-4"><i class="fas fa-calculator text-cyan me-2"></i> SGPA Calculator</h2>
            <div class="card border-0 shadow-sm rounded-4 p-4 col-md-8">
                <p class="text-muted">Calculate your Semester Grade Point Average (SGPA) based on course credits and grade points earned.</p>
                <div id="course-inputs">
                    <div class="row g-3 mb-3 course-row">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Course Credits</label>
                            <input type="number" class="form-control credit" min="1" max="6" value="4">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Grade Points (0-10)</label>
                            <input type="number" class="form-control points" min="0" max="10" step="0.5" value="9">
                        </div>
                    </div>
                </div>
                <button class="btn btn-outline-cyan fw-bold mb-3" id="add-course-btn"><i class="fas fa-plus me-1"></i> Add Another Course</button>
                <hr>
                <button class="btn btn-cyan text-white fw-bold px-4 shadow" id="calculate-sgpa">Calculate SGPA</button>
                <div class="mt-3 alert alert-info d-none" id="sgpa-result"></div>
            </div>
        </main>
    </div>
</div>
<script>
document.getElementById('add-course-btn').addEventListener('click', function() {
    const container = document.getElementById('course-inputs');
    const div = document.createElement('div');
    div.className = 'row g-3 mb-3 course-row';
    div.innerHTML = '<div class="col-md-6"><label class="form-label fw-bold">Course Credits</label><input type="number" class="form-control credit" min="1" max="6" value="4"></div><div class="col-md-6"><label class="form-label fw-bold">Grade Points (0-10)</label><input type="number" class="form-control points" min="0" max="10" step="0.5" value="9"></div>';
    container.appendChild(div);
});

document.getElementById('calculate-sgpa').addEventListener('click', function() {
    const credits = document.querySelectorAll('.credit');
    const points = document.querySelectorAll('.points');
    let totalCredits = 0;
    let weightedSum = 0;
    for(let i=0; i<credits.length; i++) {
        let c = parseFloat(credits[i].value) || 0;
        let p = parseFloat(points[i].value) || 0;
        totalCredits += c;
        weightedSum += (c * p);
    }
    const sgpa = totalCredits > 0 ? (weightedSum / totalCredits).toFixed(2) : 0;
    const res = document.getElementById('sgpa-result');
    res.classList.remove('d-none');
    res.innerHTML = `<strong>Calculated SGPA:</strong> ${sgpa} (Total Credits: ${totalCredits})`;
});
</script>
<?php require_once '../includes/footer.php'; ?>