<?php
require_once("./includes/functions/session.php");
require_once("./includes/functions/connection.php");
require_once("./includes/functions/functions.php");

try {
    $adquery = "SELECT userID, accountRank FROM useraccounts WHERE userID = :userID";
    $stmt = $connection->prepare($adquery);
    $stmt->bindParam(':userID', $_SESSION['user_id']);
    $stmt->execute();
    $adquery = $stmt->fetch(PDO::FETCH_ASSOC);
    if (logged_in()) {
        $isAdmin = $adquery['accountRank'];
    };
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./includes/css/theme.css">
    <link rel="stylesheet" href="./includes/css/style.css">
</head>

<body class="bg-dark">
    <nav class="navbar navbar-expand-lg sticky-top border-bottom border-light-subtle" id="header">
        <div class="container-fluid">
            <div>
                <a class="navbar-brand" href="/project-cinema">LOGO</a>
            </div>
            <div><!--
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Stop Motion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Hand-Drawn</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Computer 3D</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Modern 2D</a>
                        </li>
                    </ul>
                </div>-->
            </div>
            <div>
                <?php
                if (!logged_in()) {
                    echo '<a class="btn btn-primary ms-2" type="button" href="/project-cinema/login">Log in</a>';
                } else {
                    if ($isAdmin == 0) {
                        echo '<a class="btn btn-accent bi bi-person-circle" type="button" href="/project-cinema/admin-board"> Admin Dashboard</a>';
                    } else {
                        echo '<a class="btn btn-accent bi bi-person-circle" type="button" href="/project-cinema/account-page"></a>';
                    };
                    echo '<a class="btn btn-primary ms-2" type="button" href="/project-cinema/logout">Log out</a>';
                }; ?>
            </div>
        </div>
    </nav>

    </nav>

    <?php
    if (logged_in()) {
        if ($isAdmin == 1) {
            echo "the user is not admin - " . $adquery['accountRank'] . " -";
        }
        if ($isAdmin == 0) {
            echo "the user is indeed admin - " . $adquery['accountRank'] . " -";
        }
    };
    if (!logged_in()) {
        echo "the user is not logged in";
    };
    ?>