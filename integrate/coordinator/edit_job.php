<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');
checkAdminLogin();

if (isset($_GET['job_id'])) {
    $id = $_GET['job_id'];
    $result = $conn->query("SELECT * FROM jobs WHERE id='$id'");
    $job = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $skills = $_POST['skills'];
        $domain = $_POST['domain'];
        $position = $_POST['position'];
        $experience = $_POST['experience'];
        $salary = $_POST['salary'];
        $openings = $_POST['openings'];
        $eligibility = $_POST['eligibility'];
        $shift = $_POST['shift'];
        $schedule = $_POST['schedule'];

        $sql = "UPDATE jobs SET title='$title', description='$description', skills='$skills', domain='$domain', position='$position', experience='$experience', salary='$salary', openings='$openings', eligibility='$eligibility', shift='$shift', schedule='$schedule' WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            echo "Job updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} else {
    header("Location: view_jobs.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Job</title>
</head>
<body>
    <form method="POST" action="">
        <label for="title">Job Title:</label>
        <input type="text" id="title" name="title" value="<?php echo $job['title']; ?>" required><br>
        
        <label for="description">Job Description:</label>
        <textarea id="description" name="description" required><?php echo $job['description']; ?></textarea><br>
        
        <label for="skills">Skills Required:</label>
        <input type="text" id="skills" name="skills" value="<?php echo $job['skills']; ?>" required><br>
        
        <label for="domain">Domain:</label>
        <input type="text" id="domain" name="domain" value="<?php echo $job['domain']; ?>" required><br>
        
        <label for="position">Position:</label>
        <input type="text" id="position" name="position" value="<?php echo $job['position']; ?>" required><br>
        
        <label for="experience">Experience:</label>
        <input type="text" id="experience" name="experience" value="<?php echo $job['experience']; ?>" required><br>
        
        <label for="salary">Estimated Salary:</label>
        <input type="text" id="salary" name="salary" value="<?php echo $job['salary']; ?>" required><br>
        
        <label for="openings">No. of Job Openings:</label>
        <input type="number" id="openings" name="openings" value="<?php echo $job['openings']; ?>" required><br>
        
        <label for="eligibility">Who is Eligible:</label>
        <input type="text" id="eligibility" name="eligibility" value="<?php echo $job['eligibility']; ?>" required><br>
        
        <label for="shift">Shift and Schedule:</label>
        <input type="text" id="shift" name="shift" value="<?php echo $job['shift']; ?>" required><br>


        <label for="schedule">Schedule:</label>
        <input type="text" id="schedule" name="schedule" value="<?php echo $job['schedule']; ?>" required><br>
        
        <input type="submit" value="Update Job">
    </form>
</body>
</html>
