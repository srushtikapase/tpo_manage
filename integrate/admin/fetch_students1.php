<?php
include('../includes/db.php');

$conditions = [];
$params = [];

if (!empty($_POST['prn'])) {
    $conditions[] = "prn LIKE ?";
    $params[] = "%" . $_POST['prn'] . "%";
}
if (!empty($_POST['phone'])) {
    $conditions[] = "phone LIKE ?";
    $params[] = "%" . $_POST['phone'] . "%";
}
if (!empty($_POST['username'])) {
    $conditions[] = "username LIKE ?";
    $params[] = "%" . $_POST['username'] . "%";
}
if (!empty($_POST['name'])) {
    $conditions[] = "name LIKE ?";
    $params[] = "%" . $_POST['name'] . "%";
}
if (!empty($_POST['email'])) {
    $conditions[] = "email LIKE ?";
    $params[] = "%" . $_POST['email'] . "%";
}

// Build query
$query = "SELECT * FROM students";
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" OR ", $conditions);
}

// Prepare and execute statement
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['prn']}</td>
            <td>{$row['roll_no']}</td>
            <td>{$row['name']}</td>
            <td>{$row['year']}</td>
            <td>{$row['branch']}</td>
            <td>{$row['division']}</td>
            <td>{$row['batch']}</td>
            <td>{$row['address']}</td>
            <td>{$row['email']}</td>
            <td>{$row['gender']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['username']}</td>
            <td>******</td>
            <td>
                <a href='edit_student.php?id={$row['id']}' class='btn btn-sm btn-warning'>Edit</a>
                <a href='delete_student.php?id={$row['id']}' class='btn btn-sm btn-danger' 
                   onclick='return confirm(\"Are you sure you want to delete this student?\")'>Delete</a>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='15' class='text-center'>No students found</td></tr>";
}
$stmt->close();
?>
