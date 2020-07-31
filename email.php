<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* New aliases. */
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;

/* Composer autoload.php file includes all installed libraries. */
//require 'vendor/autoload.php';

/* If you installed league/oauth2-google in a different directory, include its autoloader.php file as well. */
// require 'C:\xampp\league-oauth2\vendor\autoload.php';

/* Set the script time zone to UTC. */
date_default_timezone_set('Etc/UTC');

/* Information from the XOAUTH2 configuration. */
$google_email = $_ENV['GMAIL_USER_ID'];
$oauth2_clientId = $_ENV['GMAIL_CLIENT_ID'];
$oauth2_clientSecret = $_ENV['GMAIL_CLIENT_SECRET'];
$oauth2_refreshToken = $_ENV['GMAIL_REFRESH_TOKEN'];

$mail = new PHPMailer(TRUE);

try {

    $mail->setFrom($google_email, 'Sender Name');
    $mail->addAddress('test@gmail.com', 'Sparsh Turkane');
    $mail->isHTML(true);
    $mail->Subject = 'Php emailer testing';
    $mail->Body = '<h1>Yea it is working</h1>';
    $mail->isSMTP();
    $mail->Port = 587;
    $mail->SMTPAuth = TRUE;
    $mail->SMTPSecure = 'tls';

    /* Google's SMTP */
    $mail->Host = 'smtp.gmail.com';

    /* Set AuthType to XOAUTH2. */
    $mail->AuthType = 'XOAUTH2';

    /* Create a new OAuth2 provider instance. */
    $provider = new Google(
        [
            'clientId' => $oauth2_clientId,
            'clientSecret' => $oauth2_clientSecret,
        ]
    );

    /* Pass the OAuth provider instance to PHPMailer. */
    $mail->setOAuth(
        new OAuth(
            [
                'provider' => $provider,
                'clientId' => $oauth2_clientId,
                'clientSecret' => $oauth2_clientSecret,
                'refreshToken' => $oauth2_refreshToken,
                'userName' => $google_email,
            ]
        )
    );

    /* Finally send the mail. */
    $mail->send();
}
catch (Exception $e)
{
    echo $e->errorMessage();
}
catch (\Exception $e)
{
    echo $e->getMessage();
}