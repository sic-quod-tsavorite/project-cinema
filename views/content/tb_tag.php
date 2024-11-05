<?php
// Create tag
if (isset($_POST['submit']) && $_POST['submit'] == 'Create_tag') {
    $name = trim($_POST['name']);
    $tagType = trim($_POST['tagType']);
    $short_description = trim($_POST['short_description']);

    try {
        $query = "INSERT INTO tag (name, tagType, short_description) VALUES (:name, :tagType, :short_description)";
        $stmt = $connection->prepare($query);

        // Sanitize input
        $washName = htmlspecialchars($name);
        $washTagType = htmlspecialchars($tagType);
        $washShort_description = htmlspecialchars($short_description);

        $stmt->bindParam(':name', $washName);
        $stmt->bindParam(':tagType', $washTagType);
        $stmt->bindParam(':short_description', $washShort_description);

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
// Delete tag
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete_tag') {
    // Use id to delete
    $tagIdToDelete = $_POST['tag_id'];

    try {
        $deleteQuery = "DELETE FROM tag WHERE tagID = :tagId";
        $stmt = $connection->prepare($deleteQuery);
        $stmt->bindParam(':tagId', $tagIdToDelete);
        $stmt->execute();

        header("Location: /project-cinema/admin-board");
        exit();
    } catch (PDOException $e) {
        $tagMessage = "Error deleting tag: " . $e->getMessage();
    }
}

// Edit/Update tag
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_tag') {
    $tagIdToUpdate = $_POST['tag_id'];
    $newname = $_POST['name'];
    $newtagType = $_POST['tagType'];
    $newshort_description = $_POST['short_description'];

    try {
        $updateQuery = "UPDATE tag SET name = :name, tagType = :tagType, short_description = :short_description WHERE tagID = :tagId";
        $stmt = $connection->prepare($updateQuery);

        // Sanitize input
        $washNewname = htmlspecialchars($newname);
        $washNewtagType = htmlspecialchars($newtagType);
        $washNewshort_description = htmlspecialchars($newshort_description);

        $stmt->bindParam(':name', $washNewname);
        $stmt->bindParam(':tagType', $washNewtagType);
        $stmt->bindParam(':short_description', $washNewshort_description);
        $stmt->bindParam(':tagId', $tagIdToUpdate);
        $stmt->execute();

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
        <label for="tagType">Tag Type:</label>
        <select name="tagType">
            <option value="1">Main</option>
            <option value="2">Key</option>
            <option value="3">SubKey</option>
        </select><br>
        <label for="short_description">Short Description:</label>
        <input type="text" name="short_description">
        <input type="submit" name="submit" value="Create_tag" />
    </form>
    <br>
    <ul>
        <?php foreach ($tag as $tags): ?>
            <li class="container bg-primary-subtle rounded-3 p-2 my-5">
                <!-- Display Tag -->
                <?php echo '<p class="text-secondary"><b>Name:</b> ' . $tags['name'] . '</p> <p class="text-accent"><b>Short Description:</b> ' . $tags['short_description']; ?>
                <?php
                $tagTypeName = "";
                switch ($tags['tagType']) {
                    case 1:
                        $tagTypeName = "Main";
                        break;
                    case 2:
                        $tagTypeName = "Key";
                        break;
                    case 3:
                        $tagTypeName = "SubKey";
                        break;
                    default:
                        $tagTypeName = "Unknown";
                }
                echo "<p>Tag Type: <b>" . $tagTypeName . "</b></p>";
                ?>
                <span class="btn btn-light disabled btn-sm">ID: <?php echo $tags['tagID']; ?></span>

                <!-- Delete Tag Form -->
                <form action="/project-cinema/admin-board" method="POST" style="display: inline;">
                    <input type="hidden" name="tag_id" value="<?php echo $tags['tagID']; ?>">
                    <input type="hidden" name="action" value="delete_tag">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>

                <!-- Button for popup to edit tag -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edittag<?php echo $tags['tagID']; ?>">
                    Edit
                </button>

                <!-- Popup -->
                <div class="modal fade" id="edittag<?php echo $tags['tagID']; ?>" tabindex="-1" aria-labelledby="edittag<?php echo $tags['tagID']; ?>Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="edittag<?php echo $tags['tagID']; ?>Label">Edit:</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="/project-cinema/admin-board" method="POST" style="display: inline;" class="modal-body">
                                <input type="hidden" name="tag_id" value="<?php echo $tags['tagID']; ?>">
                                <input type="hidden" name="action" value="update_tag">
                                <input type="text" name="name" value="<?php echo $tags['name']; ?>" placeholder="New Tag Name" required>
                                <label for="tagType">Tag Type:</label>
                                <select name="tagType">
                                    <option value="1" <?php if ($tags['tagType'] == 1) echo 'selected'; ?>>
                                        Main
                                    </option>
                                    <option value="2" <?php if ($tags['tagType'] == 2) echo 'selected'; ?>>
                                        Key
                                    </option>
                                    <option value="3" <?php if ($tags['tagType'] == 3) echo 'selected'; ?>>
                                        SubKey
                                    </option>
                                </select><br>
                                <input type="text" name="short_description" value="<?php echo $tags['short_description']; ?>" placeholder="New Short Description" required>
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