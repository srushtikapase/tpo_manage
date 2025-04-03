<?php
include('../includes/db.php');

$company_id = $_GET['company_id'];
$milestones = [];

$milestone_query = "SELECT title, description FROM milestones WHERE company_id = ?";
$stmt = $conn->prepare($milestone_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $milestones[] = $row;
}

echo json_encode($milestones);
?>
