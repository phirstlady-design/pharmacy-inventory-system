
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = htmlspecialchars($_POST['fullname']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'phirstlady1.fm@gmail.com'; // Your Gmail address
        $mail->Password = 'obql akfi vcwa pzcu'; // Your app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use 'tls' or PHPMailer::ENCRYPTION_STARTTLS
        $mail->Port = 587;

        // Recipients
        $mail->setFrom($email, $fullname);
        $mail->addAddress('phirstlady1.fm@gmail.com');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Contact Form: ' . $subject;
        $mail->Body    = "You have received a new message from your website contact form.<br><br>" .
                         "Here are the details:<br><br>" .
                         "Full Name: $fullname<br>" .
                         "Email: $email<br>" .
                         "Phone: $phone<br>" .
                         "Subject: $subject<br>" .
                         "Message:<br>$message<br>";

        $mail->send();
        echo 'Message sent successfully!';
    } catch (Exception $e) {
        echo "Failed to send message. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo 'Invalid request.';
}
?>
