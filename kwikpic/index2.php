<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';

// require '../vendor/autoload.php';
echo "<html><head></head><body>HI";


$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = 2;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'kushalagarwal.u@gmail.com';                     // SMTP username
    $mail->Password   = 'Jaldibhej9';                               // SMTP password
    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('kushalagarwal.u@gmail.com', 'Mailer');
    $mail->addAddress('deepkarkhanis@gmail.com', 'Joe User');     // Add a recipient
    // $mail->addAddress('ellen@example.com');               // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment($homefolder."pics.zip", $request->name."_kwikpics.zip");    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Kwikpic.in : Your Event Photos';
    $mail->Body    = "Thank You for using <b>KwikPic</b>. <br> Please find your photos attached with this mail. <br> <br>Our Artificial Intelligence software uses modern face recognition technology to identify photos in which you are present.";
    $mail->AltBody = "Thank You for using KwikPic. Please find your photos attached along with this mail. Our Artificial Intelligence software uses modern face recognition algorithms to identify photos in which you are present.";

    $mail->send();
    echo 'Message has been sent';

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
