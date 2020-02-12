<?php
require 'vendor/autoload.php'; 


const FILE_EGRESSOS_CURSOS = 'csv/testes.csv';
const FILE_EGRESSOS_CURSOS = 'csv/egressos-cursos.csv';
const FILE_EGRESSOS_RESIDENCIA_MEDICA = 'csv/egressos-res-medica.csv';
const FILE_EGRESSOS_RESIDENCIA_MULTIPROFISSIONAL = 'csv/egressos-res-multiprofissional.csv';

$emails = [];

$file = fopen(FILE_EGRESSOS_CURSOS, 'r');
while (($line = fgetcsv($file)) !== FALSE) {
   $emails[] = $line[0];
}

$file = fopen(FILE_EGRESSOS_RESIDENCIA_MEDICA, 'r');
while (($line = fgetcsv($file)) !== FALSE) {
   $emails[] = $line[0];
}

$file = fopen(FILE_EGRESSOS_RESIDENCIA_MULTIPROFISSIONAL, 'r');
while (($line = fgetcsv($file)) !== FALSE) {
   $emails[] = $line[0];
}

echo count($emails);
die;

$email = new \SendGrid\Mail\Mail(); 
$email->setFrom("victor.magalhaesp@esp.ce.gov.br", "Victor TESTE SendGrid");
$email->setSubject("Sending with SendGrid is Fun");
$email->addTo("victor.magalhaesp@gmail.com", "Victor ESP");
$email->addContent("text/plain", "Testando envio de email");
$email->addContent(
    "text/html", "<strong>Testando envio de email em PHP</strong>"
);

$key = getenv('SENDGRID_API_KEY');
$sendgrid = new \SendGrid($key);
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}