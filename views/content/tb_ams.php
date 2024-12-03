<?php
// Create Air Movie Showing
if (isset($_POST['submit']) && $_POST['submit'] == 'Add Air Movie Showing') {
    $cinemaHallID = trim($_POST['cinemaHallID']);
    $movieID = trim($_POST['movieID']);
    $showDate = trim($_POST['showDate']);
    $showTime = trim($_POST['showTime']);

    try {
        $query = "INSERT INTO airmovieshowings (cinemaHallID, movieID, showDate, showTime) VALUES (:cinemaHallID, :movieID, :showDate, :showTime)";
        $stmt = $connection->prepare($query);

        $stmt->bindParam(':cinemaHallID', $cinemaHallID);
        $stmt->bindParam(':movieID', $movieID);
        $stmt->bindParam(':showDate', $showDate);
        $stmt->bindParam(':showTime', $showTime);

        $result = $stmt->execute();

        if ($result) {
            $airMovieShowingMessage = "Air Movie Showing Added.";
        } else {
            $airMovieShowingMessage = "Air Movie Showing could not be created.";
        }
    } catch (PDOException $e) {
        $airMovieShowingMessage = "Air Movie Showing could not be created. Error: " . $e->getMessage();
    }

    if (!empty($airMovieShowingMessage)) {
        echo "<p>" . $airMovieShowingMessage . "</p>";
    }
}

try {
    $query = "SELECT am.*, c.name as cinemaHallName, m.title as movieTitle FROM airmovieshowings am JOIN cinemahalls c ON am.cinemaHallID = c.cinemaHallID JOIN movies m ON am.movieID = m.movieID";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $airMovieShowings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

// Edit Air Movie Showing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_air_movie_showing') {
    $amsID = $_POST['amsID'];
    $cinemaHallID = trim($_POST['cinemaHallID']);
    $movieID = trim($_POST['movieID']);
    $showDate = trim($_POST['showDate']);
    $showTime = trim($_POST['showTime']);

    try {
        $query = "UPDATE airmovieshowings SET cinemaHallID = :cinemaHallID, movieID = :movieID, showDate = :showDate, showTime = :showTime WHERE amsID = :amsID";
        $stmt = $connection->prepare($query);

        $stmt->bindParam(':amsID', $amsID);
        $stmt->bindParam(':cinemaHallID', $cinemaHallID);
        $stmt->bindParam(':movieID', $movieID);
        $stmt->bindParam(':showDate', $showDate);
        $stmt->bindParam(':showTime', $showTime);

        $result = $stmt->execute();

        if ($result) {
            header("Location: $base_url/admin-board?update=success");
            exit();
        } else {
            header("Location: $base_url/admin-board?update=failure");
            exit();
        }
    } catch (PDOException $e) {
        die("Database query failed: " . $e->getMessage());
    }
}

// Delete Air Movie Showing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete_air_movie_showing') {
    $amsIdToDelete = $_POST['amsID'];

    try {
        $deleteQuery = "DELETE FROM airmovieshowings WHERE amsID = :amsID";
        $stmt = $connection->prepare($deleteQuery);
        $stmt->bindParam(':amsID', $amsIdToDelete);
        $stmt->execute();

        header("Location: $base_url/admin-board");
        exit();
    } catch (PDOException $e) {
        $airMovieShowingMessage = "Error deleting air movie showing: " . $e->getMessage();
    }
}
?>

<div class="container bg-primary-subtle rounded-3 p-5 my-5">
    <h1>Air Movie Showings</h1>

    <!-- Create a New Air Movie Showing Form -->
    <form action="<?php echo $base_url; ?>/admin-board" method="POST" class="mb-4">
        <label for="cinemaHallID">Cinema Hall:</label>
        <select name="cinemaHallID" required>
            <?php
            $query = "SELECT * FROM cinemahalls";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $cinemaHalls = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($cinemaHalls as $hall) {
                echo "<option value='" . $hall['cinemaHallID'] . "'>" . $hall['name'] . "</option>";
            }
            ?>
        </select>

        <br>
        <label for="movieID">Movie:</label>
        <select name="movieID" required class="mt-1">
            <?php
            $query = "SELECT * FROM movies";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($movies as $movie) {
                echo "<option value='" . $movie['movieID'] . "'>" . $movie['title'] . "</option>";
            }
            ?>
        </select>
        <br>

        <label for="showDate">Show Date:</label>
        <input type="date" name="showDate" required class="my-1">

        <label for="showTime">Show Time:</label>
        <input type="time" name="showTime" required class="my-1">
        <br>

        <input type="submit" name="submit" value="Add Air Movie Showing" />
    </form>

    <!-- Display Existing Air Movie Showings -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cinema Hall</th>
                <th>Movie</th>
                <th>Show Date</th>
                <th>Show Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($airMovieShowings as $showing): ?>
                <tr>
                    <td><?php echo htmlspecialchars($showing['amsID']); ?></td>
                    <td><?php echo htmlspecialchars($showing['cinemaHallName']); ?></td>
                    <td><?php echo htmlspecialchars($showing['movieTitle']); ?></td>
                    <td><?php echo htmlspecialchars($showing['showDate']); ?></td>
                    <td><?php echo htmlspecialchars($showing['showTime']); ?></td>
                    <td>
                        <!-- Edit Form -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAirMovieShowing<?php echo $showing['amsID']; ?>">
                            Edit
                        </button>

                        <div class="modal fade" id="editAirMovieShowing<?php echo $showing['amsID']; ?>" tabindex="-1" aria-labelledby="editAirMovieShowing<?php echo $showing['amsID']; ?>Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editAirMovieShowing<?php echo $showing['amsID']; ?>Label">Edit Air Movie Showing</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <form action="<?php echo $base_url; ?>/admin-board" method="POST" style="display: inline;" class="modal-body">
                                        <input type="hidden" name="amsID" value="<?php echo htmlspecialchars($showing['amsID']); ?>">
                                        <input type="hidden" name="action" value="update_air_movie_showing">

                                        <div class="mb-3">
                                            <label for="cinemaHallID">Cinema Hall:</label>
                                            <select name="cinemaHallID" required>
                                                <?php
                                                $query = "SELECT * FROM cinemahalls";
                                                $stmt = $connection->prepare($query);
                                                $stmt->execute();
                                                $cinemaHalls = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($cinemaHalls as $hall) {
                                                    $selected = ($hall['cinemaHallID'] == $showing['cinemaHallID']) ? 'selected' : '';
                                                    echo "<option value='" . $hall['cinemaHallID'] . "'" . $selected . ">" . $hall['name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="movieID">Movie:</label>
                                            <select name="movieID" required>
                                                <?php
                                                $query = "SELECT * FROM movies";
                                                $stmt = $connection->prepare($query);
                                                $stmt->execute();
                                                $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($movies as $movie) {
                                                    $selected = ($movie['movieID'] == $showing['movieID']) ? 'selected' : '';
                                                    echo "<option value='" . $movie['movieID'] . "'" . $selected . ">" . $movie['title'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="showDate">Show Date:</label>
                                            <input type="date" class="form-control" id="showDate" name="showDate" value="<?php echo htmlspecialchars($showing['showDate']); ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="showTime">Show Time:</label>
                                            <input type="time" class="form-control" id="showTime" name="showTime" value="<?php echo htmlspecialchars($showing['showTime']); ?>" required>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close without saving</button>
                                            <button type="submit" class="btn btn-warning btn-sm">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Form -->
                        <form action="<?php echo $base_url; ?>/admin-board" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this air movie showing?');">
                            <input type="hidden" name="amsID" value="<?php echo htmlspecialchars($showing['amsID']); ?>">
                            <input type="hidden" name="action" value="delete_air_movie_showing">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>