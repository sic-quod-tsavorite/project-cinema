<?php
$page_title = "Piximation Cinema";
include_once "./constants/pre-html.php";
include_once "./constants/header.php";

try { // Select 4 movies with isNews = 1
    $query = "SELECT * FROM movies WHERE isNews = 1 LIMIT 4";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $newMovies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
?>

<div class="d-flex">
    <?php foreach ($newMovies as $movie): ?>
        <a class="banner-img" href="<?php echo $base_url; ?>/movie/id/<?php echo $movie['movieID']; ?>">
            <img src="<?php echo $movie['poster']; ?>" alt="<?php echo $movie['title']; ?>">
        </a>
    <?php endforeach; ?>
</div>

<nav class="navbar navbar-expand-lg bg-transparent sticky-top justify-content-center" id="fp-nav">
    <?php echo $navitems;   ?>
</nav>

<div class="container bg-primary-subtle rounded-3 px-5 my-5">
    <nav class="navbar navbar-expand-lg pt-5">
        <div class="container-fluid justify-content-center">
            <ul class="nav" id="npu-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="np-tab"
                        data-bs-toggle="tab" data-bs-target="#nowplaying" type="button" role="tab" aria-controls="nowplaying" aria-selected="true">Now playing</button>
                </li>
                <h4 class="mt-1 pe-none">|</h4>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                        data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="false">Upcoming</button>
                </li>
            </ul>
        </div>
    </nav>

    <?php include_once "./views/content/tb_now_upcoming.php"; ?>
    <div class="tab-content" id="npu-content">
        <div id="nowplaying" class="tab-pane fade show active" role="tabpanel" aria-labelledby="nowplaying-tab" tabindex="0">
            <div class="container d-flex flex-column">
                <?php
                $connection = createConnection();
                displayMovies(1, $connection);
                ?>
            </div>
        </div>
        <div id="upcoming" class="tab-pane fade" role="tabpanel" aria-labelledby="upcoming-tab" tabindex="0">
            <div class="container d-flex flex-column">
                <?php
                $connection = createConnection();
                displayMovies(2, $connection);
                ?>
            </div>
        </div>
    </div>
</div>

<?php include_once "./constants/footer.php";    ?>