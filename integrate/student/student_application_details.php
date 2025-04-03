<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

// Check if the student is logged in
if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

// Get student username from session
$student_username = $_SESSION['student'];

// Retrieve student ID
$sql_student = "SELECT id FROM students WHERE username='$student_username'";
$result_student = $conn->query($sql_student);
$student = $result_student->fetch_assoc();
$student_id = $student['id'];

// Get application ID from URL
if (isset($_GET['application_id'])) {
    $application_id = intval($_GET['application_id']);
} else {
    header("Location: student_history.php");
    exit();
}

// Query to get application details for the student
$sql = "SELECT 
            a.id AS application_id,
            j.title AS job_title,
            j.description AS job_description,
            j.domain AS job_domain,
            a.status AS application_status
        FROM applications a
        JOIN jobs j ON a.job_id = j.id
        WHERE a.id = '$application_id' AND a.student_id = '$student_id'";

$result = $conn->query($sql);

// Check if application exists
if ($result->num_rows === 0) {
    header("Location: student_history.php");
    exit();
}

$application = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h2>Application Details</h2>
    <table>
        <tr>
            <th>Job Title</th>
            <td><?php echo htmlspecialchars($application['job_title']); ?></td>
        </tr>
        <tr>
            <th>Job Description</th>
            <td><?php echo htmlspecialchars($application['job_description']); ?></td>
        </tr>
        <tr>
            <th>Job Domain</th>
            <td><?php echo htmlspecialchars($application['job_domain']); ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?php echo htmlspecialchars($application['application_status']); ?></td>
        </tr>
    </table>
    <a href="student_history.php">Back to History</a>
    <a href="logout.php">Logout</a>
</body>
</html>
