<?php
function checkAdminLogin() {
    if (!isset($_SESSION['admin'])) {
        header("Location: login.php");
        exit();
    }
}
?>
