<?php
include('../includes/db.php');

$student_name = isset($_POST['student_name']) ? $_POST['student_name'] : '';
$comname = isset($_POST['comname']) ? $_POST['comname'] : '';
$job_title = isset($_POST['job_title']) ? $_POST['job_title'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';

$query = "SELECT applications.*, students.name AS student_name, jobs.title AS job_title, jobs.comname 
          FROM applications 
          JOIN students ON applications.student_id = students.id 
          JOIN jobs ON applications.job_id = jobs.id 
          WHERE students.name LIKE '%$student_name%' 
          AND jobs.comname LIKE '%$comname%' 
          AND jobs.title LIKE '%$job_title%' 
          AND applications.status LIKE '%$status%'";

$result = $conn->query($query);

$output = '';
while($row = $result->fetch_assoc()) {
    $output .= '
    <tr>
        <td><input type="checkbox" class="select_application" value="'.htmlspecialchars($row['id']).'"></td>
        <td>'.htmlspecialchars($row['id']).'</td>
        <td>'.htmlspecialchars($row['student_name']).'</td>
        <td>'.htmlspecialchars($row['comname']).'</td>
        <td>'.htmlspecialchars($row['job_title']).'</td>
        <td>'.htmlspecialchars($row['status']).'</td>
        <td>
            <a  class="btn btn-info btn-sm text-white" href="view_application.php?id='.$row['id'].'">View</a>
            <a class="btn btn-success btn-sm" href="accept_application.php?id='.$row['id'].'&status=accepted">Accept</a>
            <a class="btn btn-danger btn-sm" href="reject_application.php?id='.$row['id'].'&status=rejected">Reject</a>
        </td>
    </tr>';
}

echo $output;
?>
