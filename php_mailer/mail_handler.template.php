<?php
require_once('email_config.php');
require('phpmailer/PHPMailer/PHPMailerAutoload.php');

$message = [];
$output = [
    'success'=> null,
    'messages'=>[]
];
//santizing the name field 
$message['name'] = filter_var($_POST['contactName'], FILTER_SANITIZE_STRING);
    if(empty($message['name'])) {
        $output['success'] = false;
        $output['messages'][] = 'missing name key';
    }
//validate email address
$message['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
if(empty($message['email'])) {
    $output['success'] = false;
    $output['messages'][] = 'invalid email key';
}
// Sanitize message
$message['comments'] = filter_var($_POST['comments'], FILTER_VALIDATE_EMAIL);
if(empty($message['comments'])) {
    $output['success'] = false;
    $output['messages'][] = 'missing message key';
}
if ($ouput['success'] !== null) {
    http_response_code(422);
    echo json_ecode($output);
    exit(); 
}

$mail = new PHPMailer;
$mail->SMTPDebug = 3;           // Enable verbose debug output. Change to 0 to disable debugging output.

$mail->isSMTP();                // Set mailer to use SMTP.
$mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers.
$mail->SMTPAuth = true;         // Enable SMTP authentication


$mail->Username = EMAIL_USER;   // SMTP username
$mail->Password = EMAIL_PASS;   // SMTP password
$mail->SMTPSecure = 'tls';      // Enable TLS encryption, `ssl` also accepted, but TLS is a newer more-secure encryption
$mail->Port = 587;              // TCP port to connect to
$options = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
$mail->smtpConnect($options);
$mail->From = 'example@gmail.com';  // sender's email address (shows in "From" field)
$mail->FromName = 'Example Name';   // sender's name (shows in "From" field)
$mail->addAddress('recipient1@example.com', 'First Recipient');  // Add a recipient
//$mail->addAddress('ellen@example.com');                        // Name is optional
$mail->addReplyTo('example@gmail.com');                          // Add a reply-to address
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    $output['success'] = false;
	$ouput['messages'][] = $mail->ErrorInfo;
} else {
    $output['success'] = true;
    echo json_encode($output);
}
?>
