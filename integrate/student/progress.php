<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$student_username = $_SESSION['student'];
$sql_student = "SELECT id FROM students WHERE username='$student_username'";
$result_student = $conn->query($sql_student);
$student = $result_student->fetch_assoc();
$student_id = $student['id'];

$result = $conn->query("SELECT applications.*, jobs.title AS job_title FROM applications 
                        JOIN jobs ON applications.job_id = jobs.id 
                        WHERE student_id='$student_id'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Progress</title>
</head>
<body>
    <h2>My Job Application Progress</h2>
    <table border="1">
        <tr>
            <th>Job Title</th>
            <th>Status</th>
            <th>Progress</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['job_title']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>
                <div style="width: 100%; background-color: #f3f3f3;">
                    <div style="width: <?php echo ($row['status'] == 'applied' ? '25%' : ($row['status'] == 'mock interview' ? '50%' : ($row['status'] == 'technical interview' ? '75%' : '100%'))); ?>; background-color: #4caf50; text-align: center; color: white;">
                        <?php echo $row['status']; ?>
                    </div>
                </div>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
