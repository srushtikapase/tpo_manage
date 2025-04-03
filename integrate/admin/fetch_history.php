<?php
include('../includes/db.php');

// Capture search parameters from the AJAX request
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$branch = isset($_POST['branch']) ? $_POST['branch'] : '';

// Prepare SQL query with parameterized statements
$query = "SELECT 
            s.id AS student_id,
            s.name AS student_name,
            s.email AS student_email,
            s.phone AS student_phone,
            s.branch AS student_branch,
            COUNT(a.id) AS application_count
        FROM students s
        LEFT JOIN applications a ON s.id = a.student_id
        WHERE s.name LIKE ? 
          AND s.email LIKE ? 
          AND s.phone LIKE ? 
          AND s.branch LIKE ? 
        GROUP BY s.id";

$stmt = $conn->prepare($query);
$search_name = '%' . $name . '%';
$search_email = '%' . $email . '%';
$search_phone = '%' . $phone . '%';
$search_branch = '%' . $branch . '%';

$stmt->bind_param("ssss", $search_name, $search_email, $search_phone, $search_branch);
$stmt->execute();
$result = $stmt->get_result();

$output = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= '
        <tr>
            <td>' . htmlspecialchars($row['student_name']) . '</td>
            <td>' . htmlspecialchars($row['student_email']) . '</td>
            <td>' . htmlspecialchars($row['student_phone']) . '</td>
            <td>' . htmlspecialchars($row['student_branch']) . '</td>
            <td>' . htmlspecialchars($row['application_count']) . '</td>
            <td><a href="student_application_details.php?student_id=' . $row['student_id'] . '">View Details</a></td>
        </tr>';
    }
} else {
    $output = "<tr><td colspan='6'>No students found.</td></tr>";
}

echo $output;
?>
