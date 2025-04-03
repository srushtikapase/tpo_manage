<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    $sql = "DELETE FROM students WHERE id='$student_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_students.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
