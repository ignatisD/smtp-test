<?php
require_once "vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer();
$mail->IsSMTP();

$mail->Host = $_ENV["SMTP_HOST"];
$mail->Port = $_ENV["SMTP_PORT"];
if (!empty($_ENV["SMTP_SECURE"])) {
    $mail->SMTPSecure = $_ENV["SMTP_SECURE"];
}
$mail->Timeout = 15;
$mail->SMTPAuth = !empty($_ENV["SMTP_PASSWORD"]);
if (!empty($_ENV["SMTP_PASSWORD"])) {
    $mail->Username = $_ENV["SMTP_USERNAME"];
    $mail->Password = $_ENV["SMTP_PASSWORD"];
}
$mail->SMTPDebug = !empty($_ENV["SMTP_DEBUG"]) ? $_ENV["SMTP_DEBUG"] : 0;
$mail->SMTPOptions = ["ssl" => ["verify_peer" => false, "verify_peer_name" => false, "allow_self_signed" => true]];

$mail->From      = $_ENV["SMTP_USERNAME"];
$mail->FromName  = "Email Tester";
$mail->Subject   = "SMTP Email testing";

$mailSendTo = $_ENV["SMTP_TESTER"];
$baseEmailSendTo = base64_encode($mailSendTo);

$mail->msgHTML("<h1>Hello world!</h1>");
$mail->AddAddress($mailSendTo);
$mail->IsHTML(true);
$mail->CharSet = 'UTF-8';
try {
    if ($mail->Send()) {
        echo "Mail send successfully!";
    } else {
        echo $mail->ErrorInfo;
    }
}catch(Exception $e) {
    echo $e->getMessage();
}
echo PHP_EOL;