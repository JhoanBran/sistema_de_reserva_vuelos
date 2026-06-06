<?php
session_start();
require_once '../../config/database.php';
require_once '../../controllers/FlightController.php';

$flightController = new FlightController($db);

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../public/login.php");
    exit();
}

$flightId = isset($_GET['flight_id']) ? $_GET['flight_id'] : null;
$flightDetails = $flightController->getFlightDetails($flightId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $passengerName = $_POST['passenger_name'];
    $passengerContact = $_POST['passenger_contact'];

    $bookingSuccess = $flightController->bookFlight($flightId, $userId, $passengerName, $passengerContact);

    if ($bookingSuccess) {
        echo "<p>Booking successful! Confirmation sent to your email.</p>";
    } else {
        echo "<p>Booking failed. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <title>Flight Booking</title>
</head>
<body>
    <h1>Book Flight</h1>
    <?php if ($flightDetails): ?>
        <h2>Flight Details</h2>
        <p>Flight Code: <?php echo htmlspecialchars($flightDetails['flight_code']); ?></p>
        <p>From: <?php echo htmlspecialchars($flightDetails['origin_city']); ?></p>
        <p>To: <?php echo htmlspecialchars($flightDetails['destination_city']); ?></p>
        <p>Date: <?php echo htmlspecialchars($flightDetails['flight_date']); ?></p>

        <form method="POST" action="">
            <label for="passenger_name">Passenger Name:</label>
            <input type="text" id="passenger_name" name="passenger_name" required>
            <label for="passenger_contact">Contact Number:</label>
            <input type="text" id="passenger_contact" name="passenger_contact" required>
            <button type="submit">Confirm Booking</button>
        </form>
    <?php else: ?>
        <p>Flight not found.</p>
    <?php endif; ?>
</body>
</html>