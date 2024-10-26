<?php
if (isset($_POST['submit']) && $_POST['submit'] == 'Create_actor') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);

    try {
        $query = "INSERT INTO actors (first_name, last_name) VALUES (:firstname, :lastname)";
        $stmt = $connection->prepare($query);

        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);

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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete_actor') {
    // Handle actor deletion
    $actorIdToDelete = $_POST['actor_id'];

    try {
        $deleteQuery = "DELETE FROM actors WHERE actorID = :actorId";
        $stmt = $connection->prepare($deleteQuery);
        $stmt->bindParam(':actorId', $actorIdToDelete);
        $stmt->execute();

        // Redirect or display a success message
        header("Location: /project-cinema/admin-board"); // Redirect to refresh the page
        exit();
    } catch (PDOException $e) {
        $actorMessage = "Error deleting actor: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_actor') {
    $actorIdToUpdate = $_POST['actor_id'];
    $newFirstName = $_POST['firstname'];
    $newLastName = $_POST['lastname'];

    try {
        $updateQuery = "UPDATE actors SET first_name = :firstName, last_name = :lastName WHERE actorID = :actorId";
        $stmt = $connection->prepare($updateQuery);
        $stmt->bindParam(':firstName', $newFirstName);
        $stmt->bindParam(':lastName', $newLastName);
        $stmt->bindParam(':actorId', $actorIdToUpdate);
        $stmt->execute();

        // Redirect or display a success message
        header("Location: /project-cinema/admin-board");
        exit();
    } catch (PDOException $e) {
        $actorMessage = "Error updating actor: " . $e->getMessage();
    }
}

?>

<div class="container">
    <h1>Actors:</h1>

    <form action="/project-cinema/admin-board" method="POST">
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
                <!-- Display Actor Information -->
                <?php echo $actor['first_name'] . ' ' . $actor['last_name']; ?>
                <span class="btn btn-light disabled btn-sm">ID: <?php echo $actor['actorID']; ?></span>

                <!-- Delete Actor Form -->
                <form action="/project-cinema/admin-board" method="POST" style="display: inline;">
                    <input type="hidden" name="actor_id" value="<?php echo $actor['actorID']; ?>">
                    <input type="hidden" name="action" value="delete_actor">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editActor<?php echo $actor['actorID']; ?>">
                    Edit Actor
                </button>

                <!-- Modal -->
                <div class="modal fade" id="editActor<?php echo $actor['actorID']; ?>" tabindex="-1" aria-labelledby="editActor<?php echo $actor['actorID']; ?>Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="editActor<?php echo $actor['actorID']; ?>Label">Edit: First Name: <?php echo $actor['first_name'] . ' Last Name: ' . $actor['last_name']; ?></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <!-- Edit Actor Form (Initially Hidden) -->
                            <form action="/project-cinema/admin-board" method="POST" style="display: inline;" class="modal-body">
                                <input type="hidden" name="actor_id" value="<?php echo $actor['actorID']; ?>">
                                <input type="hidden" name="action" value="update_actor">
                                <input type="text" name="firstname" placeholder="New First Name" required>
                                <input type="text" name="lastname" placeholder="New Last Name" required>
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