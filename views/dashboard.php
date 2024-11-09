<?php
$page_title = "Admin Dashboard | Piximation Cinema";
include_once "./constants/pre-html.php";
if (logged_in()) {
    if (!$isAdmin == 0) { //if user is not admin redirect to account page
        header("Location: $base_url/account-page");
	    exit;
    };
} else { //if user is not logged in redirect to login page
    header("Location: $base_url/login");
	exit;
};

include_once "./constants/header.php";
?>

<h1>
    admin board
</h1>

<?php ob_start(); //output buffering
// Content related to the table actors
include_once "content/tb_actors.php";  ?>

<?php // Content related to the table tag
include_once "content/tb_tag.php";   ?>

<?php // Content related to the table movies
include_once "content/tb_movies.php";    ?>

<?php include_once "./constants/footer.php";
ob_end_flush(); //end output buffering  
?>