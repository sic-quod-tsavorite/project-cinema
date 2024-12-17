<?php
$page_title = "Booking | Piximation Cinema";
include_once "./constants/pre-html.php";

if (!logged_in()) {
    header("Location: $base_url/login");
    exit;
}

include_once "./constants/header.php";

// Get cinema hall information
/*try {
    $query = "SELECT * FROM cinemahalls WHERE cinemaHallID = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $hall = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}*/

if (isset($_POST['submit'])) {
    $userID = $_SESSION['user_id'];
    $seats = trim($_POST['seats']);

    $washSeats = htmlspecialchars($seats);

    // Validate seats input
    if (!preg_match('/^[1-5]+$/', $washSeats)) {
        // Handle invalid input, display an error message
        echo "Please enter a valid number of seats (1-5).";
        return;
    } else {

        try {
            $query = "INSERT INTO bookings (userID, amsID, bookedSeats) VALUES (:userID, :amsID, :bookedSeats)";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':userID', $userID);
            $stmt->bindParam(':amsID', $id);
            $stmt->bindParam(':bookedSeats', $washSeats);
            $stmt->execute();

            if ($result) {
                $message = "Booking succesfull.";
                header("Location: " . $base_url . "/account-page");
                exit();
            } else {
                $message = "Booking unsuccessful.";
            }

        } catch (PDOException $e) {
            die("Database query failed: " . $e->getMessage());
        }
    }
}

?>

<div class="container py-5">
    <form action="<?php echo $base_url; ?>/account-page" method="post">
        <label for="seats">Select seats (up to 5):</label>
        <input type="number" name="seats" id="seats" min="1" max="5" required>
        <br>
        <input type="submit" class="btn btn-primary mt-3" value="Create" />
        <input type="hidden" class="g-recaptcha" data-sitekey="reCAPTCHA_site_key" data-callback='onSubmit' data-action='submit'>
    </form>
</div>

<?php include_once "./constants/footer.php";    ?>