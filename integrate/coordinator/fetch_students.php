<?php
include('../includes/db.php');

$search_query = isset($_POST['query']) ? $_POST['query'] : '';

$sql = "SELECT * FROM students WHERE 
    id LIKE '%$search_query%' OR 
    prn LIKE '%$search_query%' OR 
    roll_no LIKE '%$search_query%' OR 
    name LIKE '%$search_query%' OR 
    year LIKE '%$search_query%' OR 
    branch LIKE '%$search_query%' OR 
    division LIKE '%$search_query%' OR 
    batch LIKE '%$search_query%' OR 
    address LIKE '%$search_query%' OR 
    email LIKE '%$search_query%' OR 
    phone LIKE '%$search_query%' OR 
    username LIKE '%$search_query%' OR 
    password LIKE '%$search_query%'";

$result = $conn->query($sql);
?>

<div class="table-responsive p-0">
    <table class="table align-items-center mb-0">
        <thead>
            
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['prn']); ?></td>
                        <td><?php echo htmlspecialchars($row['roll_no']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['year']); ?></td>
                        <td><?php echo htmlspecialchars($row['branch']); ?></td>
                        <td><?php echo htmlspecialchars($row['division']); ?></td>
                        <td><?php echo htmlspecialchars($row['batch']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['password']); ?></td>
                        <td class="align-middle text-center">
                        <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-edit">Edit</a>
                            <a href="delete_student.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-delete " styel="background : red" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan='14' class="text-center py-3">No students found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
