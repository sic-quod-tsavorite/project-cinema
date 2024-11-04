<?php
$page_title = "Account Page | Piximation Cinema";
include_once "./constants/header.php";
if (logged_in()) {
    if ($isAdmin == 0) { //if user is admin redirect to admin board
        redirect_to("/project-cinema/admin-board");
    };
} else { //if user is not logged in redirect to login page
    redirect_to("/project-cinema/login");
};
?>

<h1>
    user account page
</h1>

<?php include_once "./constants/footer.php";    ?>