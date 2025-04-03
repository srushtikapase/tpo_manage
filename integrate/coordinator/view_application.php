<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $application_id = $_GET['id'];
    $result = $conn->query("SELECT applications.*, students.*, jobs.title AS job_title FROM applications 
                            JOIN students ON applications.student_id = students.id 
                            JOIN jobs ON applications.job_id = jobs.id 
                            WHERE applications.id = '$application_id'");
    $application = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Application</title>
</head>
<body>
    <h2>Application Details</h2>
    <p><strong>Job Title:</strong> <?php echo $application['job_title']; ?></p>
    <p><strong>Student Name:</strong> <?php echo $application['name']; ?></p>
    <p><strong>Year:</strong> <?php echo $application['year']; ?></p>
    <p><strong>Branch:</strong> <?php echo $application['branch']; ?></p>
    <p><strong>Domain:</strong> <?php echo $application['domain']; ?></p>
    <p><strong>Address:</strong> <?php echo $application['address']; ?></p>
    <p><strong>Phone:</strong> <?php echo $application['phone']; ?></p>
    <p><strong>Email:</strong> <?php echo $application['email']; ?></p>
    <p><strong>Experience:</strong> <?php echo $application['experience']; ?></p>
    <p><strong>Resume:</strong> <a href="../uploads/<?php echo $application['resume']; ?>" target="_blank">View Resume</a></p>
</body>
</html>
