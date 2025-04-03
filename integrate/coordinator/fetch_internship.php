<?php
include('../includes/db.php');

// Capture search parameters from the AJAX request
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$internship_count = isset($_POST['internship_count']) ? $_POST['internship_count'] : '';

// Prepare SQL query with parameterized statements
$query = "SELECT s.id, s.name, s.email, COUNT(i.id) AS internship_count
          FROM students s
          LEFT JOIN internships i ON s.id = i.student_id
          WHERE s.name LIKE ? 
            AND s.email LIKE ? 
          GROUP BY s.id
          HAVING COUNT(i.id) LIKE ?";

$stmt = $conn->prepare($query);
$search_name = '%' . $name . '%';
$search_email = '%' . $email . '%';
$search_internship_count = '%' . $internship_count . '%';

$stmt->bind_param("sss", $search_name, $search_email, $search_internship_count);
$stmt->execute();
$result = $stmt->get_result();

$output = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= '
        <tr>
            <td>' . htmlspecialchars($row['name']) . '</td>
            <td>' . htmlspecialchars($row['email']) . '</td>
            <td>' . htmlspecialchars($row['internship_count']) . '</td>
            <td><a class="text-sm btn btn-sm btn-primary" href="view_internship_details.php?student_id=' . $row['id'] . '">Details</a></td>
        </tr>';
    }
} else {
    $output = "<tr><td colspan='4'>No students found.</td></tr>";
}

echo $output;
?>
