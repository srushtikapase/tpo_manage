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
    $sql = "UPDATE applications SET status='accepted' WHERE id='$application_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: view_applications.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
