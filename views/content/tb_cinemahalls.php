<?php
/* Create Cinema Hall */
if (isset($_POST['submit']) && $_POST['submit'] == 'Add Cinema Hall') {
    $name = trim($_POST['name']);

    try {
        $query = "INSERT INTO cinemahalls (name) VALUES (:name)";
        $stmt = $connection->prepare($query);

        // Sanitize input
        $washName = htmlspecialchars($name);

        $stmt->bindParam(':name', $washName);

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

    try {
        $query = "UPDATE cinemahalls SET name = :name WHERE cinemaHallID = :cinemaHallID";
        $stmt = $connection->prepare($query);

        // Sanitize input
        $washName = htmlspecialchars($name);

        $stmt->bindParam(':name', $washName);
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
        // Delete seats first
        $deleteSeatsQuery = "DELETE FROM hallseats WHERE cinemaHallID = :cinemaHallID";
        $stmtSeats = $connection->prepare($deleteSeatsQuery);
        $stmtSeats->bindParam(':cinemaHallID', $cinemaHallIdToDelete);
        $stmtSeats->execute();

        // Delete cinema hall
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

/* Create Seat */
if (isset($_POST['submit']) && $_POST['submit'] == 'Add Seats') {
    $cinemaHallID = $_POST['cinemaHallID'];
    $numRows = (int) $_POST['numRows'];
    $seatsPerRow = (int) $_POST['seatsPerRow'];

    try {
        // Get the highest row number in the hallseats table for the current cinema hall
        $query = "SELECT MAX(rowName) FROM hallseats WHERE cinemaHallID = :cinemaHallID";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':cinemaHallID', $cinemaHallID);
        $stmt->execute();
        $highestRow = $stmt->fetchColumn();

        // If no existing seats, set the starting row to 1
        if ($highestRow === null) {
            $startingRow = 1;
        } else {
            // Otherwise, increment the highest row by 1
            $startingRow = (int) $highestRow + 1;
        }

        for ($row = $startingRow; $row <= $numRows + $startingRow - 1; $row++) {
            for ($seat = 1; $seat <= $seatsPerRow; $seat++) {
                $query = "INSERT INTO hallseats (cinemaHallID, rowName, seatRowNumber) VALUES (:cinemaHallID, :rowName, :seatRowNumber)";
                $stmt = $connection->prepare($query);

                $stmt->bindParam(':cinemaHallID', $cinemaHallID);
                $stmt->bindParam(':rowName', $row);
                $stmt->bindParam(':seatRowNumber', $seat);

                $stmt->execute();
            }
        }

        $seatMessage = "Seats added successfully.";
    } catch (PDOException $e) {
        $seatMessage = "Error adding seats: " . $e->getMessage();
    }

    if (!empty($seatMessage)) {
        echo "<p>" . $seatMessage . "</p>";
    }
}

/* Delete All Seats */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'Delete All Seats') {
    $cinemaHallIdToDelete = $_POST['cinemaHallID'];

    try {
        $deleteQuery = "DELETE FROM hallseats WHERE cinemaHallID = :cinemaHallID";
        $stmt = $connection->prepare($deleteQuery);
        $stmt->bindParam(':cinemaHallID', $cinemaHallIdToDelete);
        $stmt->execute();

        header("Location: $base_url/admin-board");
        exit();
    } catch (PDOException $e) {
        die("Database query failed: " . $e->getMessage());
    }
}

try {
    $query = "SELECT * FROM hallseats";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $hallSeats = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

?>

<div class="container bg-primary-subtle rounded-3 p-5 my-5">
    <h1>Cinema Halls</h1>

    <!-- Create a New Cinema Hall Form -->
    <form action="<?php echo $base_url; ?>/admin-board" method="POST" class="mb-4">
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <input type="submit" name="submit" value="Add Cinema Hall" />
    </form>

    <!-- Display Existing Cinema Halls -->
    <table class="table table-striped">
        <thead class="bg-secondary text-white">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Total Seats</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cinemaHalls as $hall): ?>
                <tr>
                    <td><?php echo htmlspecialchars($hall['cinemaHallID']); ?></td>
                    <td><?php echo htmlspecialchars($hall['name']); ?></td>

                    <!-- Calculate total seats for each hall -->
                    <?php
                    $totalSeats = count(array_filter($hallSeats, function ($seat) use ($hall) {
                        return $seat['cinemaHallID'] === $hall['cinemaHallID'];
                    }));
                    ?>

                    <td<?php echo $totalSeats > 100 ? ' style="color: red;"' : ''; ?>><?php echo $totalSeats; ?></td>

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
                            <button type="submit" class="btn btn-danger btn-sm">Delete Hall</button>
                        </form>

                        <!-- Add Seats Form -->
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSeat<?php echo $hall['cinemaHallID']; ?>">
                            Add Seats
                        </button>

                        <div class="modal fade" id="addSeat<?php echo $hall['cinemaHallID']; ?>" tabindex="-1" aria-labelledby="addSeat<?php echo $hall['cinemaHallID']; ?>Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="addSeat<?php echo $hall['cinemaHallID']; ?>Label">Add Seats</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <form action="<?php echo $base_url; ?>/admin-board" method="POST" style="display: inline;" class="modal-body">
                                        <input type="hidden" name="cinemaHallID" value="<?php echo htmlspecialchars($hall['cinemaHallID']); ?>">

                                        <div class="mb-3">
                                            <label for="numRows">Number of Rows:</label>
                                            <input type="number" class="form-control" id="numRows" name="numRows" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="seatsPerRow">Seats per Row:</label>
                                            <input type="number" class="form-control" id="seatsPerRow" name="seatsPerRow" required>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close without saving</button>
                                            <input type="submit" name="submit" class="btn btn-primary btn-sm" value="Add Seats"></input>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Delete All Seats Form -->
                        <form action="<?php echo $base_url; ?>/admin-board" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete all seats connected to this cinema hall?');">
                            <input type="hidden" name="cinemaHallID" value="<?php echo htmlspecialchars($hall['cinemaHallID']); ?>">
                            <input type="hidden" name="action" value="Delete All Seats">
                            <button type="submit" class="btn btn-danger btn-sm">Delete All Seats</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>