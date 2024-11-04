<?php
// Dynamic page for Tags/Categories

// Require connection earlier to create page title
require_once("./includes/functions/connection.php");

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

    <div class="tab-content" id="npu-content">
        <div id="nowplaying" class="tab-pane fade show active" role="tabpanel" aria-labelledby="nowplaying-tab" tabindex="0">
            <div class="container d-flex flex-column">
                <?php
                // Fetch now playing movies with tag
                try {
                    $movieQuery = "SELECT m.* 
                                   FROM movies m
                                   INNER JOIN movietags mt ON m.movieID = mt.movieID
                                   WHERE mt.tagID = :tagID AND m.now_upcoming = 1";
                    $movieStmt = $connection->prepare($movieQuery);
                    $movieStmt->bindParam(':tagID', $id);
                    $movieStmt->execute();
                    $nowPlayingMovies = $movieStmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    die("Database query failed: " . $e->getMessage());
                }

                foreach ($nowPlayingMovies as $movie) {
                    $hours = floor($movie['length'] / 60);
                    $minutes = $movie['length'] % 60;
                    $movieItem = '
                            <div class="item d-flex flex-row mb-5">
                                <div class="img me-5">
                                    <img src="' . $base_url . '/' . $movie['poster'] . '" alt="' . $movie['title'] . '">
                                </div>
                                <div class="film-content">
                                    <h3>' . $movie['title'] . '</h3>
                                    <h5>' . $hours . 'h ' . $minutes . 'min</h5>
                                    <p>Director: ' . $movie['director'] . '</p>
                                    <p>Actor: 
                                        <ul>';
                    try { // Show 4 Actors
                        $maQuery = "SELECT a.first_name, a.last_name, ar.role 
                                                    FROM actors a
                                                    INNER JOIN actorrole ar ON a.actorID = ar.actorID
                                                    WHERE ar.movieID = :movieID
                                                    LIMIT 4";
                        $stmt = $connection->prepare($maQuery);
                        $stmt->bindParam(':movieID', $movie['movieID']);
                        $stmt->execute();
                        $movieActors = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($movieActors as $mActor) {
                            $movieItem .= '<li>' . $mActor['first_name'] . ' ' . $mActor['last_name'] . ' - As: ' . $mActor['role'] . '</li>';
                        }
                    } catch (PDOException $e) {
                        die("Database query failed: " . $e->getMessage());
                    }

                    $movieItem .= '      </ul>
                                    </p>
                                    <p>Genre: 
                                        <ul>';

                    try { // Show Genres
                        $mtQuery = "SELECT t.name 
                                        FROM tag t
                                        INNER JOIN movietags mt ON t.tagID = mt.tagID
                                        WHERE mt.movieID = :movieID";
                        $stmt = $connection->prepare($mtQuery);
                        $stmt->bindParam(':movieID', $movie['movieID']);
                        $stmt->execute();
                        $movieTags = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($movieTags as $mTag) {
                            $movieItem .= '<li>' . $mTag['name'] . '</li>';
                        }
                    } catch (PDOException $e) {
                        die("Database query failed: " . $e->getMessage());
                    }

                    $movieItem .= '      </ul> </p>
                                    <divider class="my-4">
                                    </divider>
                                    <p>Airings</p>
                                    timetable
                                </div>
                            </div>';

                    echo $movieItem;
                };
            ?>
            </div>
        </div>
        
        <div id="upcoming" class="tab-pane fade" role="tabpanel" aria-labelledby="upcoming-tab" tabindex="0">
            <div class="container d-flex flex-column">
                <?php
                // Fetch upcoming movies with tag
                try {
                    $movieQuery = "SELECT m.* 
                                   FROM movies m
                                   INNER JOIN movietags mt ON m.movieID = mt.movieID
                                   WHERE mt.tagID = :tagID AND m.now_upcoming = 2";
                    $movieStmt = $connection->prepare($movieQuery);
                    $movieStmt->bindParam(':tagID', $id);
                    $movieStmt->execute();
                    $upcomingMovies = $movieStmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    die("Database query failed: " . $e->getMessage());
                }

                foreach ($upcomingMovies as $movie) {
                    
                    $hours = floor($movie['length'] / 60);
                    $minutes = $movie['length'] % 60;
                    $movieItem = '
                            <div class="item d-flex flex-row mb-5">
                                <div class="img me-5">
                                    <img src="' . $base_url . '/' . $movie['poster'] . '" alt="' . $movie['title'] . '">
                                </div>
                                <div class="film-content">
                                    <h3>' . $movie['title'] . '</h3>
                                    <h5>' . $hours . 'h ' . $minutes . 'min</h5>
                                    <p>Director: ' . $movie['director'] . '</p>
                                    <p>Actor: 
                                        <ul>';
                    try { // Show 4 Actors
                        $maQuery = "SELECT a.first_name, a.last_name, ar.role 
                                                    FROM actors a
                                                    INNER JOIN actorrole ar ON a.actorID = ar.actorID
                                                    WHERE ar.movieID = :movieID
                                                    LIMIT 4";
                        $stmt = $connection->prepare($maQuery);
                        $stmt->bindParam(':movieID', $movie['movieID']);
                        $stmt->execute();
                        $movieActors = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($movieActors as $mActor) {
                            $movieItem .= '<li>' . $mActor['first_name'] . ' ' . $mActor['last_name'] . ' - As: ' . $mActor['role'] . '</li>';
                        }
                    } catch (PDOException $e) {
                        die("Database query failed: " . $e->getMessage());
                    }

                    $movieItem .= '      </ul>
                                    </p>
                                    <p>Genre: 
                                        <ul>';

                    try { // Show Genres
                        $mtQuery = "SELECT t.name 
                                        FROM tag t
                                        INNER JOIN movietags mt ON t.tagID = mt.tagID
                                        WHERE mt.movieID = :movieID";
                        $stmt = $connection->prepare($mtQuery);
                        $stmt->bindParam(':movieID', $movie['movieID']);
                        $stmt->execute();
                        $movieTags = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($movieTags as $mTag) {
                            $movieItem .= '<li>' . $mTag['name'] . '</li>';
                        }
                    } catch (PDOException $e) {
                        die("Database query failed: " . $e->getMessage());
                    }

                    $movieItem .= '      </ul> </p>
                                    <divider class="my-4">
                                    </divider>
                                    <p>Airings</p>
                                    timetable
                                </div>
                            </div>';

                    echo $movieItem;
                }
                ?>
            </div>
        </div>
    </div>
</div>


<?php include_once "./constants/footer.php";    ?>