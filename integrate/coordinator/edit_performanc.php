<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

// Check if the admin is logged in
if (!isset($_SESSION['coordinaror'])) {
    header("Location: login.php");
    exit();
}

// Get the application ID from the query string
$application_id = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch the current performance tracking data for this application
$sql = "SELECT * FROM performance_tracking WHERE application_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $application_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("No performance tracking data found for the given application ID.");
}

$performance = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the new values from the form
    $aptitude = $_POST['aptitude'];
    $technical_interview = $_POST['technical_interview'];
    $offer_letter = $_POST['offer_letter'];
    $placed = $_POST['placed'];
    $rejected = $_POST['rejected'];

    // Update the performance tracking data
    $update_sql = "UPDATE performance_tracking 
                   SET aptitude = ?, technical_interview = ?, offer_letter = ?, placed = ?, rejected = ? 
                   WHERE application_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssi", $aptitude, $technical_interview, $offer_letter, $placed, $rejected, $application_id);

    if ($update_stmt->execute()) {
        echo "Performance tracking updated successfully!";
    } else {
        echo "Error updating performance tracking: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Performance Tracking</title>
</head>
<body>
    <h2>Edit Performance Tracking</h2>

    <form method="POST">
        <label for="aptitude">Aptitude Test:</label>
        <select name="aptitude" id="aptitude">
            <option value="pending" <?php if ($performance['aptitude'] == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="completed" <?php if ($performance['aptitude'] == 'completed') echo 'selected'; ?>>Completed</option>
        </select><br>

        <label for="technical_interview">Technical Interview:</label>
        <select name="technical_interview" id="technical_interview">
            <option value="pending" <?php if ($performance['technical_interview'] == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="completed" <?php if ($performance['technical_interview'] == 'completed') echo 'selected'; ?>>Completed</option>
        </select><br>

        <label for="offer_letter">Offer Letter:</label>
        <select name="offer_letter" id="offer_letter">
            <option value="pending" <?php if ($performance['offer_letter'] == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="completed" <?php if ($performance['offer_letter'] == 'completed') echo 'selected'; ?>>Completed</option>
        </select><br>

        <label for="placed">Placed:</label>
        <select name="placed" id="placed">
            <option value="pending" <?php if ($performance['placed'] == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="completed" <?php if ($performance['placed'] == 'completed') echo 'selected'; ?>>Completed</option>
        </select><br>

        <label for="rejected">Rejected:</label>
        <select name="rejected" id="rejected">
            <option value="no" <?php if ($performance['rejected'] == 'no') echo 'selected'; ?>>No</option>
            <option value="yes" <?php if ($performance['rejected'] == 'yes') echo 'selected'; ?>>Yes</option>
        </select><br>

        <button type="submit">Update Performance</button>
    </form>

    <a href="performance_tracking.php">Back to Performance Tracking</a>
</body>
</html>
