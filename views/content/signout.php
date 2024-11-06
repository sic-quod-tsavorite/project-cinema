<?php require_once("././includes/functions/functions.php");
require_once("./includes/functions/connection.php");
require_once("./includes/functions/session.php");
?>
<?php
// Four steps to closing a session
// (i.e. logging out)

// 1. Find the session
session_start();

// 2. Unset all the session variables
$_SESSION = array();

// 3. Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time() - 42000, '/');
}

// 4. Destroy the session
session_destroy();

redirect_to($base_url);
?>