<?php
$page_title = "Admin Board";
include_once "./constants/header.php";
if (logged_in()) {
    if (!$isAdmin == 0) {
        redirect_to("/project-cinema/account-page");
    };
} else {
    redirect_to("/project-cinema/login");
};
ob_start();
?>

<h1>
    admin board
</h1>

<?php include_once "./includes/functions/tb_actors.php";    ?>

<?php include_once "./includes/functions/tb_tag.php";    ?>



<?php include_once "./constants/footer.php";
ob_end_flush();    ?>