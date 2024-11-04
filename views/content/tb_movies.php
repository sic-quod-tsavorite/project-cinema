<?php

if (isset($_POST['submit']) && $_POST['submit'] == 'Create_movie') {
    $title = trim($_POST['title']);
    $length = trim($_POST['length']);
    $description = trim($_POST['description']);
    $released = trim($_POST['released']);
    $director = trim($_POST['director']);
    $isNews = isset($_POST['isNews']) ? 1 : 0;
    $now_upcoming = trim($_POST['now_upcoming']);

    // Movie Poster image upload
    $poster = '';
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "includes/assets/uploads/posters/";
        $poster = $targetDir . basename($_FILES["poster"]["name"]);
        move_uploaded_file($_FILES["poster"]["tmp_name"], $poster);
    }

    // Movie hero image upload
    $heroimg = '';
    if (isset($_FILES['heroimg']) && $_FILES['heroimg']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "includes/assets/uploads/heroimgs/";
        $heroimg = $targetDir . basename($_FILES["heroimg"]["name"]);
        move_uploaded_file($_FILES["heroimg"]["tmp_name"], $heroimg);
    }

    // Movie trailer video upload
    $trailer = '';
    if (isset($_FILES['trailer']) && $_FILES['trailer']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "includes/assets/uploads/trailers/";
        $trailer = $targetDir . basename($_FILES["trailer"]["name"]);
        move_uploaded_file($_FILES["trailer"]["tmp_name"], $trailer);
    }

    // Create Movie to database
    try {
        $query = "INSERT INTO movies (title, length, description, poster, heroimg, trailer, released, director, isNews, now_upcoming) 
                  VALUES (:title, :length, :description, :poster, :heroimg, :trailer, :released, :director, :isNews, :now_upcoming)";
        $stmt = $connection->prepare($query);

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':length', $length);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':poster', $poster);
        $stmt->bindParam(':heroimg', $heroimg);
        $stmt->bindParam(':trailer', $trailer);
        $stmt->bindParam(':released', $released);
        $stmt->bindParam(':director', $director);
        $stmt->bindParam(':isNews', $isNews);
        $stmt->bindParam(':now_upcoming', $now_upcoming);

        /* exit if there would be more than 4 movies marked as news with this created movie.
        if (isset($_POST['isNews']) && $_POST['isNews'] == '1') {
            $checkNewsQuery = "SELECT COUNT(*) FROM movies WHERE isNews = 1";
            $checkNewsStmt = $connection->query($checkNewsQuery);
            $newsCount = $checkNewsStmt->fetchColumn();

            if ($newsCount >= 4) {
                echo "<p>Error: You can only have a maximum of 4 movies marked as news.</p>";
                exit();
            }
        }
        */

        $isNews = isset($_POST['isNews']) ? 1 : 0;

        $result = $stmt->execute();

        if ($result) {
            $movieMessage = "Movie Added.";
            $insertedMovieId = $connection->lastInsertId(); // Get the ID of the newly inserted movie

            // Handle movie tags
            if (isset($_POST['tags']) && is_array($_POST['tags'])) {
                $tagIds = $_POST['tags'];

                foreach ($tagIds as $tagId) {
                    try {
                        $insertTagQuery = "INSERT INTO movietags (movieID, tagID) VALUES (:movieID, :tagID)";
                        $insertTagStmt = $connection->prepare($insertTagQuery);
                        $insertTagStmt->bindParam(':movieID', $insertedMovieId);
                        $insertTagStmt->bindParam(':tagID', $tagId);
                        $insertTagStmt->execute();
                    } catch (PDOException $e) {
                        error_log("Error inserting tag: " . $e->getMessage());
                    }
                }
                // Handle actor roles
                if (isset($_POST['actors']) && is_array($_POST['actors'])) {
                    $actorIds = $_POST['actors'];
                    $roles = $_POST['roles'];

                    for ($i = 0; $i < count($actorIds); $i++) {
                        try {
                            $insertActorRoleQuery = "INSERT INTO actorrole (movieID, actorID, role) VALUES (:movieID, :actorID, :role)";
                            $insertActorRoleStmt = $connection->prepare($insertActorRoleQuery);
                            $insertActorRoleStmt->bindParam(':movieID', $insertedMovieId);
                            $insertActorRoleStmt->bindParam(':actorID', $actorIds[$i]);
                            $insertActorRoleStmt->bindParam(':role', $roles[$i]);
                            $insertActorRoleStmt->execute();
                        } catch (PDOException $e) {
                            error_log("Error inserting actor role: " . $e->getMessage());
                        }
                    }
                }
            } else {
                $movieMessage = "Movie could not be created.";
            }
        }
    } catch (PDOException $e) {
        $movieMessage = "Movie could not be created. Error: " . $e->getMessage();
    }
}

if (!empty($movieMessage)) {
    echo "<p>" . $movieMessage . "</p>";
}

try {
    $query = "SELECT * FROM movies";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

// Delete movie
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete_movie') {
    $movieIdToDelete = $_POST['movie_id'];

    try {
        // Delete related data from actorrole
        $deleteActorRoleQuery = "DELETE FROM actorrole WHERE movieID = :movieId";
        $stmt = $connection->prepare($deleteActorRoleQuery);
        $stmt->bindParam(':movieId', $movieIdToDelete);
        $stmt->execute();

        // Delete related data from movietags
        $deleteMovieTagsQuery = "DELETE FROM movietags WHERE movieID = :movieId";
        $stmt = $connection->prepare($deleteMovieTagsQuery);
        $stmt->bindParam(':movieId', $movieIdToDelete);
        $stmt->execute();

        // Fetch movie data to get file paths
        $fetchQuery = "SELECT poster, heroimg, trailer FROM movies WHERE movieID = :movieId";
        $fetchStmt = $connection->prepare($fetchQuery);
        $fetchStmt->bindParam(':movieId', $movieIdToDelete);
        $fetchStmt->execute();
        $movie = $fetchStmt->fetch(PDO::FETCH_ASSOC);

        // Delete files from directory
        if ($movie) {
            if (file_exists($movie['poster'])) {
                unlink($movie['poster']);
            }
            if (file_exists($movie['heroimg'])) {
                unlink($movie['heroimg']);
            }
            if (file_exists($movie['trailer'])) {
                unlink($movie['trailer']);
            }
        }

        // Delete movie from database
        $deleteQuery = "DELETE FROM movies WHERE movieID = :movieId";
        $stmt = $connection->prepare($deleteQuery);
        $stmt->bindParam(':movieId', $movieIdToDelete);
        $stmt->execute();

        header("Location: /project-cinema/admin-board");
        exit();
    } catch (PDOException $e) {
        $movieMessage = "Error deleting movie: " . $e->getMessage();
    }
}

// Edit/Update movie
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_movie') {
    $movieIdToUpdate = $_POST['movie_id'];
    $newTitle = trim($_POST['title']);
    $newLength = trim($_POST['length']);
    $newDescription = trim($_POST['description']);
    $newReleased = trim($_POST['released']);
    $newDirector = trim($_POST['director']);
    $newIsNews = isset($_POST['isNews']) ? 1 : 0;
    $newNowUpcoming = trim($_POST['now_upcoming']);

    // Uploads for poster, heroimg, and trailer
    $posterPath = handleFileUpload('poster', 'posters', $movieIdToUpdate);
    $heroimgPath = handleFileUpload('heroimg', 'heroimgs', $movieIdToUpdate);
    $trailerPath = handleFileUpload('trailer', 'trailers', $movieIdToUpdate);

    // Update movie in database
    try {
        $updateQuery = "UPDATE movies SET 
                          title = :title, 
                          length = :length, 
                          description = :description, 
                          poster = :poster, 
                          heroimg = :heroimg, 
                          trailer = :trailer, 
                          released = :released, 
                          director = :director,
                          isNews = :isNews,
                          now_upcoming = :now_upcoming
                          WHERE movieID = :movieId";

        $stmt = $connection->prepare($updateQuery);
        $stmt->bindParam(':title', $newTitle);
        $stmt->bindParam(':length', $newLength);
        $stmt->bindParam(':description', $newDescription);
        $stmt->bindParam(':poster', $posterPath);
        $stmt->bindParam(':heroimg', $heroimgPath);
        $stmt->bindParam(':trailer', $trailerPath);
        $stmt->bindParam(':released', $newReleased);
        $stmt->bindParam(':director', $newDirector);
        $stmt->bindParam(':isNews', $newIsNews);
        $stmt->bindParam(':now_upcoming', $newNowUpcoming);
        $stmt->bindParam(':movieId', $movieIdToUpdate);

        $stmt->execute();

        if ($stmt->execute()) {
            // Handle movie tags (update)
            $deleteExistingTagsQuery = "DELETE FROM movietags WHERE movieID = :movieID";
            $deleteExistingTagsStmt = $connection->prepare($deleteExistingTagsQuery);
            $deleteExistingTagsStmt->bindParam(':movieID', $movieIdToUpdate);
            $deleteExistingTagsStmt->execute();

            if (isset($_POST['tags']) && is_array($_POST['tags'])) {
                $tagIds = $_POST['tags'];

                foreach ($tagIds as $tagId) {
                    try {
                        $insertTagQuery = "INSERT INTO movietags (movieID, tagID) VALUES (:movieID, :tagID)";
                        $insertTagStmt = $connection->prepare($insertTagQuery);
                        $insertTagStmt->bindParam(':movieID', $movieIdToUpdate);
                        $insertTagStmt->bindParam(':tagID', $tagId);
                        $insertTagStmt->execute();
                    } catch (PDOException $e) {
                        error_log("Error inserting tag: " . $e->getMessage());
                    }
                }
            }
        }
    } catch (PDOException $e) {
        error_log("Error inserting tag: " . $e->getMessage());
    }
    // Handle actor roles (update)
    try {
        // Delete ALL existing actor roles for this movie
        $deleteAllActorRolesQuery = "DELETE FROM actorrole WHERE movieID = :movieID";
        $deleteAllActorRolesStmt = $connection->prepare($deleteAllActorRolesQuery);
        $deleteAllActorRolesStmt->bindParam(':movieID', $movieIdToUpdate);
        $deleteAllActorRolesStmt->execute();

        // Re-insert actor roles based on the submitted form data
        if (isset($_POST['actors']) && is_array($_POST['actors'])) {
            $actorIds = $_POST['actors'];
            $roles = $_POST['roles'];
            $removeActor = $_POST['removeActor'] ?? [];

            for ($i = 0; $i < count($actorIds); $i++) {
                // Only insert if actorId is not empty AND the "remove" checkbox is NOT checked
                if (!empty($actorIds[$i]) && !isset($removeActor[$actorIds[$i]])) {
                    $insertActorRoleQuery = "INSERT INTO actorrole (movieID, actorID, role) 
                                          VALUES (:movieID, :actorID, :role)";
                    $insertActorRoleStmt = $connection->prepare($insertActorRoleQuery);
                    $insertActorRoleStmt->bindParam(':movieID', $movieIdToUpdate);
                    $insertActorRoleStmt->bindParam(':actorID', $actorIds[$i]);
                    $insertActorRoleStmt->bindParam(':role', $roles[$i]);
                    $insertActorRoleStmt->execute();
                }
            }
        }
    } catch (PDOException $e) {
        $movieMessage = "Error updating movie: " . $e->getMessage();
    }
    header("Location: /project-cinema/admin-board");
    exit();
}

// Function for file uploads
function handleFileUpload($fieldName, $subfolder, $movieId)
{
    // Check if a file was uploaded for this field
    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
        $targetDir = "includes/assets/uploads/$subfolder/";
        $newFileName = $movieId . '_' . basename($_FILES[$fieldName]["name"]); // Unique filename
        $targetFile = $targetDir . $newFileName;

        // Move uploaded file to directory
        if (move_uploaded_file($_FILES[$fieldName]["tmp_name"], $targetFile)) {
            return $targetFile; // Return new file path
        } else {
            // if upload error
            echo "Sorry, there was an error uploading your file.";
            return ''; // Return empty string on error
        }
    } else {
        // if no file uploaded keep the existing path
        return $_POST[$fieldName];
    }
}
?>

<div class="container">
    <h1>Movies:</h1>
    <!-- Create movie form -->
    <form action="/project-cinema/admin-board" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title"><br>

        <label for="length">Length (minutes):</label>
        <input type="number" name="length"><br>

        <label for="description">Description:</label>
        <textarea name="description"></textarea><br>

        <label for="poster">Poster:</label>
        <input type="file" name="poster"><br>

        <label for="heroimg">Hero Image:</label>
        <input type="file" name="heroimg"><br>

        <label for="trailer">Trailer:</label>
        <input type="file" name="trailer"><br>

        <label for="released">Released Date:</label>
        <input type="date" name="released"><br>

        <label for="director">Director:</label>
        <input type="text" name="director"><br>

        <label for="actors">Actors:</label>
        <div id="actorsContainer">
            <div class="actor-input-group mb-2">
                <select name="actors[]" class="form-select actor-select">
                    <option value="">Select Actor</option>
                    <?php
                    try {
                        $actorsQuery = "SELECT * FROM actors ORDER BY first_name, last_name";
                        $actorsStmt = $connection->prepare($actorsQuery);
                        $actorsStmt->execute();
                        $allActors = $actorsStmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($allActors as $actor) {
                            echo '<option value="' . $actor['actorID'] . '">' . $actor['first_name'] . ' ' . $actor['last_name'] . '</option>';
                        }
                    } catch (PDOException $e) {
                        echo "Error fetching actors: " . $e->getMessage();
                    }
                    ?>
                </select>
                <input type="text" name="roles[]" class="form-control" placeholder="Role">
                <button type="button" class="btn btn-danger remove-actor-button" style="display: none;">Remove</button>
            </div>
        </div>
        <button type="button" class="btn btn-primary" id="addActorButton">Add Actor</button>

        <br><label for="isNews">Is News:</label>
        <input type="checkbox" name="isNews" value="1"> <br>

        <label for="now_upcoming">Now or Upcoming:</label>
        <select name="now_upcoming">
            <option value="1">Now</option>
            <option value="2">Upcoming</option>
        </select><br>
        <label for="tags">Tags:</label>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                Select Tags
            </button>
            <ul class="dropdown-menu" id="tagDropdown">
                <?php
                try {
                    $tagsQuery = "SELECT * FROM tag ORDER BY tagType, name";
                    $tagsStmt = $connection->prepare($tagsQuery);
                    $tagsStmt->execute();
                    $allTags = $tagsStmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($allTags as $tag) {
                        echo '<li class="form-check form-switch">';
                        echo '<input class="form-check-input dropdown-item tag-checkbox" type="checkbox" role="switch" id="movieTagSelect' . $tag['tagID'] . '" name="tags[]" value="' . $tag['tagID'] . '">';
                        echo '<label class="form-check-label" for="movieTagSelect' . $tag['tagID'] . '">' . $tag['name'] . '</label>';
                        echo '</li>';
                    }
                } catch (PDOException $e) {
                    echo "Error fetching tags: " . $e->getMessage();
                }
                ?>
            </ul>
        </div>

        <input type="submit" name="submit" value="Create_movie">
    </form>
    <br>
    <!-- Display movies -->
    <?php
    $checkNewsDisplayQuery = "SELECT COUNT(*) FROM movies WHERE isNews = 1";
    $checkNewsDisplayStmt = $connection->query($checkNewsDisplayQuery);
    $newsCountDisplay = $checkNewsDisplayStmt->fetchColumn();

    if ($newsCountDisplay == 4) {
        echo '<p>Currently ' . $newsCountDisplay . ' movies are marked as news.</p>';
    } elseif ($newsCountDisplay < 4) {
        echo '<p class="h3 bg-danger">(!) Currently there is only ' . $newsCountDisplay . ' movies that are marked as news.</p>';
    } else {
        echo '<p class="h3 bg-danger"><b>(!) Warning</b> <i>more than</i> 4 <i>movies are marked as news. Currently</i> <b>' . $newsCountDisplay . '</b> <i>movies are marked as news.</i></p>';
    }
    ?>
    <ul>
        <?php foreach ($movies as $movie): ?>
            <li>
                <?php
                echo '<p><b>' . $movie['title'] . '</b></p>';
                $hours = floor($movie['length'] / 60);
                $minutes = $movie['length'] % 60;
                ?>
                <p>
                    Length:
                    <b data-bs-toggle="tooltip" data-bs-title="<?php echo $movie['length'] . ' minutes' ?>">
                        <?php echo $hours . 'h ' . $minutes . 'min'; ?>
                    </b>
                </p>
                <?php echo '<p>Description: <i>' . $movie['description'] . '</i></p>'; ?>
                <?php echo '<p>Released on: <b>' . $movie['released'] . '</b></p>'; ?>
                <?php echo '<p>Director(s): <b>' . $movie['director'] . '</b></p>'; ?>

                <?php
                // Fetch actors and roles for the current movie
                try {
                    $actorsQuery = "SELECT a.actorID, a.first_name, a.last_name, ar.role 
                               FROM actors a
                               INNER JOIN actorrole ar ON a.actorID = ar.actorID
                               WHERE ar.movieID = :movieID";
                    $actorsStmt = $connection->prepare($actorsQuery);
                    $actorsStmt->bindParam(':movieID', $movie['movieID']);
                    $actorsStmt->execute();
                    $movieActors = $actorsStmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    echo "Error fetching actors for movie: " . $e->getMessage();
                }
                ?>

                <p>Actors:</p>
                <ul>
                    <?php foreach ($movieActors as $actor): ?>
                        <li><?php echo $actor['first_name'] . ' ' . $actor['last_name'] . ' - ' . $actor['role']; ?></li>
                    <?php endforeach; ?>
                </ul>
                <br>
                <?php
                if ($movie['isNews'] == 1) {
                    echo '<p>This movie is in the news section.</p>';
                } else {
                    echo '<p>This movie is not in the news section.</p>';
                }; ?>
                <?php
                $now_upcomingName = "";
                switch ($movie['now_upcoming']) {
                    case 1:
                        $now_upcomingName = "Now playing.";
                        break;
                    case 2:
                        $now_upcomingName = "Upcoming.";
                        break;
                    default:
                        $now_upcomingName = "Unknown";
                }
                echo '<p>This movie is ' . $now_upcomingName . '</p>';
                ?>
                <?php
                $movieTagsQuery = "SELECT t.name 
                FROM tag t
                INNER JOIN movietags mt ON t.tagID = mt.tagID
                WHERE mt.movieID = :movieID";
                $movieTagsStmt = $connection->prepare($movieTagsQuery);
                $movieTagsStmt->bindParam(':movieID', $movie['movieID']);
                $movieTagsStmt->execute();
                $movieTags = $movieTagsStmt->fetchAll(PDO::FETCH_COLUMN);

                echo '<p>Tags: ';
                if (!empty($movieTags)) {
                    echo implode(', ', $movieTags);
                } else {
                    echo 'No tags assigned';
                }
                echo '</p>';
                ?>

                <img src="<?php echo $movie['poster']; ?>" alt="<?php echo $movie['title'] . ' Poster'; ?>" height="100">
                <img src="<?php echo $movie['heroimg']; ?>" alt="<?php echo $movie['title'] . ' Heroimg'; ?>" height="100">
                <video height="100" controls>
                    <source src="<?php echo $movie['trailer']; ?>" type="video/mp4">
                </video>

                <span class="badge text-bg-secondary">ID: <?php echo $movie['movieID']; ?></span>

                <form action="/project-cinema/admin-board" method="POST" style="display: inline;">
                    <input type="hidden" name="movie_id" value="<?php echo $movie['movieID']; ?>">
                    <input type="hidden" name="action" value="delete_movie">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".editMovie<?php echo $movie['movieID']; ?>">
                    Edit
                </button>
                <!-- Edit movie popup -->
                <div class="modal fade editMovie<?php echo $movie['movieID']; ?>" id="editMovieModal" tabindex="-1" aria-labelledby="editMovie<?php echo $movie['movieID']; ?>Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="editMovie<?php echo $movie['movieID']; ?>Label">Edit Movie</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="/project-cinema/admin-board" method="POST" style="display: inline;" class="modal-body" enctype="multipart/form-data">
                                    <input type="hidden" name="movie_id" value="<?php echo $movie['movieID']; ?>">
                                    <input type="hidden" name="action" value="update_movie">

                                    <input type="text" name="title" value="<?php echo $movie['title']; ?>"><br>
                                    <input type="number" name="length" value="<?php echo $movie['length']; ?>"><br>
                                    <textarea name="description"><?php echo $movie['description']; ?></textarea><br>
                                    <input type="date" name="released" value="<?php echo $movie['released']; ?>"><br>
                                    <input type="text" name="director" value="<?php echo $movie['director']; ?>"><br>

                                    <label for="actors">Actors:</label>
                                    <div id="editActorsContainer<?php echo $movie['movieID']; ?>">
                                        <?php
                                        // Fetch existing actors and roles for this movie
                                        try {
                                            $actorsQuery = "SELECT a.actorID, a.first_name, a.last_name, ar.role 
                                           FROM actors a
                                           INNER JOIN actorrole ar ON a.actorID = ar.actorID
                                           WHERE ar.movieID = :movieID";
                                            $actorsStmt = $connection->prepare($actorsQuery);
                                            $actorsStmt->bindParam(':movieID', $movie['movieID']);
                                            $actorsStmt->execute();
                                            $existingActors = $actorsStmt->fetchAll(PDO::FETCH_ASSOC);
                                        } catch (PDOException $e) {
                                            echo "Error fetching actors for movie: " . $e->getMessage();
                                        }

                                        // Display existing actors and roles
                                        foreach ($existingActors as $index => $actor):
                                        ?>
                                            <div class="actor-input-group mb-2" data-actor-id="<?php echo $actor['actorID']; ?>">
                                                <select name="actors[]" class="form-select actor-select">
                                                    <option value="">Select Actor</option>
                                                    <?php
                                                    foreach ($allActors as $allActor) {
                                                        $selected = ($allActor['actorID'] == $actor['actorID']) ? 'selected' : '';
                                                        echo '<option value="' . $allActor['actorID'] . '" ' . $selected . '>' . $allActor['first_name'] . ' ' . $allActor['last_name'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <input type="text" name="roles[]" class="form-control" placeholder="Role" value="<?php echo $actor['role']; ?>">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input remove-actor-checkbox" type="checkbox" role="switch" id="removeActor<?php echo $actor['actorID']; ?>" name="removeActor[<?php echo $actor['actorID']; ?>]" <?php if (isset($_POST['removeActor'][$actor['actorID']])) echo 'checked'; ?>>
                                                    <label class="form-check-label" for="removeActor<?php echo $actor['actorID']; ?>">Remove</label>
                                                </div>
                                                <button type="button" class="btn btn-danger remove-actor-button" style="display: none;">Remove</button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <button type="button" class="btn btn-primary" onclick="addActorToEdit('editActorsContainer<?php echo $movie['movieID']; ?>')">Add Actor</button>
                                    <br>
                                    <label for="isNews">Is News:</label>
                                    <input type="checkbox" name="isNews" <?php if ($movie['isNews'] == 1) echo 'checked'; ?>><br>

                                    <label for="now_upcoming">Now or Upcoming</label>
                                    <select name="now_upcoming">
                                        <option value="1" <?php if ($movie['now_upcoming'] == 1) echo 'selected'; ?>>
                                            Now
                                        </option>
                                        <option value="2" <?php if ($movie['now_upcoming'] == 2) echo 'selected'; ?>>
                                            Upcoming
                                        </option>
                                    </select><br>

                                    <label for="tags">Tags:</label>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                            Select Tags
                                        </button>
                                        <ul class="dropdown-menu" id="tagDropdown">
                                            <?php
                                            try {
                                                $tagsQuery = "SELECT * FROM tag";
                                                $tagsStmt = $connection->prepare($tagsQuery);
                                                $tagsStmt->execute();
                                                $allTags = $tagsStmt->fetchAll(PDO::FETCH_ASSOC);

                                                // Fetch tags for the current movie
                                                $movieTagsQuery = "SELECT tagID FROM movietags WHERE movieID = :movieID";
                                                $movieTagsStmt = $connection->prepare($movieTagsQuery);
                                                $movieTagsStmt->bindParam(':movieID', $movie['movieID']);
                                                $movieTagsStmt->execute();
                                                $selectedTagIds = $movieTagsStmt->fetchAll(PDO::FETCH_COLUMN);

                                                foreach ($allTags as $tag) {
                                                    // Check if the tag is associated with the movie
                                                    $isChecked = in_array($tag['tagID'], $selectedTagIds) ? 'checked' : '';

                                                    echo '<li class="form-check form-switch">';
                                                    echo '<input class="form-check-input dropdown-item tag-checkbox" type="checkbox" role="switch" id="movieTagSelect' . $tag['tagID'] . '" name="tags[]" value="' . $tag['tagID'] . '" ' . $isChecked . '>';
                                                    echo '<label class="form-check-label" for="movieTagSelect' . $tag['tagID'] . '">' . $tag['name'] . '</label>';
                                                    echo '</li>';
                                                }
                                            } catch (PDOException $e) {
                                                echo "Error fetching tags: " . $e->getMessage();
                                            }
                                            ?>
                                        </ul>
                                    </div>

                                    <!-- Poster select new and preview -->
                                    <div class="mb-3">
                                        <label for="poster" class="form-label">Poster:</label>
                                        <img id="posterPreview<?php echo $movie['movieID']; ?>" src="<?php echo $movie['poster']; ?>" alt="<?php echo $movie['title'] . ' Poster'; ?>" height="100">
                                        <input type="hidden" name="poster" value="<?php echo $movie['poster']; ?>">
                                        <input class="form-control" type="file" name="poster" onchange="previewFile(this, 'posterPreview<?php echo $movie['movieID']; ?>')">
                                    </div>

                                    <!-- Heroimg select new and preview -->
                                    <div class="mb-3">
                                        <label for="heroimg" class="form-label">Hero Image:</label>
                                        <img id="heroimgPreview<?php echo $movie['movieID']; ?>" src="<?php echo $movie['heroimg']; ?>" alt="<?php echo $movie['title'] . ' Heroimg'; ?>" height="100">
                                        <input type="hidden" name="heroimg" value="<?php echo $movie['heroimg']; ?>">
                                        <input class="form-control" type="file" name="heroimg" onchange="previewFile(this, 'heroimgPreview<?php echo $movie['movieID']; ?>')">
                                    </div>

                                    <!-- Trailer select new and preview -->
                                    <div class="mb-3">
                                        <label for="trailer" class="form-label">Trailer:</label>
                                        <video id="trailerPreview<?php echo $movie['movieID']; ?>" height="100" controls>
                                            <source src="<?php echo $movie['trailer']; ?>" type="video/mp4">
                                        </video>
                                        <input type="hidden" name="trailer" value="<?php echo $movie['trailer']; ?>">
                                        <input class="form-control" type="file" name="trailer">
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close without saving</button>
                                        <button type="submit" class="btn btn-warning btn-sm">Update</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </li>
        <?php endforeach; ?>
    </ul>
</div>