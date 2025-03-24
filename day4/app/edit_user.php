<?php
require_once "../DB/db_operation.php";
require_once "../includes/utils.php";
require_once "../validation/imageValidation.php"; // Add this line

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from URL
$userId = $_GET['id'] ?? null;
if (!$userId) {
    header("Location: table.php");
    exit();
}

// Fetch user data
try {
    $user = getUserById($userId);
    if (!$user) {
        throw new Exception("User not found");
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: table.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userData = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'room_no' => $_POST['room_no'],
        'ext' => $_POST['ext'],
        'image_path' => $user['image_path'] // Keep existing unless new file uploaded
    ];

    try {
        // Handle file upload if new image provided
        if (!empty($_FILES['image']['name'])) {
            $allowedExtensions = ["jpg", "jpeg", "png", "gif"];

            // Verify the function exists
            if (!function_exists('validateUploadedFile')) {
                die("Validation function not available");
            }

            $fileValidation = validateUploadedFile($_FILES['image'], $allowedExtensions);

            if (!empty($fileValidation['errors'])) {
                throw new Exception(implode(", ", $fileValidation['errors']));
            }

            $fileValidData = $fileValidation['valid_data'];
            $imageFileName = uniqid() . '.' . $fileValidData['extension'];
            $destination = "../upload/" . $imageFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                // Delete old image if exists
                if (!empty($user['image_path']) && file_exists("../upload/" . $user['image_path'])) {
                    unlink("../upload/" . $user['image_path']);
                }
                $userData['image_path'] = $imageFileName;
            } else {
                throw new Exception("Failed to move uploaded file");
            }
        }

        $updated = updateUser($userId, $userData);
        if ($updated) {
            $_SESSION['message'] = "User updated successfully";
            header("Location: User_Managment.php");
            exit();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-4">Edit User</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control"
                    value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Room No</label>
                <input type="text" name="room_no" class="form-control"
                    value="<?= htmlspecialchars($user['room_no']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Ext</label>
                <input type="text" name="ext" class="form-control"
                    value="<?= htmlspecialchars($user['ext']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Profile Image</label>
                <?php if (!empty($user['image_path'])): ?>
                    <div class="mb-2">
                        <img src="../upload/<?= htmlspecialchars($user['image_path']) ?>"
                            alt="Current Image" width="100">
                    </div>
                <?php endif; ?>
                <input type="file" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="table.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>