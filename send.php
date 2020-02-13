<?php
require 'vendor/autoload.php';


const FILE_TESTES = 'csv/testes.csv';
const FILE_EGRESSOS_CURSOS = 'csv/egressos-cursos.csv';
const FILE_EGRESSOS_RESIDENCIA_MEDICA = 'csv/egressos-res-medica.csv';
const FILE_EGRESSOS_RESIDENCIA_MULTIPROFISSIONAL = 'csv/egressos-res-multiprofissional.csv';

$listasHabilitadasParaEnvio = array(FILE_TESTES);
$emails = [];


$keyJson = file_get_contents('key.json');
$key = json_decode($keyJson, true);

foreach ($listasHabilitadasParaEnvio as $lista) {
    $file = fopen($lista, 'r');
    while (($line = fgetcsv($file)) !== FALSE) {
        $emails[] = $line[0];
    }
}

$mensagem = "
    <html>
    <head>
        <title></title>
    </head>
    <body>
         <a href='https://docs.google.com/forms/d/e/1FAIpQLScHapzRfg1zeUwB_agQbt1NboUvDIeGNrYdZjwaswiRL96mFw/viewform'>
            <img src='http://academico.esp.ce.gov.br/miolo20/html/images/banner.png' />
         </a>
    </body>
    </html>
";
foreach ($emails as $emailDestino) {

    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("pesquisa.egressos@esp.ce.gov.br", "Escola de Saúde Pública do Ceará");
    $email->setSubject("Pesquisa Egressos");
    $email->addTo($emailDestino);
    $email->addContent(
        "text/html", $mensagem
    );

    $sendgrid = new \SendGrid($key['SENDGRID_API_KEY']);
    try {
        $response = $sendgrid->send($email);
        print $response->statusCode() . "\n";
        print_r($response->headers());
        print $response->body() . "\n";
    } catch (Exception $e) {
        echo 'Caught exception: '. $e->getMessage() ."\n";
    }
}
