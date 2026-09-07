<?php
session_start();

// --- Configuration ---
$admin_password = 'admin'; // VERY simple password for demo purposes. User should change this!
$upload_target = 'images/photo.jpg'; // The exact file to overwrite

$message = '';
$is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;

// --- Handle Login ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['is_logged_in'] = true;
        $is_logged_in = true;
        $message = '<div class="alert alert--success">Logged in successfully.</div>';
    } else {
        $message = '<div class="alert alert--error">Incorrect password.</div>';
    }
}

// --- Handle Logout ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// --- Handle File Upload ---
if ($is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_photo'])) {
    if (isset($_FILES['new_photo']) && $_FILES['new_photo']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['new_photo']['tmp_name'];
        
        // Basic security check to ensure it's an image
        if (getimagesize($tmp_name) !== false) {
            
            // Ensure the images directory exists and is writable
            if (!is_dir('images')) {
                mkdir('images', 0777, true);
            }
            
            // Overwrite the existing photo.jpg
            if (move_uploaded_file($tmp_name, $upload_target)) {
                $message = '<div class="alert alert--success">Profile photo updated successfully! <a href="index.php">View site</a></div>';
            } else {
                $message = '<div class="alert alert--error">Failed to save the image (Permission denied). Check folder permissions.</div>';
            }
        } else {
            $message = '<div class="alert alert--error">Uploaded file is not a valid image.</div>';
        }
    } else {
        $message = '<div class="alert alert--error">Error uploading file. Error Code: ' . ($_FILES['new_photo']['error'] ?? 'Unknown') . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
            background-color: var(--color-bg);
        }
        .admin-card {
            background: var(--color-surface);
            padding: 2.5rem;
            border-radius: var(--radius-lg);
            border: 1px solid var(--color-border);
            width: 100%;
            max-width: 500px;
            box-shadow: var(--shadow);
            text-align: center;
        }
        .admin-header {
            margin-bottom: 2rem;
        }
        .admin-header h1 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .current-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--color-border);
            margin: 0 auto 2rem;
            display: block;
        }
        .logout-link {
            display: inline-block;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--color-text-muted);
        }
    </style>
</head>
<body>

    <div class="admin-card">
        <div class="admin-header">
            <h1>⚙️ Portfolio Admin</h1>
            <p style="color: var(--color-text-muted);">Manage your site completely.</p>
        </div>

        <?php echo $message; ?>

        <?php if (!$is_logged_in): ?>
            <!-- Login Form -->
            <form method="post" action="admin.php">
                <div class="form__group" style="text-align: left;">
                    <input type="password" id="password" name="password" placeholder=" " required style="background: var(--color-bg-alt);">
                    <label for="password">Admin Password</label>
                </div>
                <button type="submit" name="login" class="btn btn--primary btn--full" style="margin-top: 1.5rem;">Secure Login</button>
            </form>
        <?php else: ?>
            <!-- Dashboard -->
            <img src="images/photo.jpg?v=<?php echo time(); ?>" alt="Current Profile Photo" class="current-photo" onerror="this.src=''; this.alt='No Image';">
            
            <form method="post" action="admin.php" enctype="multipart/form-data">
                <div class="form__group form__group--file" style="text-align: left;">
                    <label for="new_photo" class="file-label" style="font-weight: 600;">Upload New Profile Picture (JPG/PNG)</label>
                    <input type="file" id="new_photo" name="new_photo" accept="image/*" class="form__file" required>
                </div>
                <button type="submit" name="upload_photo" class="btn btn--primary btn--full" style="margin-top: 1.5rem;">Update Photo</button>
            </form>

            <a href="?logout=1" class="logout-link">Log out</a>
        <?php endif; ?>
    </div>

</body>
</html>
