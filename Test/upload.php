<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadFile = $uploadDir . basename($_FILES['file']['name']);
    $name = $_POST['name'];
    $regNumber = $_POST['regNumber'];

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = '2ndyearaids@gmail.com'; // Your Gmail address
            $mail->Password = 'pbcsonugbaomgikp';       // Your Gmail app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('2ndyearaids@gmail.com', 'Document Upload');
            $mail->addAddress('gmars.gmr@gmail.com'); // Receiver's email address

            // Content
            $mail->isHTML(false);
            $mail->Subject = 'New Document Uploaded';
            $mail->Body = "Name: $name\nRegister Number: $regNumber\n\nFile: " . basename($uploadFile);

            $mail->send();
            echo json_encode(['message' => 'File uploaded and email sent successfully!']);
        } catch (Exception $e) {
            echo json_encode(['message' => 'File uploaded but email sending failed. Error: ' . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['message' => 'File upload failed.']);
    }
} else {
    echo json_encode(['message' => 'Invalid request method.']);
}
?>
