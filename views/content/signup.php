<?php
// START FORM PROCESSING
if (isset($_POST['submit'])) { // Form has been submitted.

    // Perform validations on the form data
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Sanitize email
        $washEmail = htmlspecialchars($email);

        // Password pattern
        $passwordPattern = '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';

        // Validate password using the regex pattern
        if (!preg_match($passwordPattern, $password)) {
            $message = "Password must contain at least one number and one uppercase and lowercase letter, and be at least 8 characters long.";
        } else {
            // Sanitize password
            $washPassword = htmlspecialchars($password);

            // Hash the password with bcrypt and cost factor
            $iterations = ['cost' => 15];
            $hashed_password = password_hash($washPassword, PASSWORD_BCRYPT, $iterations);

            try {
                // Prepare the SQL query to insert user
                $query = "INSERT INTO useraccounts (username, password) VALUES (:username, :hashed_password)";
                $stmt = $connection->prepare($query);

                // Bind parameters
                $stmt->bindParam(':username', $washEmail);
                $stmt->bindParam(':hashed_password', $hashed_password);

                // Execute the query
                $result = $stmt->execute();

                if ($result) {
                    $message = "User Created.";
                    header("Location: " . $base_url . "/login?signup=success");
                    exit();
                } else {
                    $message = "User could not be created.";
                }
            } catch (PDOException $e) {
                $message = "User could not be created. Error: " . $e->getMessage();
            }
        }
    }

    if (!empty($message)) {
        echo "<p>" . $message . "</p>";
    }
}
?>

<h2 class="text-center mb-3 mt-5">Create New User</h2>
<form action="<?php echo $base_url; ?>/login?signup=success" method="post" class="container p-5">
    <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" name="email" maxlength="255" value="" class="form-control" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" data-bs-toggle="tooltip" data-bs-title="example@email.com" />
    </div>
    <br>
    <div class="mb-4">
        <label for="password" class="form-label">Password:</label>
        <div class="input-group mb-3">
            <input type="password" name="password" id="password-up" maxlength="255" value="" class="form-control rounded-start" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bs-toggle="tooltip" data-bs-title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" />
            <div class="input-group-text">
                <input type="checkbox" class="btn-check" id="psw-check-up" autocomplete="off">
                <label id="toggle-password-up" class="btn btn-transparent btn-sm rounded border-light" for="psw-check-up"><i class="bi bi-eye-fill"></i></label>
            </div>
        </div>
    </div>
    <input type="submit" name="submit" value="Create" class="btn btn-primary mb-4 px-3" />
    <input type="hidden" class="g-recaptcha" data-sitekey="reCAPTCHA_site_key" data-callback='onSubmit' data-action='submit'>
</form>