<?php
session_start();
include('../includes/db.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$query = isset($_GET['query']) ? $_GET['query'] : '';

// Prepare the SQL query to filter data
$sql = "SELECT 
            pt.application_id, 
            s.name AS student_name, 
            s.email AS student_email, 
            s.phone AS student_phone, 
            s.branch AS student_branch, 
            j.title AS job_title, 
            j.description AS job_description, 
            j.domain AS job_domain, 
            pt.aptitude, 
            pt.technical_interview, 
            pt.offer_letter, 
            pt.placed, 
            pt.rejected,
            pt.rejection_reason  -- Assuming rejection_reason exists in the performance_tracking table
        FROM performance_tracking pt 
        JOIN applications a ON pt.application_id = a.id 
        JOIN students s ON a.student_id = s.id 
        JOIN jobs j ON a.job_id = j.id 
        WHERE a.status = 'accepted' AND (
            s.name LIKE ? OR
            s.email LIKE ? OR
            s.phone LIKE ? OR
            s.branch LIKE ? OR
            j.title LIKE ? OR
            j.description LIKE ? OR
            j.domain LIKE ?
        )";

$stmt = $conn->prepare($sql);
$searchTerm = "%$query%";
$stmt->bind_param('sssssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $totalStages = 5;
    $completedStages = 0;

    if ($row['aptitude'] == 'completed') $completedStages++;
    if ($row['technical_interview'] == 'completed') $completedStages++;
    if ($row['offer_letter'] == 'completed') $completedStages++;
    if ($row['placed'] == 'completed') $completedStages++;

    if ($row['rejected'] == 'yes') {
        $completedStages = 0; // Reset if rejected
    }

    $progressPercentage = ($completedStages / $totalStages) * 100;

    // Add progress and rejection reason to the response
    $row['progressPercentage'] = $progressPercentage;
    $row['rejection_reason'] = $row['rejection_reason'] ? $row['rejection_reason'] : 'No reason provided'; // Default text if no reason provided
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
