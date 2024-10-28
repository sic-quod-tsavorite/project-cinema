<?php
// Dynamic page for movies
$page_title = "Film Page";
include_once "./constants/header.php";

// Select the movie based on the $id from routes.php
try {
    $query = "SELECT * FROM movies WHERE movieID = $id";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

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
    </div>
</div>

<?php include_once "./constants/footer.php";    ?>