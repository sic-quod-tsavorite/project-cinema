<?php
$page_title = "Account Page | Piximation Cinema";
include_once "./constants/pre-html.php";

if (logged_in()) {
    if ($isAdmin == 0) { // if user is admin redirect to admin board
        header("Location: $base_url/admin-board");
        exit;
    }
} else { // if user is not logged in redirect to login page
    header("Location: $base_url/login");
    exit;
}

// Profile picture upload
$profile_picture = '';
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    if (($_FILES['profile_picture']['type'] == 'image/jpeg')
        || ($_FILES['profile_picture']['type'] == 'image/png')
        || ($_FILES['profile_picture']['type'] == 'image/jpg')
        || ($_FILES['profile_picture']['type'] == 'image/pjpeg')
        || ($_FILES['profile_picture']['type'] == 'image/webp')
        && ($_FILES['profile_picture']['size'] <= 5242880) // 5 MiB file size limit
    ) {
        // Extra MIME Type Validation
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $_FILES['profile_picture']['tmp_name']);
        finfo_close($finfo);
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/pjpeg', 'image/webp'];

        if (in_array($mimeType, $allowedMimeTypes)) {
            $targetDir = "includes/assets/uploads/profile_picture/";
            $profile_picture = $targetDir . basename($_FILES["profile_picture"]["name"]);
            move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_picture);

            try {
                // Update profile picture in database
                $query = "UPDATE useraccounts SET profile_picture = :profile_picture WHERE userID = :userID";
                $stmt = $connection->prepare($query);
                $washProfilePicure = htmlspecialchars($profile_picture);
                $stmt->bindParam(':profile_picture', $washProfilePicure);
                $stmt->bindParam(':userID', $_SESSION['user_id']);
                $stmt->execute();

                header("Location: $base_url/account-page?update=pfp");
                exit;
            } catch (PDOException $e) {
                die("Database query failed: " . $e->getMessage());
            }
        } else {
            echo "Error: Invalid profile picture file type. MIME type '$mimeType' is not allowed.";
        }
    }
}

// Password change
if (isset($_POST['change_psw'])) {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);

    try {
        $query = "SELECT password FROM useraccounts WHERE userID = :userID";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':userID', $_SESSION['user_id']);
        $stmt->execute();
        $db_password = $stmt->fetch(PDO::FETCH_ASSOC)['password'];
    } catch (PDOException $e) {
        die("Database query failed: " . $e->getMessage());
    }

    $washCurrentPassword = htmlspecialchars($current_password);

    if (password_verify($washCurrentPassword, $db_password)) {
        // Password pattern
        $passwordPattern = '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';

        $washNewPassword = htmlspecialchars($new_password);

        // Validate password using the regex pattern
        if (!preg_match($passwordPattern, $washNewPassword)) {
            $message = "Password must contain at least one number and one uppercase and lowercase letter, and be at least 8 characters long.";
        } else {
            $iterations = ['cost' => 15];
            $hashed_password = password_hash($washNewPassword, PASSWORD_BCRYPT, $iterations);

            try {
                $query = "UPDATE useraccounts SET password = :password WHERE userID = :userID";
                $stmt = $connection->prepare($query);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':userID', $_SESSION['user_id']);
                $stmt->execute();

                header("Location: $base_url/account-page?update=psw");
                exit;
            } catch (PDOException $e) {
                die("Database query failed: " . $e->getMessage());
            }
        }
    } else {
        echo "Incorrect current password.";
    }
}

// Account deletion
if (isset($_POST['delete'])) {
    try {
        $query = "DELETE FROM useraccounts WHERE userID = :userID";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':userID', $_SESSION['user_id']);
        $stmt->execute();

        session_destroy();
        header("Location: $base_url/login?user=deleted");
        exit;
    } catch (PDOException $e) {
        die("Database query failed: " . $e->getMessage());
    }
}

include_once "./constants/header.php";
?>

<h2 class="text-center mb-3 mt-5">Account Page</h2>

<div class="container bg-primary-subtle rounded-3 p-5 my-5" style="width: 700px;">
    <div class="row d-flex justify-content-center my-5">
        <div class="col-md-6 d-flex flex-column justify-content-center">

            <!-- Profile Picture -->
            <div class="profile-pic">
                <label class="-label" for="profile_picture">
                    <i class="bi bi-camera-fill me-2"></i>
                    <span>Change Image</span>
                </label>
                <form action="<?php echo $base_url; ?>/account-page" method="POST" enctype="multipart/form-data" id="profile-picture-form">
                    <input id="profile_picture" type="file" onchange="submitForm(this.form)" name="profile_picture" value="profile_picture" />
                </form>

                <?php
                $user_id = $_SESSION['user_id'];
                try {
                    $query = "SELECT profile_picture FROM useraccounts WHERE userID = :userID";
                    $stmt = $connection->prepare($query);
                    $stmt->bindParam(':userID', $user_id);
                    $stmt->execute();
                    $profile_picture = $stmt->fetch(PDO::FETCH_ASSOC)['profile_picture'];
                } catch (PDOException $e) {
                    die("Database query failed: " . $e->getMessage());
                }

                if ($profile_picture && file_exists($profile_picture)) {
                    echo "<img src='$profile_picture' id='output' width='200' /> ";
                } else {
                    echo "<img src='" . $base_url . "/includes/assets/uploads/profile_picture/default-avatar.png' id='output' width='200' /> ";
                }
                ?>
            </div>

            <!-- Show Email -->
            <h4 class="text-center mb-5 mt-5"><b>Email:</b> <?php echo $_SESSION['user']; ?></h4>

            <div class="d-flex justify-content-center">
                <button class="btn btn-primary mb-3" onclick="togglePasswordForm()">Change Password</button>
            </div>

            <!-- Password Change Form -->
            <form action="<?php echo $base_url; ?>/account-page" method="POST" style="display: none;" id="password-form">
                <div class="row d-flex justify-content-center mb-5">
                    <label for="crnt_psw_grp" class="form-label">Current Password:</label>
                    <div class="input-group mb-3 crnt_psw_grp">
                        <input type="password" class="form-control rounded-start" id="current_password" name="current_password" maxlength="255">
                        <div class="input-group-text">
                            <input type="checkbox" class="btn-check" id="psw-check-crnt" autocomplete="off">
                            <label id="toggle-password-crnt" class="btn btn-transparent btn-sm rounded border-light" for="psw-check-crnt"><i class="bi bi-eye-fill"></i></label>
                        </div>
                    </div>
                    <label for="n_psw_grp" class="form-label">New Password:</label>
                    <div class="input-group mb-3 n_psw_grp">
                        <input type="password" class="form-control rounded-start" id="new_password" name="new_password" maxlength="255" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bs-toggle="tooltip" data-bs-title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
                        <div class="input-group-text">
                            <input type="checkbox" class="btn-check" id="psw-check-new" autocomplete="off">
                            <label id="toggle-password-new" class="btn btn-transparent btn-sm rounded border-light" for="psw-check-new"><i class="bi bi-eye-fill"></i></label>
                        </div>
                    </div>
                    <input type="hidden" name="change_psw" value="change_psw">
                    <button type="submit" class="btn btn-secondary" value="change_psw" id="cnf_psw_chg" onclick="return confirm('Are you sure you want to change your password?')">Confirm Password Change</button>
                </div>
            </form>

            <!-- Account Deletion Form -->
            <form action="<?php echo $base_url; ?>/account-page" method="POST" class="d-flex justify-content-center">
                <input type="hidden" name="delete" value="true">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account?')">Delete Account</button>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo $base_url; ?>/includes/scripts/ac_script.js"></script>
<?php include_once "./constants/footer.php"; ?>