<?php
$page_title = "My Profile";
require_once '../includes/auth.php';
require_role('student');
require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle Form Submission for Profile Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    $profile_pic = null;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $file_name = $_FILES['profile_pic']['name'];
        $file_tmp = $_FILES['profile_pic']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($file_ext, $allowed)) {
            $new_filename = 'profile_' . $user_id . '_' . time() . '.' . $file_ext;
            $upload_dir = __DIR__ . '/../uploads/profiles/';
            
            // Ensure directory exists
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $upload_path = $upload_dir . $new_filename;
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $profile_pic = $new_filename;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid image format. Allowed: JPG, JPEG, PNG, GIF, WEBP.";
        }
    }

    if (empty($error)) {
        try {
            if ($profile_pic) {
                $stmt = $pdo->prepare("UPDATE students SET name = ?, phone = ?, address = ?, profile_pic = ? WHERE user_id = ?");
                $stmt->execute([$name, $phone, $address, $profile_pic, $user_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE students SET name = ?, phone = ?, address = ? WHERE user_id = ?");
                $stmt->execute([$name, $phone, $address, $user_id]);
            }

            // Update user name in session and users table
            $stmt_user = $pdo->prepare("UPDATE users SET name = ? WHERE id = ?");
            $stmt_user->execute([$name, $user_id]);
            $_SESSION['name'] = $name;

            $success = "Profile updated successfully!";
        } catch (Exception $e) {
            $error = "Failed to update profile: " . $e->getMessage();
        }
    }
}

// Fetch current student details
$stmt = $pdo->prepare("SELECT * FROM students WHERE user_id = ?");
$stmt->execute([$user_id]);
$student = $stmt->fetch();

require_once '../includes/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold text-navy mb-4"><i class="fas fa-user-graduate text-cyan me-2"></i> Student Profile & Settings</h2>
            
            <?php if($success): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-1"></i> <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if($error): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <!-- Left Column: Profile Card -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white h-100">
                        <div class="mb-3">
                            <?php if(!empty($student['profile_pic']) && $student['profile_pic'] != 'default.png' && file_exists(__DIR__ . '/../uploads/profiles/' . $student['profile_pic'])): ?>
                                <img src="<?= BASE_URL ?>uploads/profiles/<?= htmlspecialchars($student['profile_pic']) ?>" class="rounded-circle shadow" width="130" height="130" style="object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-navy text-cyan rounded-circle d-inline-flex align-items-center justify-content-center shadow mx-auto" style="width: 130px; height: 130px; font-size: 55px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <h4 class="fw-bold text-navy mb-1"><?= htmlspecialchars($student['name'] ?? '') ?></h4>
                        <p class="text-muted small mb-2 fw-bold"><?= htmlspecialchars($student['enrollment_number'] ?? '') ?></p>
                        
                        <div class="mt-2">
                            <span class="badge bg-cyan text-white px-3 py-2 text-wrap shadow-sm" style="max-width: 100%; display: inline-block; line-height: 1.4;">
                                Sem <?= htmlspecialchars($student['semester'] ?? '1') ?> – <?= htmlspecialchars($student['department'] ?? 'N/A') ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Edit Profile Form -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <h4 class="fw-bold text-navy mb-3">Edit Profile Details</h4>
                        <hr class="mb-4">
                        
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($student['name'] ?? '') ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Address (Read-only)</label>
                                <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($student['email'] ?? '') ?>" readonly>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" placeholder="Enter phone number" value="<?= htmlspecialchars($student['phone'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Upload Profile Picture</label>
                                    <input type="file" name="profile_pic" class="form-control" accept="image/*">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Address</label>
                                <textarea name="address" rows="3" class="form-control" placeholder="Enter residential address"><?= htmlspecialchars($student['address'] ?? '') ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-cyan text-white fw-bold px-4 py-2 shadow">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>