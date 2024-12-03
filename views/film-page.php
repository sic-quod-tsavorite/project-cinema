<?php
// Dynamic page for movies

// Require and establish connection earlier to create page title
include_once "./constants/pre-html.php";
$connection = createConnection();

// Select the movie based on the $id from routes.php
try {
    $query = "SELECT * FROM movies WHERE movieID = $id";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

// Page title
$page_title = $movies[0]['title'] . " | Piximation Cinema";

// Head
include_once "./constants/header.php";

// Junction with actorrole table
try {
    $query = "SELECT a.first_name, a.last_name, ar.role 
              FROM actors a
              INNER JOIN actorrole ar ON a.actorID = ar.actorID
              WHERE ar.movieID = :movieID";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':movieID', $id);
    $stmt->execute();
    $actors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

// Junction with movietags table
try {
    $query = "SELECT t.name, t.tagID
              FROM tag t
              INNER JOIN movietags mt ON t.tagID = mt.tagID
              WHERE mt.movieID = :movieID";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':movieID', $id);
    $stmt->execute();
    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

// AirMovieShowings
function getMovieShowingsByDate($id, $selectedDate, $connection) {
    try {
        $query = "SELECT am.amsID, c.name as cinemaHallName, m.title as movieTitle, am.showDate, am.showTime 
                  FROM airmovieshowings am 
                  JOIN cinemahalls c ON am.cinemaHallID = c.cinemaHallID 
                  JOIN movies m ON am.movieID = m.movieID 
                  WHERE am.showDate = :selectedDate AND am.movieID = :id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':selectedDate', $selectedDate);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Database query failed: " . $e->getMessage());
    }
}

?>

<div id="film-hero">
    <img class="z-n1" src="<?php echo $base_url . '/' . $movies[0]['heroimg']; ?>" alt="">
    <div class="container d-flex">
        <div>
            <h2><?php echo $movies[0]['title']; ?></h2>
            <p><?php echo $movies[0]['description']; ?></p>
        </div>
        <video height="450" controls autoplay loop muted>
            <source src="<?php echo $base_url . '/' . $movies[0]['trailer']; ?>" type="video/mp4">
        </video>
    </div>
    <div>
        <h3>Starring:</h3>
        <ul>
            <?php foreach ($actors as $actor): ?>
                <li><?php echo $actor['first_name'] . ' ' . $actor['last_name'] . ' - As: ' . $actor['role']; ?></li>
            <?php endforeach; ?>
        </ul>
        <h3>Genre:</h3>
        <ul>
            <?php foreach ($tags as $tag): ?>
                <li>
                    <?php echo '<a class="link-offset-1 link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="' . $base_url . '/tag/id/' . $tag['tagID'] . '">' . $tag['name'] . '</a>' ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="container bg-primary-subtle rounded-3 p-5 my-5">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid justify-content-center">
                <ul class="nav" id="npu-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="np-tab"
                            data-bs-toggle="tab" data-bs-target="#day1" type="button" role="tab" aria-controls="day1" aria-selected="true"><?php echo date("d") ?>.<br><?php echo date("D") ?>.</button>
                    </li>
                    <h4 class="mt-1 pe-none">|</h4>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link"
                            data-bs-toggle="tab" data-bs-target="#day2" type="button" role="tab" aria-controls="day2" aria-selected="false">
                            <?php
                            $date = date_create(date("Y-m-d"));
                            date_modify($date, "+1 day");
                            echo date_format($date, "d");
                            ?>.
                            <br>
                            <?php
                            $date = date_create(date("Y-m-d"));
                            date_modify($date, "+1 day");
                            echo date_format($date, "D");
                            ?>.
                        </button>
                    </li>
                    <h4 class="mt-1 pe-none">|</h4>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link"
                            data-bs-toggle="tab" data-bs-target="#day3" type="button" role="tab" aria-controls="day3" aria-selected="false">
                            <?php
                            $date = date_create(date("Y-m-d"));
                            date_modify($date, "+2 days");
                            echo date_format($date, "d");
                            ?>.
                            <br>
                            <?php
                            $date = date_create(date("Y-m-d"));
                            date_modify($date, "+2 days");
                            echo date_format($date, "D");
                            ?>.
                        </button>
                    </li>
                    <h4 class="mt-1 pe-none">|</h4>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link"
                            data-bs-toggle="tab" data-bs-target="#day4" type="button" role="tab" aria-controls="day4" aria-selected="false">
                            <?php
                            $date = date_create(date("Y-m-d"));
                            date_modify($date, "+3 days");
                            echo date_format($date, "d");
                            ?>.
                            <br>
                            <?php
                            $date = date_create(date("Y-m-d"));
                            date_modify($date, "+3 days");
                            echo date_format($date, "D");
                            ?>.
                        </button>
                    </li>
                    <h4 class="mt-1 pe-none">|</h4>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link"
                            data-bs-toggle="tab" data-bs-target="#day5" type="button" role="tab" aria-controls="day5" aria-selected="false">
                            <?php
                            $date = date_create(date("Y-m-d"));
                            date_modify($date, "+4 days");
                            echo date_format($date, "d");
                            ?>.
                            <br>
                            <?php
                            $date = date_create(date("Y-m-d"));
                            date_modify($date, "+4 days");
                            echo date_format($date, "D");
                            ?>.
                        </button>
                    </li>
                    <h4 class="mt-1 pe-none">|</h4>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link"
                            data-bs-toggle="tab" data-bs-target="#day6" type="button" role="tab" aria-controls="day6" aria-selected="false">
                            <?php
                            $date = date_create(date("Y-m-d"));
                            date_modify($date, "+5 days");
                            echo date_format($date, "d");
                            ?>.
                            <br>
                            <?php
                            $date = date_create(date("Y-m-d"));
                            date_modify($date, "+5 days");
                            echo date_format($date, "D");
                            ?>.
                        </button>
                    </li>
                    <h4 class="mt-1 pe-none">|</h4>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link"
                            data-bs-toggle="tab" data-bs-target="#day7" type="button" role="tab" aria-controls="day7" aria-selected="false">
                            <?php
                            $date = date_create(date("Y-m-d"));
                            date_modify($date, "+6 days");
                            echo date_format($date, "d");
                            ?>.
                            <br>
                            <?php
                            $date = date_create(date("Y-m-d"));
                            date_modify($date, "+6 days");
                            echo date_format($date, "D");
                            ?>.
                        </button>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="tab-content" id="npu-content">
            <div id="day1" class="tab-pane fade show active" role="tabpanel" aria-labelledby="day1-tab" tabindex="0">
                <h4>Today: </h4>
                <?php 
                $date = date_create(date("Y-m-d"));
                $chosenDate = date_format($date, "Y-m-d");

                $movieShowings = getMovieShowingsByDate($id, $chosenDate, $connection);

                // Sort the movie showings by showTime
                usort($movieShowings, function($a, $b) {
                    return $a['showTime'] <=> $b['showTime'];
                });

                foreach ($movieShowings as $movieShowing) {
                    echo '<button type="button" class="btn btn-secondary rounded-1 m-2">' . $movieShowing['showTime'] . '</button>';
                }
                ?>
            </div>
            <div id="day2" class="tab-pane fade" role="tabpanel" aria-labelledby="day2-tab" tabindex="0">
                <h4>Tomorrow: </h4>
                <?php 
                $date = date_create(date("Y-m-d"));
                date_modify($date, "+1 day");
                $chosenDate = date_format($date, "Y-m-d");

                $movieShowings = getMovieShowingsByDate($id, $chosenDate, $connection);

                // Sort the movie showings by showTime
                usort($movieShowings, function($a, $b) {
                    return $a['showTime'] <=> $b['showTime'];
                });

                foreach ($movieShowings as $movieShowing) {
                    echo '<button type="button" class="btn btn-secondary rounded-1 m-2">' . $movieShowing['showTime'] . '</button>';
                }
                ?>
            </div>
            <div id="day3" class="tab-pane fade" role="tabpanel" aria-labelledby="day3-tab" tabindex="0">
                <h4><?php
                    $date = date_create(date("Y-m-d"));
                    date_modify($date, "+2 days");
                    echo date_format($date, "l d. F");
                    ?></h4>
                <?php 
                $date = date_create(date("Y-m-d"));
                date_modify($date, "+2 days");
                $chosenDate = date_format($date, "Y-m-d");

                $movieShowings = getMovieShowingsByDate($id, $chosenDate, $connection);

                // Sort the movie showings by showTime
                usort($movieShowings, function($a, $b) {
                    return $a['showTime'] <=> $b['showTime'];
                });

                foreach ($movieShowings as $movieShowing) {
                    echo '<button type="button" class="btn btn-secondary rounded-1 m-2">' . $movieShowing['showTime'] . '</button>';
                }
                ?>
            </div>
            <div id="day4" class="tab-pane fade" role="tabpanel" aria-labelledby="day4-tab" tabindex="0">
                <h4><?php
                    $date = date_create(date("Y-m-d"));
                    date_modify($date, "+3 days");
                    echo date_format($date, "l d. F");
                    ?></h4>
                <?php 
                $date = date_create(date("Y-m-d"));
                date_modify($date, "+3 days");
                $chosenDate = date_format($date, "Y-m-d");

                $movieShowings = getMovieShowingsByDate($id, $chosenDate, $connection);

                // Sort the movie showings by showTime
                usort($movieShowings, function($a, $b) {
                    return $a['showTime'] <=> $b['showTime'];
                });

                foreach ($movieShowings as $movieShowing) {
                    echo '<button type="button" class="btn btn-secondary rounded-1 m-2">' . $movieShowing['showTime'] . '</button>';
                }
                ?>
            </div>
            <div id="day5" class="tab-pane fade" role="tabpanel" aria-labelledby="day5-tab" tabindex="0">
                <h4><?php
                    $date = date_create(date("Y-m-d"));
                    date_modify($date, "+4 days");
                    echo date_format($date, "l d. F");
                    ?></h4>
                <?php 
                $date = date_create(date("Y-m-d"));
                date_modify($date, "+4 days");
                $chosenDate = date_format($date, "Y-m-d");

                $movieShowings = getMovieShowingsByDate($id, $chosenDate, $connection);

                // Sort the movie showings by showTime
                usort($movieShowings, function($a, $b) {
                    return $a['showTime'] <=> $b['showTime'];
                });

                foreach ($movieShowings as $movieShowing) {
                    echo '<button type="button" class="btn btn-secondary rounded-1 m-2">' . $movieShowing['showTime'] . '</button>';
                }
                ?>
            </div>
            <div id="day6" class="tab-pane fade" role="tabpanel" aria-labelledby="day6-tab" tabindex="0">
                <h4><?php
                    $date = date_create(date("Y-m-d"));
                    date_modify($date, "+5 days");
                    echo date_format($date, "l d. F");
                    ?></h4>
                <?php 
                $date = date_create(date("Y-m-d"));
                date_modify($date, "+5 days");
                $chosenDate = date_format($date, "Y-m-d");

                $movieShowings = getMovieShowingsByDate($id, $chosenDate, $connection);

                // Sort the movie showings by showTime
                usort($movieShowings, function($a, $b) {
                    return $a['showTime'] <=> $b['showTime'];
                });

                foreach ($movieShowings as $movieShowing) {
                    echo '<button type="button" class="btn btn-secondary rounded-1 m-2">' . $movieShowing['showTime'] . '</button>';
                }
                ?>
            </div>
            <div id="day7" class="tab-pane fade" role="tabpanel" aria-labelledby="day7-tab" tabindex="0">
                <h4><?php
                    $date = date_create(date("Y-m-d"));
                    date_modify($date, "+6 days");
                    echo date_format($date, "l d. F");
                    ?></h4>
                <?php 
                $date = date_create(date("Y-m-d"));
                date_modify($date, "+6 days");
                $chosenDate = date_format($date, "Y-m-d");

                $movieShowings = getMovieShowingsByDate($id, $chosenDate, $connection);

                // Sort the movie showings by showTime
                usort($movieShowings, function($a, $b) {
                    return $a['showTime'] <=> $b['showTime'];
                });

                foreach ($movieShowings as $movieShowing) {
                    echo '<button type="button" class="btn btn-secondary rounded-1 m-2">' . $movieShowing['showTime'] . '</button>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include_once "./constants/footer.php";    ?>