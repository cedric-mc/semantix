<?php
if(!file_exists('PHPMailer/src/Exception.php')) exit("Le fichier 'PHPMailer/src/Exception.php' n'existe pas !");

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Server settings
$mail = new PHPMailer(true);
$mail->isSMTP();                                            //Send using SMTP
$mail->Host       = '';                     //Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
$mail->Username   = '';                     //SMTP username
$mail->Password   = '';                               //SMTP password
$mail->SMTPSecure = '';            //Enable implicit TLS encryption
$mail->Port       = '';                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
?>