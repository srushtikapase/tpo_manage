<?php
include('../includes/db.php');

header('Content-Type: application/json');

$sql = "SELECT id, recipient, subject, sent_at FROM sent_mails ORDER BY sent_at DESC";
$result = $conn->query($sql);

$emails = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $emails[] = $row;
    }
}

echo json_encode($emails);
?>
