<?php
require_once("./includes/functions/connection.php");

function displayMovies($nowUpcomingValue, $connection, $tagID = null) { 
    global $base_url;

    try {
        // Build the SQL query based on whether $tagID is provided
        if ($tagID) {
            $movieQuery = "SELECT m.* 
                           FROM movies m
                           INNER JOIN movietags mt ON m.movieID = mt.movieID
                           WHERE mt.tagID = :tagID AND m.now_upcoming = :nowUpcoming";
        } else {
            $movieQuery = "SELECT * FROM movies WHERE now_upcoming = :nowUpcoming";
        }

        $movieStmt = $connection->prepare($movieQuery);
        $movieStmt->bindParam(':nowUpcoming', $nowUpcomingValue, PDO::PARAM_INT);

        // Bind :tagID only if it's provided
        if ($tagID) {
            $movieStmt->bindParam(':tagID', $tagID, PDO::PARAM_INT);
        }

        $movieStmt->execute();
        $movies = $movieStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($movies as $movie) {
            $hours = floor($movie['length'] / 60);
            $minutes = $movie['length'] % 60;
            ?> 
            <div class="item d-flex flex-row mb-5">
                <a class="img me-5" href="<?php echo $base_url; ?>/movie/id/<?php echo $movie['movieID']; ?>">
                    <img src="<?php echo $base_url . '/' . $movie['poster']; ?>" alt="<?php echo $movie['title']; ?>">
                </a>
                <div class="film-content">
                    <a class="h3 link-underline link-underline-opacity-0" href="<?php echo $base_url; ?>/movie/id/<?php echo $movie['movieID']; ?>">
                        <?php echo $movie['title']; ?>
                    </a>
                    <h5><?php echo $hours; ?>h <?php echo $minutes; ?>min</h5>
                    <p>Director: <?php echo $movie['director']; ?></p>
                    <divider class="my-4">
                    </divider>
                    <p>Actor:
                        <ul>
                            <?php
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
                                    echo '<li>' . $mActor['first_name'] . ' ' . $mActor['last_name'] . ' - As: ' . $mActor['role'] . '</li>';
                                }
                            } catch (PDOException $e) {
                                die("Database query failed: " . $e->getMessage());
                            }
                            ?>
                        </ul>
                    </p>
                    <p>Genre:
                        <ul>
                            <?php
                            try { // Show Genres
                                $mtQuery = "SELECT t.name, t.tagID
                                              FROM tag t
                                              INNER JOIN movietags mt ON t.tagID = mt.tagID
                                              WHERE mt.movieID = :movieID";
                                $stmt = $connection->prepare($mtQuery);
                                $stmt->bindParam(':movieID', $movie['movieID']);
                                $stmt->execute();
                                $movieTags = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($movieTags as $mTag) {
                                    echo '<li><a class="link-offset-1 link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="' . $base_url . '/tag/id/' . $mTag['tagID'] . '">' . $mTag['name'] . '</a></li>';
                                }
                            } catch (PDOException $e) {
                                die("Database query failed: " . $e->getMessage());
                            }
                            ?>
                        </ul>
                    </p>
                </div>
            </div>
            <?php
        } 

    } catch (PDOException $e) {
        die("Database query failed: " . $e->getMessage());
    }
}
?>