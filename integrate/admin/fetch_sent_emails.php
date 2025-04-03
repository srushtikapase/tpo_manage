<?php
require '../config.php'; // Database connection

header('Content-Type: application/json');

$sql = "SELECT DISTINCT recipient FROM sent_mails ORDER BY id DESC";
$result = $conn->query($sql);

$emails = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $emails[] = $row['recipient'];
    }
}

echo json_encode($emails);
?>
