<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');
checkAdminLogin();

if (isset($_GET['job_id'])) {
    $id = $_GET['job_id'];
    $sql = "DELETE FROM jobs WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Job deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    header("Location: view_jobs.php");
}
?>
