<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

$maxFileSize = 2 * 1024 * 1024;
// Get the posted data
$postdata = file_get_contents("php://input");
if (!isset($postdata) || empty($postdata))
    exit(json_encode(["success" => false, "reason" => "Not a post data"]));
// Extract the data
$request = json_decode($postdata);
// Validate
// if (trim($request->data) === "")
//     exit(json_encode(["success" => false, "reason" => "Not a post data"]));

$folder = "/app/uploads/images/". $request->name."/";
$datafol = "/app/uploads/images/data/";
$homeFolder = $_SERVER['DOCUMENT_ROOT'] . $folder; 
$destinationFolder = $_SERVER['DOCUMENT_ROOT'] . $folder."people/"; // you may need to adjust to your server configuration
$dataFolder = $_SERVER['DOCUMENT_ROOT'] . $datafol; // you may need to adjust to your server configuration

shell_exec("mkdir $homeFolder 2>&1");
shell_exec("mkdir $destinationFolder 2>&1");

// $file = $request->data;

// // getimagesize is used to get the file extension
// // Only png / jpg mime types are allowed
// $size = getimagesize($file);
// $ext = $size['mime'];
// if ($ext == 'image/jpeg')
//     $ext = '.jpg';
// elseif ($ext == 'image/png')
//     $ext = '.png';
// else
//     exit(json_encode(['success' => false, 'reason' => 'only png and jpg mime types are allowed']));

// // Prevent the upload of large files
// if (strlen(base64_decode($file)) > $maxFileSize)
//     exit(json_encode(['success' => false, 'reason' => "file size exceeds {$maxFileSize} Mb"]));

// Remove inline tags and spaces
// $img = str_replace('data:image/png;base64,', '', $file);
// $img = str_replace('data:image/jpeg;base64,', '', $img);
// $img = str_replace(' ', '+', $img);

// Read base64 encoded string as an image
// $img = base64_decode($img);

// Give the image a unique name. Don't forget the extension
// $filename = date("d_m_Y_H_i_s") . "-" . time() . $ext;
// $filename = $request->name ."11". $ext;

// The path to the newly created file inside the upload folder
// $destinationPath = "$destinationFolder$filename";
// $success = file_put_contents($destinationPath, $img);
$success = true;

$retval = shell_exec("./compress/compress $destinationFolder $dataFolder $homeFolder 2>&1");
// $retval = shell_exec("touch $homeFolder"."pics.zip");
$retval = $retval . shell_exec("ls ./");
$retval = $retval . shell_exec("ls $homeFolder");

$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = 0;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'kushalagarwal.u@gmail.com';                     // SMTP username
    $mail->Password   = 'Jaldibhej9';                               // SMTP password
    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('kushalagarwal.u@gmail.com', 'KwikPic');
    $mail->addAddress("$request->email","$request->name");               // Name is optional
    $mail->addBCC('kushalagarwal.u@gmail.com', 'KwikPic');     // Add a recipient
    // $mail->addAddress('ellen@example.com');               // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    $mail->addAttachment($homeFolder."pics.zip", $request->name."_kwikpics.zip");    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Kwikpic.in : Your Event Photos';
    $mail->Body    = "Thank You for using <b>KwikPic</b>. <br> Please find your photos attached with this mail. <br> <br>Our Artificial Intelligence software uses modern face recognition technology to identify photos in which you are present.";
    $mail->AltBody = "Thank You for using KwikPic. Please find your photos attached along with this mail. Our Artificial Intelligence software uses modern face recognition algorithms to identify photos in which you are present.";

    $mail_ret = $mail->send();
    $retval = $retval . 'Message has been sent';

} catch (Exception $e) {
    $retval = $retval . "Message could not be sent. ";
    // Mailer Error: {$mail->ErrorInfo}
}

if (!$success)
    exit(json_encode(['success' => false, 'reason' => "$filename"]));

// Inform the browser about the path to the newly created image
exit(json_encode(['success' => true, 'path' => "$folder"."people/"."$filename", 'ls' => "$retval"]));
// exit(json_encode(['success' => false, 'reason' => "hehe$success"]));

