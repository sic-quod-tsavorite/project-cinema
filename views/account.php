<?php
$page_title = "Account Page";
include_once "./constants/header.php";
if (logged_in()) {
    if ($isAdmin == 0) {
        redirect_to("/project-cinema/admin-board");
    };
} else {
    redirect_to("/project-cinema/login");
};
?>

<h1>
    user account page
</h1>

<?php include_once "./constants/footer.php";    ?>