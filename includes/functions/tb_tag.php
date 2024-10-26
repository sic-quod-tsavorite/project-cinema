<?php
if (isset($_POST['submit']) && $_POST['submit'] == 'Create_tag') {
    $name = trim($_POST['name']);
    $short_description = trim($_POST['short_description']);

    try {
        $query = "INSERT INTO tag (name, short_description) VALUES (:name, :short_description)";
        $stmt = $connection->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':short_description', $short_description);

        $result = $stmt->execute();

        if ($result) {
            $tagMessage = "Tag Added.";
        } else {
            $tagMessage = "Tag could not be created.";
        }
    } catch (PDOException $e) {
        $tagMessage = "Tag could not be created. Error: " . $e->getMessage();
    }
}

if (!empty($tagMessage)) {
    echo "<p>" . $tagMessage . "</p>";
}

try {
    $query = "SELECT * FROM tag";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $tag = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete_tag') {
    // Handle tag deletion
    $tagIdToDelete = $_POST['tag_id'];

    try {
        $deleteQuery = "DELETE FROM tag WHERE tagID = :tagId";
        $stmt = $connection->prepare($deleteQuery);
        $stmt->bindParam(':tagId', $tagIdToDelete);
        $stmt->execute();

        // Redirect or display a success message
        header("Location: /project-cinema/admin-board"); // Redirect to refresh the page
        exit();
    } catch (PDOException $e) {
        $tagMessage = "Error deleting tag: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_tag') {
    $tagIdToUpdate = $_POST['tag_id'];
    $newname = $_POST['name'];
    $newshort_description = $_POST['short_description'];

    try {
        $updateQuery = "UPDATE tag SET name = :name, short_description = :short_description WHERE tagID = :tagId";
        $stmt = $connection->prepare($updateQuery);
        $stmt->bindParam(':name', $newname);
        $stmt->bindParam(':short_description', $newshort_description);
        $stmt->bindParam(':tagId', $tagIdToUpdate);
        $stmt->execute();

        // Redirect or display a success message
        header("Location: /project-cinema/admin-board");
        exit();
    } catch (PDOException $e) {
        $tagMessage = "Error updating tag: " . $e->getMessage();
    }
}

?>

<div class="container">
    <h1>tag:</h1>

    <form action="/project-cinema/admin-board" method="POST">
        <label for="name">Tag:</label>
        <input type="text" name="name">
        <label for="short_description">Short Description:</label>
        <input type="text" name="short_description">
        <input type="submit" name="submit" value="Create_tag" />
    </form>
    <br>
    <ul>
        <?php foreach ($tag as $tags): ?>
            <li class="container bg-primary-subtle rounded-3 p-2 my-5">
                <!-- Display Tag Information -->
                <?php echo '<p class="text-secondary"><b>Name:</b> ' . $tags['name'] . '</p> <p class="text-accent"><b>Short Description:</b> ' . $tags['short_description']; ?>
                <span class="btn btn-light disabled btn-sm">ID: <?php echo $tags['tagID']; ?></span>

                <!-- Delete Tag Form -->
                <form action="/project-cinema/admin-board" method="POST" style="display: inline;">
                    <input type="hidden" name="tag_id" value="<?php echo $tags['tagID']; ?>">
                    <input type="hidden" name="action" value="delete_tag">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edittag<?php echo $tags['tagID']; ?>">
                    Edit Tag
                </button>

                <!-- Modal -->
                <div class="modal fade" id="edittag<?php echo $tags['tagID']; ?>" tabindex="-1" aria-labelledby="edittag<?php echo $tags['tagID']; ?>Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="edittag<?php echo $tags['tagID']; ?>Label">Edit: Tag Name: <?php echo $tags['name'] . ' Short Description: ' . $tags['short_description']; ?></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <!-- Edit Tag Form (Initially Hidden) -->
                            <form action="/project-cinema/admin-board" method="POST" style="display: inline;" class="modal-body">
                                <input type="hidden" name="tag_id" value="<?php echo $tags['tagID']; ?>">
                                <input type="hidden" name="action" value="update_tag">
                                <input type="text" name="name" placeholder="New Tag" required>
                                <input type="text" name="short_description" placeholder="New Short Description" required>
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