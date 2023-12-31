<?php

require "vendor/autoload.php";

use Alura\BuscadorDeCursos\Buscador;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client(["base_uri" => "https://alura.com.br/"]);
$crawler = new Crawler();

$buscador = new Buscador($client, $crawler);

$cursos = $buscador->buscar();

foreach ($cursos as $curso) {
    echo $curso . PHP_EOL;
}
