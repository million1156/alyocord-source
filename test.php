<?php
require 'vendor/autoload.php';

$email = new \SendGrid\Mail\Mail(); 
$email->setFrom("alyocord@funwithalbi.xyz", "Albi");
$email->setSubject("Verification test");
$email->addTo("albiyt@proton.me", "AlbiYT");
$email->addContent("text/plain", "yeah so this is just a test so uhm yeah i hope it works.");
$email->addContent(
    "text/html", "<strong>-- Albi from Alyocord</strong>"
);
$sendgrid = new \SendGrid(getenv('SENDGRID'));
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}
?>