<?php
// Create actor
if (isset($_POST['submit']) && $_POST['submit'] == 'Create_actor') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);

    try {
        $query = "INSERT INTO actors (first_name, last_name) VALUES (:firstname, :lastname)";
        $stmt = $connection->prepare($query);

        // Sanitize input
        $washFirstname = htmlspecialchars($firstname);
        $washLastname = htmlspecialchars($lastname);

        $stmt->bindParam(':firstname', $washFirstname);
        $stmt->bindParam(':lastname', $washLastname);

        $result = $stmt->execute();

        if ($result) {
            $actorMessage = "Actor Added.";
        } else {
            $actorMessage = "Actor could not be created.";
        }
    } catch (PDOException $e) {
        $actorMessage = "Actor could not be created. Error: " . $e->getMessage();
    }
}
if (!empty($actorMessage)) {
    echo "<p>" . $actorMessage . "</p>";
}

try {
    $query = "SELECT * FROM actors";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $actors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

// Delete actor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete_actor') {
    // Use id to delete
    $actorIdToDelete = $_POST['actor_id'];

    try {
        $deleteQuery = "DELETE FROM actors WHERE actorID = :actorId";
        $stmt = $connection->prepare($deleteQuery);
        $stmt->bindParam(':actorId', $actorIdToDelete);
        $stmt->execute();

        // Redirect or display a success message
        header("Location: " . $base_url . "/admin-board"); // Redirect to refresh the page
        exit();
    } catch (PDOException $e) {
        $actorMessage = "Error deleting actor: " . $e->getMessage();
    }
}

// Edit/Update actor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_actor') {
    $actorIdToUpdate = $_POST['actor_id'];
    $newFirstName = $_POST['firstname'];
    $newLastName = $_POST['lastname'];

    try {
        $updateQuery = "UPDATE actors SET first_name = :firstName, last_name = :lastName WHERE actorID = :actorId";
        $stmt = $connection->prepare($updateQuery);

        // Sanitize input
        $washNewFirstName = htmlspecialchars($newFirstName);
        $washNewLastName = htmlspecialchars($newLastName);

        $stmt->bindParam(':firstName', $washNewFirstName);
        $stmt->bindParam(':lastName', $washNewLastName);
        $stmt->bindParam(':actorId', $actorIdToUpdate);
        $stmt->execute();

        // Redirect or display a success message
        header("Location: " . $base_url . "/admin-board");
        exit();
    } catch (PDOException $e) {
        $actorMessage = "Error updating actor: " . $e->getMessage();
    }
}

?>

<div class="container">
    <h1>Actors:</h1>

    <form action="<?php echo $base_url; ?>/admin-board" method="POST">
        <label for="firstname">First name:</label>
        <input type="text" name="firstname">
        <label for="lastname">Last name:</label>
        <input type="text" name="lastname">
        <input type="submit" name="submit" value="Create_actor" />
    </form>
    <br>
    <ul>
        <?php foreach ($actors as $actor): ?>
            <li>
                <!-- Display Actor -->
                <?php echo $actor['first_name'] . ' ' . $actor['last_name']; ?>
                <span class="btn btn-light disabled btn-sm">ID: <?php echo $actor['actorID']; ?></span>

                <!-- Delete Actor Form -->
                <form action="<?php echo $base_url;?>/admin-board" method="POST" style="display: inline;">
                    <input type="hidden" name="actor_id" value="<?php echo $actor['actorID']; ?>">
                    <input type="hidden" name="action" value="delete_actor">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>

                <!-- Button for popup to edit actor -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editActor<?php echo $actor['actorID']; ?>">
                    Edit
                </button>

                <!-- Popup -->
                <div class="modal fade" id="editActor<?php echo $actor['actorID']; ?>" tabindex="-1" aria-labelledby="editActor<?php echo $actor['actorID']; ?>Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="editActor<?php echo $actor['actorID']; ?>Label">Edit Actor:</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="<?php echo $base_url;?>/admin-board" method="POST" style="display: inline;" class="modal-body">
                                <input type="hidden" name="actor_id" value="<?php echo $actor['actorID']; ?>">
                                <input type="hidden" name="action" value="update_actor">
                                <label for="firstname">First Name:</label>
                                <input class="w-100 mb-2" type=" text" name="firstname" value="<?php echo $actor['first_name']; ?>" placeholder="First Name" required>
                                <label for="lastname">Last Name:</label>
                                <input class="w-100 mb-2" type=" text" name="lastname" value="<?php echo $actor['last_name']; ?>" placeholder="Last Name" required>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close without saving</button>
                                    <button type="submit" class="btn btn-warning btn-sm">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </li>
        <?php endforeach; ?>
    </ul>
</div>