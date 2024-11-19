<?php
// START FORM PROCESSING
if (isset($_POST['submit'])) { // Form has been submitted.
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	try {
		// Prepare the SQL query using PDO
		$query = "SELECT userID, username, password FROM useraccounts WHERE username = :username LIMIT 1";
		$stmt = $connection->prepare($query);

		// Sanitize input
		$washUsername = htmlspecialchars($username);
		$washPassword = htmlspecialchars($password);

		// Bind the username parameter
		$stmt->bindParam(':username', $washUsername);
		$stmt->execute();

		// Fetch the result
		$found_user = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($found_user) {
			// Check if the password is correct
			if (password_verify($washPassword, $found_user['password'])) {
				// Username/password authenticated
				$_SESSION['user_id'] = $found_user['userID'];
				$_SESSION['user'] = $found_user['username'];
				header("Location: $base_url");
				exit;
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

<h2 class="text-center mb-3 mt-5">Please login</h2>
<form action="<?php echo $base_url; ?>/login" method="post" class="container p-5">
	<div class="mb-3">
		<label for="email" class="form-label">Email:</label>
		<input type="text" name="username" maxlength="255" value="" class="form-control" data-bs-toggle="tooltip" data-bs-title="example@email.com" />
	</div>

	<div class="mb-4">
		<label for="password" class="form-label">Password:</label>
		<div class="input-group mb-3">
			<input type="password" name="password" id="password-in" maxlength="255" value="" class="form-control rounded-start" />
			<div class="input-group-text">
				<input type="checkbox" class="btn-check" id="psw-check-in" autocomplete="off">
				<label id="toggle-password-in" class="btn btn-transparent btn-sm rounded border-light" for="psw-check-in"><i class="bi bi-eye-fill"></i></label>
			</div>
		</div>
	</div>

	<input type="submit" name="submit" value="Login" class="btn btn-primary mb-4 px-3" />
</form>