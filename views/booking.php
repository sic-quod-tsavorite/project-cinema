<?php
$page_title = "Booking | Piximation Cinema";
include_once "./constants/pre-html.php";

if (!logged_in()) {
    header("Location: $base_url/login");
    exit;
}

include_once "./constants/header.php";

// Junction with hallseats and airmovieshowings table
try {
    $query = "SELECT hs.seatID, hs.cinemaHallID, hs.rowName, hs.seatRowNumber 
              FROM hallseats hs
              INNER JOIN airmovieshowings ams ON hs.cinemaHallID = ams.cinemaHallID
              WHERE ams.amsID = :amsID";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':amsID', $id);
    $stmt->execute();
    $seats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

// Junction with seatbookings and airmovieshowings table
try {
    $query = "SELECT sb.seatBookingID, sb.bookingID, sb.seatID, sb.amsID
              FROM seatbookings sb
              INNER JOIN airmovieshowings ams ON sb.amsID = ams.amsID
              WHERE ams.amsID = :amsID";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':amsID', $id);
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

?>

<div class="py-5 d-flex flex-column align-items-center">
    <h1 class="mb-4">Booking</h1>

    <div class="bg-primary-subtle rounded-3 p-4 wmc">
    <?php $lastRow = ""; ?>
    <?php foreach ($seats as $seat) { ?>
        <?php if ($seat['rowName'] !== $lastRow) { ?>
            <div class="d-flex">
                <div class="mx-4 my-auto">
                    <?php echo $seat['rowName']; ?>
                </div>

                <?php $lastSeat = ""; ?>
                <?php foreach ($seats as $seatnumber) { ?>
                    <?php if ($seatnumber['rowName'] === $seat['rowName'] && $seatnumber['seatRowNumber'] !== $lastSeat) { ?>
                        <button class="btn btn-primary px-3 my-2 mx-1"><?php echo $seatnumber['seatRowNumber']; ?></button>
                    <?php } ?>
                    <?php $lastSeat = $seatnumber['seatRowNumber']; ?>
                <?php } ?>

            </div>
        <?php } ?>
        <?php $lastRow = $seat['rowName']; ?>
    <?php } ?>
    </div>
</div>

<?php include_once "./constants/footer.php";    ?>