<?php
require_once("./includes/functions/session.php");
require_once("./includes/functions/connection.php");
require_once("./includes/functions/functions.php");

if (logged_in()) { // if logged in check account rank
    try {
        $adquery = "SELECT userID, accountRank FROM useraccounts WHERE userID = :userID";
        $stmt = $connection->prepare($adquery);
        $stmt->bindParam(':userID', $_SESSION['user_id']);
        $stmt->execute();
        $adquery = $stmt->fetch(PDO::FETCH_ASSOC);

        $isAdmin = $adquery['accountRank'];
    } catch (PDOException $e) {
        die("Database query failed: " . $e->getMessage());
    }
}

// router dynamic links causes issues so this is so I don't have to change link multiple places
$base_url = "http://localhost/project-cinema";

?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="shortcut icon" href="<?php echo $base_url; ?>/includes/assets/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/includes/css/theme.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/includes/css/style.css">
</head>

<body class="bg-dark">
    <div id="pg-content">
        <nav class="navbar navbar-expand-lg fixed-top border-bottom border-light-subtle" id="header">
            <div class="container-fluid">
                <div>
                    <a class="navbar-brand pix-logo" href="/project-cinema">
                        <img src="<?php echo $base_url; ?>/includes/assets/logo.png" alt="">
                        Piximation
                    </a>
                </div>
                <div>
                    <?php
                    try {
                        $tagQuery = "SELECT * FROM tag WHERE tagType = 1";
                        $tagStmt = $connection->prepare($tagQuery);
                        $tagStmt->execute();
                        $mainTags = $tagStmt->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        die("Database query failed: " . $e->getMessage());
                    }

                    //create nav items but only show them in the header on other pages than the front page
                    $navitems = '
                            <div id="categorys">
                                <div>
                                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">';

                    // Loop through the fetched tags and create list items
                    foreach ($mainTags as $tag) {
                        $navitems .= '<li class="nav-item">
                                        <a class="nav-link" href="' . $base_url . '/tag/id/' . $tag['tagID'] . '">' . $tag['name'] . '</a>
                                    </li>';
                    }

                    $navitems .= '</ul>
                                </div>
                            </div>
                        ';

                    if ($page_title != "Piximation Cinema") {
                        echo $navitems;
                    };
                    ?>
                </div>
                <div>
                    <?php //logic for login button
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

        <?php /*debug show user rank
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
        };*/
        ?>