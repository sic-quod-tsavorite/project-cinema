<?php
$page_title = "login";
include_once "./constants/header.php";
if (logged_in()) {
	redirect_to("/project-cinema/");
};
?>

<?php
// START FORM PROCESSING
if (isset($_POST['submit'])) { // Form has been submitted.
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	try {
		// Prepare the SQL query using PDO
		$query = "SELECT userID, username, password FROM useraccounts WHERE username = :username LIMIT 1";
		$stmt = $connection->prepare($query);

		// Bind the username parameter
		$stmt->bindParam(':username', $username);
		$stmt->execute();

		// Fetch the result
		$found_user = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($found_user) {
			// Check if the password is correct
			if (password_verify($password, $found_user['password'])) {
				// Username/password authenticated
				$_SESSION['user_id'] = $found_user['userID'];
				$_SESSION['user'] = $found_user['username'];
				redirect_to("/project-cinema/");
			} else {
				// Password is incorrect
				$message = "Username/password combination incorrect.<br />
					Please make sure your caps lock key is off and try again.";
			}
		} else {
			// No user found
			$message = "Username/password combination incorrect.<br />
				Please make sure your caps lock key is off and try again.";
		}
	} catch (PDOException $e) {
		die("Database query failed: " . $e->getMessage());
	}
} else { // Form has not been submitted.
	if (isset($_GET['logout']) && $_GET['logout'] == 1) {
		$message = "You are now logged out.";
	}
}

// Display the message if set
if (!empty($message)) {
	echo "<p>" . $message . "</p>";
}
?>

<h2>Please login</h2>
<form action="/project-cinema/login" method="post">
	Username:
	<input type="text" name="username" maxlength="30" value="" />
	Password:
	<input type="password" name="password" maxlength="30" value="" />
	<input type="submit" name="submit" value="Login" />
</form>

<?php include_once "newuser.php";    ?>

<?php include_once "./constants/footer.php";    ?>