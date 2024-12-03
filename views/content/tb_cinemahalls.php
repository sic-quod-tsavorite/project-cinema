<?php
/* Create Cinema Hall */
if (isset($_POST['submit']) && $_POST['submit'] == 'Add Cinema Hall') {
    $name = trim($_POST['name']);
    $seats = trim($_POST['seats']);

    try {
        $query = "INSERT INTO cinemahalls (name, seats) VALUES (:name, :seats)";
        $stmt = $connection->prepare($query);

        // Sanitize input
        $washName = htmlspecialchars($name);
        $washSeats = intval($seats);

        $stmt->bindParam(':name', $washName);
        $stmt->bindParam(':seats', $washSeats);

        $result = $stmt->execute();

        if ($result) {
            $cinemaHallMessage = "Cinema Hall Added.";
        } else {
            $cinemaHallMessage = "Cinema Hall could not be created.";
        }
    } catch (PDOException $e) {
        $cinemaHallMessage = "Cinema Hall could not be created. Error: " . $e->getMessage();
    }

    if (!empty($cinemaHallMessage)) {
        echo "<p>" . $cinemaHallMessage . "</p>";
    }
}

try {
    $query = "SELECT * FROM cinemahalls";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $cinemaHalls = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

/* Edit Cinema Hall */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_cinema_hall') {
    $cinemaHallID = $_POST['cinemaHallID'];
    $name = trim($_POST['name']);
    $seats = trim($_POST['seats']);

    try {
        $query = "UPDATE cinemahalls SET name = :name, seats = :seats WHERE cinemaHallID = :cinemaHallID";
        $stmt = $connection->prepare($query);

        // Sanitize input
        $washName = htmlspecialchars($name);
        $washSeats = intval($seats);

        $stmt->bindParam(':name', $washName);
        $stmt->bindParam(':seats', $washSeats);
        $stmt->bindParam(':cinemaHallID', $cinemaHallID);

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

// Delete cinema hall
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete_cinema_hall') {
    // Use ID to delete
    $cinemaHallIdToDelete = $_POST['cinemaHallID'];

    try {
        $deleteQuery = "DELETE FROM cinemahalls WHERE cinemaHallID = :cinemaHallID";
        $stmt = $connection->prepare($deleteQuery);
        $stmt->bindParam(':cinemaHallID', $cinemaHallIdToDelete);
        $stmt->execute();

        header("Location: $base_url/admin-board");
        exit();
    } catch (PDOException $e) {
        $cinemaHallMessage = "Error deleting cinema hall: " . $e->getMessage();
    }
}
?>

<div class="container bg-primary-subtle rounded-3 p-5 my-5">
    <h1>Cinema Halls</h1>

    <!-- Create a New Cinema Hall Form -->
    <form action="<?php echo $base_url; ?>/admin-board" method="POST" class="mb-4">
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <label for="seats">Seats:</label>
        <input type="number" name="seats" required>
        <input type="submit" name="submit" value="Add Cinema Hall" />
    </form>

    <!-- Display Existing Cinema Halls -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Seats</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cinemaHalls as $hall): ?>
                <tr>
                    <td><?php echo htmlspecialchars($hall['cinemaHallID']); ?></td>
                    <td><?php echo htmlspecialchars($hall['name']); ?></td>
                    <td><?php echo htmlspecialchars($hall['seats']); ?></td>
                    <td>
                        <!-- Edit Form -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editCinemaHall<?php echo $hall['cinemaHallID']; ?>">
                            Edit
                        </button>

                        <div class="modal fade" id="editCinemaHall<?php echo $hall['cinemaHallID']; ?>" tabindex="-1" aria-labelledby="editCinemaHall<?php echo $hall['cinemaHallID']; ?>Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editCinemaHall<?php echo $hall['cinemaHallID']; ?>Label">Edit Cinema Hall</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <form action="<?php echo $base_url; ?>/admin-board" method="POST" style="display: inline;" class="modal-body">
                                        <input type="hidden" name="cinemaHallID" value="<?php echo htmlspecialchars($hall['cinemaHallID']); ?>">
                                        <input type="hidden" name="action" value="update_cinema_hall">

                                        <div class="mb-3">
                                            <label for="name">Name:</label>
                                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($hall['name']); ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="seats">Seats:</label>
                                            <input type="number" class="form-control" id="seats" name="seats" value="<?php echo intval($hall['seats']); ?>" required>
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
                        <form action="<?php echo $base_url; ?>/admin-board" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this cinema hall?');">
                            <input type="hidden" name="cinemaHallID" value="<?php echo htmlspecialchars($hall['cinemaHallID']); ?>">
                            <input type="hidden" name="action" value="delete_cinema_hall">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>