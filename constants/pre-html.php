<?php
require_once("./includes/functions/session.php");
require_once("./includes/functions/connection.php");

$connection = createConnection();

if (logged_in()) { // if logged in check account rank
    try {
        $adquery = "SELECT userID, accountRank FROM useraccounts WHERE userID = :userID";
        $stmt = $connection->prepare($adquery);
        $stmt->bindParam(':userID', $_SESSION['user_id']);
        $stmt->execute();
        $adquery = $stmt->fetch(PDO::FETCH_ASSOC);

        $isAdmin = $adquery['accountRank'];
    } catch (PDOException $e) {
        die("Database query failed: " . $e->getMessage());
    }
};
