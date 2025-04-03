<?php
include('../includes/db.php');

if (isset($_POST['ids']) && isset($_POST['status'])) {
    $ids = $_POST['ids'];
    $status = $_POST['status'];
    
    // Convert array of IDs to comma-separated string for the query
    $idList = implode(',', array_map('intval', $ids));

    $query = "UPDATE applications SET status = '$status' WHERE id IN ($idList)";
    
    if ($conn->query($query) === TRUE) {
        echo "Applications have been " . ($status == 'accepted' ? "accepted." : "rejected.");
    } else {
        echo "Error updating records: " . $conn->error;
    }
}
?>
