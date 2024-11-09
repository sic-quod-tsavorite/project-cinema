<?php
$page_title = "Account Page | Piximation Cinema";
include_once "./constants/pre-html.php";
if (logged_in()) {
    if ($isAdmin == 0) { //if user is admin redirect to admin board
        header("Location: $base_url/admin-board");
	    exit;
    };
} else { //if user is not logged in redirect to login page
    header("Location: $base_url/login");
	exit;
};
include_once "./constants/header.php";
?>

<h1>
    user account page
</h1>

<?php include_once "./constants/footer.php";    ?>