<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $job_id = $_GET['id'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_username = $_SESSION['student'];
    $sql_student = "SELECT id FROM students WHERE username='$student_username'";
    $result_student = $conn->query($sql_student);
    $student = $result_student->fetch_assoc();
    $student_id = $student['id'];

    $name = $_POST['name'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];
    $domain = $_POST['domain'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $experience = $_POST['experience'];

    // File Upload Handling
    $resume = $_FILES['resume']['name'];
    $photo = $_FILES['photo']['name'];
    $target_dir = "../uploads/";

    // File Paths
    $target_file_resume = $target_dir . basename($_FILES["resume"]["name"]);
    $target_file_photo = $target_dir . basename($_FILES["photo"]["name"]);

    // Move files
    move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file_resume);
    move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file_photo);

    // Insert into DB
    $sql = "INSERT INTO applications (student_id, job_id, name, year, branch, domain, address, phone, email, experience, resume, photo) 
            VALUES ('$student_id', '$job_id', '$name', '$year', '$branch', '$domain', '$address', '$phone', '$email', '$experience', '$target_file_resume', '$target_file_photo')";

    if ($conn->query($sql) === TRUE) {
        echo "Application submitted successfully";
        header("Location: view_applications.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch Job Details
$job_result = $conn->query("SELECT * FROM jobs WHERE id='$job_id'");
$job = $job_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for <?php echo $job['title']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-submit {
            background-color: #007bff;
            color: white;
            width: 100%;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center text-primary">Apply for <?php echo $job['title']; ?></h2>
    <form method="POST" enctype="multipart/form-data" action="">

        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" required 
                   placeholder="First Name Middle Name Last Name" 
                   onfocus="clearPlaceholder(this)" onblur="restorePlaceholder(this)">
        </div>

        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="text" class="form-control" id="year" name="year" required>
        </div>

        <div class="mb-3">
            <label for="branch" class="form-label">Branch</label>
            <input type="text" class="form-control" id="branch" name="branch" required>
        </div>

        <div class="mb-3">
            <label for="domain" class="form-label">Domain of Work</label>
            <input type="text" class="form-control" id="domain" name="domain" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone No</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email (Personal)</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="experience" class="form-label">Short Description about Experience</label>
            <textarea class="form-control" id="experience" name="experience" required></textarea>
        </div>

        <div class="mb-3">
            <label for="resume" class="form-label">Upload Resume (PDF)</label>
            <input type="file" class="form-control" id="resume" name="resume" accept="application/pdf" required>
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Upload Photo (JPEG, PNG)</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg, image/png" required>
        </div>

        <button type="submit" class="btn btn-submit">Submit Application</button>
    </form>
</div>

<script>
    function clearPlaceholder(input) {
        if (input.value === "First Name Middle Name Last Name") {
            input.value = "";
        }
    }

    function restorePlaceholder(input) {
        if (input.value === "") {
            input.value = "First Name Middle Name Last Name";
        }
    }
</script>

</body>
</html>
