<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$host = 'localhost';
$dbname = 'applicants';  // Replace with your database name
$username = 'root';  // Default XAMPP MySQL username
$password = '';  // Default XAMPP MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ensure the uploads directory exists
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Handle file upload for photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photoPath = 'uploads/' . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
    } else {
        $photoPath = null;
    }

    // Handle file upload for signature
    if (isset($_FILES['signature']) && $_FILES['signature']['error'] == 0) {
        $signaturePath = 'uploads/' . basename($_FILES['signature']['name']);
        move_uploaded_file($_FILES['signature']['tmp_name'], $signaturePath);
    } else {
        $signaturePath = null;
    }

    // Insert form data into the database
    $stmt = $pdo->prepare("INSERT INTO applicants (full_name, email, phone, qualification, photo, signature) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['fullName'], $_POST['email'], $_POST['phone'], $_POST['qualification'], $photoPath, $signaturePath]);

    // Send an email
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'chawanvin@gmail.com';  // Replace with your SMTP username
        $mail->Password = 'cpig hmpy moah meut';  // Replace with your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('chawanvin@gmail.com', 'Application form demo');
        $mail->addAddress($_POST['email'], $_POST['fullName']);

        // Attachments (optional)
        if ($photoPath) {
            $mail->addAttachment($photoPath);  // Attach the uploaded photo
        }
        if ($signaturePath) {
            $mail->addAttachment($signaturePath);  // Attach the uploaded signature
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Application Received';
        $mail->Body    = "<p>Dear {$_POST['fullName']},</p><p>Thank you for your application. We have received your details and will get back to you shortly.</p>";
        $mail->AltBody = "Dear {$_POST['fullName']},\n\nThank you for your application. We have received your details and will get back to you shortly.";

        $mail->send();
        echo "<p style='color: green; font-weight: bold;'>Your application has been submitted successfully and a confirmation email has been sent!</p>";
    } catch (Exception $e) {
        echo "<p style='color: red; font-weight: bold;'>Application submitted, but email could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
    }

} catch (PDOException $e) {
    echo 'Database Error: ' . $e->getMessage();
}
?>
