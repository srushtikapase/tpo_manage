<?php
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Autoload the PHPMailer files

if (isset($_POST['send'])) {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'email_db');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch emails from the database
    $sql = "SELECT email FROM email_list";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // SMTP server settings
            $mail->isSMTP();    
            $mail->Host = 'smtp.gmail.com';               // Set the SMTP server
            $mail->SMTPAuth = true;                       // Enable SMTP authentication
            $mail->Username = 'testmailsgu@gmail.com';     // Your Gmail address
            $mail->Password = 'zzxq uxru tgai tgkh';      // Gmail password or App password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port = 587;                            // TCP port to connect to

            // Email settings
            $mail->setFrom('testmailsgu@gmail.com', 'SGU TPO Department'); // Sender's email and name

            // Loop through email results and send the emails
            while ($row = $result->fetch_assoc()) {
                $to = $row['email'];

                // Add recipient
                $mail->addAddress($to);  

                // Email content
                $mail->isHTML(true);                                 // Set email format to HTML
                $mail->Subject = 'Notification from Our Website';   // Email subject
                $mail->Body    = 'Hello, this is a test email from our website.'; // HTML message body
                $mail->AltBody = 'Hello, this is a test email from our website.'; // Plain text message body

                // Send the email
                if ($mail->send()) {
                    echo "Email sent successfully to: " . $to . "<br>";
                } else {
                    echo "Failed to send email to: " . $to . "<br>";
                }

                // Clear recipient for next iteration
                $mail->clearAddresses();
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No emails found in the database.";
    }

    // Close the database connection
    $conn->close(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Emails</title>
</head>
<body>

<h2>Send Email to All Contacts</h2>
<form method="POST" action="mail.php">
    <input type="submit" name="send" value="Send Emails">
</form>

</body>
</html>
