<?php
// Dynamic page for Tags/Categories

// Require and establish connection earlier to create page title
include_once "./constants/pre-html.php";
$connection = createConnection();

// Select the tag based on the $id from routes.php
try {
    $query = "SELECT * FROM tag WHERE tagID = $id";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

// Page title
$page_title = $tags[0]['name'] . " | Piximation Cinema";

// Head
include_once "./constants/header.php";
?>

<div class="container d-flex flex-column tag-page pt-5">
    <h1 class="pb-5"><b>Showing category:</b> <i><?php echo $tags[0]['name']; ?></i></h1>
    <div class="container bg-primary-subtle rounded-3 px-5 my-5">
        <nav class="navbar navbar-expand-lg pt-5">
            <div class="container-fluid justify-content-center">
                <ul class="nav" id="npu-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="np-tab" data-bs-toggle="tab" data-bs-target="#nowplaying" type="button" role="tab" aria-controls="nowplaying" aria-selected="true">Now playing</button>
                    </li>
                    <h4 class="mt-1 pe-none">|</h4>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="false">Upcoming</button>
                    </li>
                </ul>
            </div>
        </nav>

        <?php include_once "./views/content/tb_now_upcoming.php"; ?>
        <div class="tab-content" id="npu-content">
            <div id="nowplaying" class="tab-pane fade show active" role="tabpanel" aria-labelledby="nowplaying-tab" tabindex="0">
                <div class="container d-flex flex-column">
                    <?php 
                    displayMovies(1, $connection, $id);
                    ?>
                </div>
            </div>

            <div id="upcoming" class="tab-pane fade" role="tabpanel" aria-labelledby="upcoming-tab" tabindex="0">
                <div class="container d-flex flex-column">
                    <?php 
                    displayMovies(2, $connection, $id);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "./constants/footer.php"; ?>