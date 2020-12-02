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
if (trim($request->data) === "")
    exit(json_encode(["success" => false, "reason" => "Not a post data"]));

$folder = "/app/uploads/images/".$request->name."/";
$datafol = "/app/uploads/images/data";
$homeFolder = $_SERVER['DOCUMENT_ROOT'] . $folder; 
$destinationFolder = $_SERVER['DOCUMENT_ROOT'] . $folder."people/"; // you may need to adjust to your server configuration
$dataFolder = $_SERVER['DOCUMENT_ROOT'] . $datafol; // you may need to adjust to your server configuration

// $success = True;
shell_exec("mkdir $homeFolder 2>&1");
shell_exec("mkdir $destinationFolder 2>&1");
// $retval = shell_exec("ls ".$_SERVER['DOCUMENT_ROOT']);

$file = $request->data;

// getimagesize is used to get the file extension
// Only png / jpg mime types are allowed
$size = getimagesize($file);
$ext = $size['mime'];
if ($ext == 'image/jpeg')
    $ext = '.jpg';
elseif ($ext == 'image/png')
    $ext = '.png';
else
    exit(json_encode(['success' => false, 'reason' => 'only png and jpg mime types are allowed']));

// Prevent the upload of large files
if (strlen(base64_decode($file)) > $maxFileSize)
    exit(json_encode(['success' => false, 'reason' => "file size exceeds {$maxFileSize} Mb"]));

// Remove inline tags and spaces
$img = str_replace('data:image/png;base64,', '', $file);
$img = str_replace('data:image/jpeg;base64,', '', $img);
$img = str_replace(' ', '+', $img);

// Read base64 encoded string as an image
$img = base64_decode($img);

// Give the image a unique name. Don't forget the extension
// $filename = date("d_m_Y_H_i_s") . "-" . time() . $ext;
$filename = $request->name ."11". $ext;

// The path to the newly created file inside the upload folder
$destinationPath = "$destinationFolder$filename";

// Create the file or return false
$success = file_put_contents($destinationPath, $img);
// $retval = shell_exec("./process.sh $destinationFolder $dataFolder $homeFolder 2>&1");
// $retval = shell_exec("touch $homeFolder"."pics.zip");
$retval = $retval . shell_exec("ls $homeFolder");
// $retval = shell_exec('ls ../');
// // $retval =  "Current user is: " . get_current_user()."\n";

if (!$success)
    exit(json_encode(['success' => false, 'reason' => "$filename"]));

// Inform the browser about the path to the newly created image
exit(json_encode(['success' => true, 'path' => "$folder"."people/"."$filename", 'ls' => "$retval"]));
// exit(json_encode(['success' => false, 'reason' => "hehe$success"]));

