<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');
checkAdminLogin();

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Autoload the PHPMailer files

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comname = $_POST['comname'];
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

    // Handle the logo upload
    $logo = $_FILES['logo']['name'];
    $target_dir = "../uploads/logos/";
    $target_file_photo = $target_dir . basename($_FILES["logo"]["name"]);
    move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file_photo);

    $sql = "INSERT INTO jobs (comname, title, description, skills, domain, position, experience, salary, openings, eligibility, shift, schedule, logo) 
            VALUES ('$comname', '$title', '$description', '$skills', '$domain', '$position', '$experience', '$salary', '$openings', '$eligibility', '$shift', '$schedule', '$logo')";

    if ($conn->query($sql) === TRUE) {
        echo "New job added successfully";

        // Fetch students with matching domains
        $emailQuery = $conn->prepare("SELECT email FROM students WHERE branch = ?");
        $emailQuery->bind_param('s', $domain);
        $emailQuery->execute();
        $result = $emailQuery->get_result();

        if ($result->num_rows > 0) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'testmailsgu@gmail.com';
                $mail->Password = 'zzxq uxru tgai tgkh';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('testmailsgu@gmail.com', 'SGU TPO Department');

                while ($row = $result->fetch_assoc()) {
                    $to = $row['email'];
                    $mail->addAddress($to);

                    $mail->isHTML(true);
                    $mail->Subject = "New Job Opportunity: $title";
                    $mail->Body = "
                        <h3>New Job Alert!</h3>
                        <p>Company Name: $comname</p>
                        <p>Job Title: $title</p>
                        <p>Description: $description</p>
                        <p>Domain: $domain</p>
                        <p><strong>Apply now!</strong></p>
                    ";

                    if ($mail->send()) {
                        echo "Email sent successfully to: $to<br>";
                    } else {
                        echo "Failed to send email to: $to<br>";
                    }

                    $mail->clearAddresses();
                }
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "No students found for the domain: $domain";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>
