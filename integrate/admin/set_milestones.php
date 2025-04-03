<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission for creating milestones
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $milestone_titles = $_POST['milestone_title'];
    $milestone_descriptions = $_POST['milestone_description'];
    $company_id = $_POST['company_id'];

    foreach ($milestone_titles as $index => $title) {
        $description = $milestone_descriptions[$index];

        // Insert each milestone into the milestones table
        $insert_milestone = "INSERT INTO milestones (company_id, title, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_milestone);
        $stmt->bind_param("iss", $company_id, $title, $description);
        $stmt->execute();
    }
    echo "Milestones added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Milestones</title>
</head>
<body>
    <h2>Create Milestones for Company</h2>
    <form method="POST">
        <label for="company_id">Select Company:</label>
        <select name="company_id" id="company_id">
            <?php
            $company_sql = "SELECT id, comname FROM jobs";
            $company_result = $conn->query($company_sql);
            while ($company = $company_result->fetch_assoc()) {
                echo "<option value='{$company['id']}'>{$company['comname']}</option>";
            }
            ?>
        </select>

        <div id="milestone-container">
            <div class="milestone-input">
                <input type="text" name="milestone_title[]" placeholder="Milestone Title" required>
                <input type="text" name="milestone_description[]" placeholder="Milestone Description" required>
            </div>
        </div>

        <button type="button" onclick="addMilestone()">Add Another Milestone</button>
        <br>
        <button type="submit">Save Milestones</button>
    </form>

    <script>
        function addMilestone() {
            const container = document.getElementById('milestone-container');
            const newMilestone = document.createElement('div');
            newMilestone.classList.add('milestone-input');
            newMilestone.innerHTML = `
                <input type="text" name="milestone_title[]" placeholder="Milestone Title" required>
                <input type="text" name="milestone_description[]" placeholder="Milestone Description" required>
            `;
            container.appendChild(newMilestone);
        }
    </script>
</body>
</html>
