<?php
// START FORM PROCESSING
if (isset($_POST['submit'])) { // Form has been submitted.

    // Perform validations on the form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Sanitize password
    $washPassword = htmlspecialchars($password);

    // Hash the password with bcrypt and cost factor
    $iterations = ['cost' => 15];
    $hashed_password = password_hash($washPassword, PASSWORD_BCRYPT, $iterations);

    try {
        // Prepare the SQL query to insert user
        $query = "INSERT INTO useraccounts (username, password) VALUES (:username, :hashed_password)";
        $stmt = $connection->prepare($query);

        // Sanitize username
        $washUsername = htmlspecialchars($username);

        // Bind parameters
        $stmt->bindParam(':username', $washUsername);
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

if (!empty($message)) {
    echo "<p>" . $message . "</p>";
}
?>
<h2>Create New User</h2>

<form action="<?php echo $base_url;?>/login?signup=success" method="post">
    Username:
    <input type="text" name="username" maxlength="30" value="" />
    Password:
    <input type="password" name="password" maxlength="30" value="" />
    <input type="submit" name="submit" value="Create" />
</form>