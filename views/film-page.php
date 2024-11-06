<?php
// Dynamic page for movies

// Require and establish connection earlier to create page title
require_once("./includes/functions/connection.php");
$connection = createConnection();

// Select the movie based on the $id from routes.php
try {
    $query = "SELECT * FROM movies WHERE movieID = $id";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

// Page title
$page_title = $movies[0]['title'] . " | Piximation Cinema";

// Head
include_once "./constants/header.php";

// Junction with actorrole table
try {
    $query = "SELECT a.first_name, a.last_name, ar.role 
              FROM actors a
              INNER JOIN actorrole ar ON a.actorID = ar.actorID
              WHERE ar.movieID = :movieID";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':movieID', $id);
    $stmt->execute();
    $actors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

// Junction with movietags table
try {
    $query = "SELECT t.name, t.tagID
              FROM tag t
              INNER JOIN movietags mt ON t.tagID = mt.tagID
              WHERE mt.movieID = :movieID";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':movieID', $id);
    $stmt->execute();
    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

?>

<div id="film-hero">
    <img class="z-n1" src="<?php echo $base_url . '/' . $movies[0]['heroimg']; ?>" alt="">
    <div class="container d-flex">
        <div>
            <h2><?php echo $movies[0]['title']; ?></h2>
            <p><?php echo $movies[0]['description']; ?></p>
        </div>
        <video height="450" controls autoplay loop muted>
            <source src="<?php echo $base_url . '/' . $movies[0]['trailer']; ?>" type="video/mp4">
        </video>
    </div>
    <div>
        <h3>Starring:</h3>
        <ul>
            <?php foreach ($actors as $actor): ?>
                <li><?php echo $actor['first_name'] . ' ' . $actor['last_name'] . ' - As: ' . $actor['role']; ?></li>
            <?php endforeach; ?>
        </ul>
        <h3>Genre:</h3>
        <ul>
            <?php foreach ($tags as $tag): ?>
                <li>
                    <?php echo '<a class="link-offset-1 link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="' . $base_url . '/tag/id/' . $tag['tagID'] . '">' . $tag['name'] . '</a>' ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php include_once "./constants/footer.php";    ?>