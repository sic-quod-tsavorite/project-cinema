<div class="tab-content" id="npu-content">
    <div id="nowplaying" class="tab-pane fade show active" role="tabpanel" aria-labelledby="nowplaying-tab" tabindex="0">
        calander
        <div class="container d-flex flex-column">
            <?php
            try {
                $movieQuery = "SELECT * FROM movies WHERE now_upcoming = 1";
                $movieStmt = $connection->prepare($movieQuery);
                $movieStmt->execute();
                $movies = $movieStmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Database query failed: " . $e->getMessage());
            }

            foreach ($movies as $movie) {
                $hours = floor($movie['length'] / 60);
                $minutes = $movie['length'] % 60;
                $movieItem = '
                        <div class="item d-flex flex-row mb-5">
                            <div class="img me-5">
                                <img src="' . $movie['poster'] . '" alt="' . $movie['title'] . '">
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
                                                    <p>Genre: </p>
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
            try {
                $movieQuery = "SELECT * FROM movies WHERE now_upcoming = 2";
                $movieStmt = $connection->prepare($movieQuery);
                $movieStmt->execute();
                $movies = $movieStmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Database query failed: " . $e->getMessage());
            }

            foreach ($movies as $movie) {
                $hours = floor($movie['length'] / 60);
                $minutes = $movie['length'] % 60;
                $movieItem = '
                        <div class="item d-flex flex-row mb-5">
                            <div class="img me-5">
                                <img src="' . $movie['poster'] . '" alt="' . $movie['title'] . '">
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
                                                    <p>Genre: </p>
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
</div>