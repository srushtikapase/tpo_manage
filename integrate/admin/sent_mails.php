<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');
checkAdminLogin();

// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; 

// Initialize messages for UI feedback
$error_message = "";
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form values
    $to_email   = trim($_POST['to_email']);
    $department = trim($_POST['department']);
    $subject    = trim($_POST['subject']);
    $message    = trim($_POST['message']);

    // Array to collect recipient emails
    $recipient_emails = array();

    // If a specific email address is provided, add it to the list
    if (!empty($to_email)) {
        $recipient_emails[] = $to_email;
    }

    // If a department is selected, fetch the emails from the students table where branch matches
    if (!empty($department)) {
        $stmt = $conn->prepare("SELECT email FROM students WHERE branch = ?");
        $stmt->bind_param("s", $department);
        $stmt->execute();
        $result_dept = $stmt->get_result();
        while ($row = $result_dept->fetch_assoc()) {
            $recipient_emails[] = $row['email'];
        }
        $stmt->close();
    }

    // Remove duplicate emails if any
    $recipient_emails = array_unique($recipient_emails);

    // Check if at least one recipient was found
    if (empty($recipient_emails)) {
        $error_message = "Please provide a valid email address or select a department.";
    } else {
        // Create a new PHPMailer instance and configure it
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'testmailsgu@gmail.com';
        $mail->Password   = 'zzxq uxru tgai tgkh'; // Consider using environment variables for security.
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('testmailsgu@gmail.com', 'SGU TPO Department');

        // Add all recipient addresses
        foreach ($recipient_emails as $email) {
            $mail->addAddress($email);
        }

        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->isHTML(true);

        // Attempt to send the mail
        if(!$mail->send()) {
            $error_message = 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            // Save the sent mail in the database
            $recipients_str = implode(",", $recipient_emails);
            $stmt = $conn->prepare("INSERT INTO sent_mails (subject, message, recipients, send_date) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $subject, $message, $recipients_str);
            $stmt->execute();
            $stmt->close();
            $success_message = 'Mail sent successfully!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mail Sender</title>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Inter', Arial, sans-serif; }
  </style>
</head>
<body class="bg-light">
  <div class="container py-4">
    <!-- Compose Mail Card -->
    <div class="card mb-4">
      <div class="card-header">
        <h4 class="mb-0">Compose Mail</h4>
      </div>
      <div class="card-body">
        <!-- Display messages if available -->
        <?php if (!empty($error_message)): ?>
          <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
          <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <form method="post" action="">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <input type="text" class="form-control" name="to_email" placeholder="Enter specific email address">
            </div>
            <div class="col-md-6">
              <select class="form-select" name="department">
                <option value="">--Select Department--</option>
                <?php
                // Populate the department dropdown using distinct branches from the students table
                $dept_result = $conn->query("SELECT DISTINCT branch FROM students");
                while ($row = $dept_result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['branch']) . "'>" . htmlspecialchars($row['branch']) . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <input type="text" class="form-control" name="subject" placeholder="Subject" required>
          </div>
          <!-- Optional Formatting Toolbar -->
          <div class="mb-3">
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-light"><i class="fas fa-bold"></i></button>
              <button type="button" class="btn btn-light"><i class="fas fa-italic"></i></button>
              <button type="button" class="btn btn-light"><i class="fas fa-underline"></i></button>
              <button type="button" class="btn btn-light"><i class="fas fa-list"></i></button>
              <button type="button" class="btn btn-light"><i class="fas fa-list-ol"></i></button>
              <button type="button" class="btn btn-light"><i class="fas fa-link"></i></button>
              <button type="button" class="btn btn-light"><i class="fas fa-image"></i></button>
            </div>
          </div>
          <div class="mb-3">
            <textarea class="form-control" name="message" rows="10" placeholder="Write your message here..." required></textarea>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <button type="submit" class="btn btn-primary">Send</button>
              <button type="button" class="btn btn-outline-secondary">
                <i class="fas fa-paperclip me-2"></i>Attach
              </button>
            </div>
            <div class="text-muted small">
              Auto-saved at <?php echo date("g:i A"); ?>
            </div>
          </div>
        </form>
      </div>
    </div>
    
    <!-- Sent Mails Table Card -->
    <div class="card">
      <div class="card-header">
        <h4 class="mb-0">Sent Mails</h4>
      </div>
      <div class="card-body">
        <?php
        // Query the sent_mails table to display previously sent emails
        $result = $conn->query("SELECT * FROM sent_mails ORDER BY send_date DESC");
        if ($result->num_rows > 0) {
            echo '<div class="table-responsive">';
            echo '<table class="table table-striped table-hover">';
            echo '<thead class="table-light"><tr><th>ID</th><th>Subject</th><th>Recipients</th><th>Message</th><th>Sent Date</th></tr></thead><tbody>';
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                echo "<td>" . htmlspecialchars($row['recipients']) . "</td>";
                echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                echo "<td>" . $row['send_date'] . "</td>";
                echo "</tr>";
            }
            echo '</tbody></table>';
            echo '</div>';
        } else {
            echo "<p>No sent mails found.</p>";
        }
        ?>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
  