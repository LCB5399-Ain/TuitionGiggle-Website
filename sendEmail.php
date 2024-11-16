<?php

/**
 * This example shows how to send a message to a whole list of recipients efficiently.
 */
// Code adapted from Basic_Code, 2023
 $title = isset($_POST['title']) ? $_POST['title'] : '';
 $announcement = isset($_POST['announcement']) ? $_POST['announcement'] : '';

//Import the PHPMailer class into the global namespace
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/Exception.php';
require 'PHPMailer/SMTP.php';

error_reporting(E_STRICT | E_ALL);

date_default_timezone_set('Etc/UTC');

require 'vendor/autoload.php';

//Passing `true` enables PHPMailer exceptions
$mail = new PHPMailer\PHPMailer\PHPMailer();

$body = file_get_contents('insert_data/insert_announcement.php');
$mail->msgHTML($body);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->SMTPKeepAlive = true; //SMTP connection will not close after each email sent, reduces SMTP overhead
$mail->Port = 587;
$mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
$mail->Username = 'tuitiongiggle@gmail.com';
$mail->Password = 'Tuition_Giggle2024';
$mail->setFrom('tuitiongiggle@gmail.com', 'Tuition Giggle');
$mail->addReplyTo('tuitiongiggle@gmail.com', 'Tuition Giggle');

$mail->Subject = $title;
$mail->Body = $announcement;

//$mail->Subject = 'PHPMailer Simple database mailing list test';

//Same body for all messages, so set this before the sending loop
//If you generate a different body for each recipient (e.g. you're using a templating system),
//set it inside the loop
//$mail->msgHTML($body);
//msgHTML also sets AltBody, but if you want a custom one, set it afterwards
//$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';

//Connect to the database and select the recipients from your mailing list that have not yet been sent to
//You'll need to alter this to match your database
$mysql = mysqli_connect('localhost', 'root', '');
mysqli_select_db($mysql, 'tuitioncentre');
$result = mysqli_query($mysql, "SELECT fullName, phoneNumber, email, address FROM parents WHERE email IS NOT NULL AND email != ''");

foreach ($result as $row) {
    try {
        $mail->addAddress($row['email'], $row['fullName']);
    } catch (Exception $e) {
        echo 'Invalid address skipped: ' . htmlspecialchars($row['email']) . '<br>';
        continue;
    }

    try {
        $mail->send();
        echo 'Message sent to :' . htmlspecialchars($row['fullName']) . ' (' .
            htmlspecialchars($row['email']) . ')<br>';
        //Mark it as sent in the DB
        // mysqli_query(
        //     $mysql,
        //     "UPDATE parents SET email = TRUE WHERE email = '" .
        //     mysqli_real_escape_string($mysql, $row['email']) . "'"
        // );
    } catch (Exception $e) {
        echo 'Mailer Error (' . htmlspecialchars($row['email']) . ') ' . $mail->ErrorInfo . '<br>';
        //Reset the connection to abort sending this message
        //The loop will continue trying to send to the rest of the list
        $mail->getSMTPInstance()->reset();
    }
    //Clear all addresses and attachments for the next iteration
    $mail->clearAddresses();
    $mail->clearAttachments();
}

// End of adapted code