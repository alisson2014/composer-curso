<?php

namespace Alura\BuscadorDeCursos;

use GuzzleHttp\ClientInterface;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * class Buscador
 * @package Alura\BuscadorDeCursos
 * @property ClientInterface $httpClient
 * @property Crawler $crawler
 * @method array pedirEscola()
 * @method array pedirTech()
 * @method array buscar()
 */
class Buscador
{
    public function __construct(
        private ClientInterface $httpClient,
        private Crawler $crawler
    ) {
    }

    private function pedirEscola(): array
    {
        $retorno = [];
        $schools = [
            "Programacão - 0",
            "Front-end - 1",
            "Data-Science - 2",
            "Devops - 3",
            "Mobile - 4"
        ];

        echo implode(", ", $schools) . PHP_EOL;

        $school = readline("Digite o numero da escola: ");

        switch ($school) {
            case 0:
                $retorno = ["0", "java", "python", "php"];
                break;
            case 1:
                $retorno = ["1", "javascript", "reactjs", "vuejs"];
                break;
            case 2:
                $retorno = ["2", "sql", "data-science", "nosql"];
                break;
            case 3:
                $retorno = ["3", "linux-comandos-e-processos", "seguranca", "redes"];
                break;
            case 4:
                $retorno = ["4", "react-native", "flutter-mobile", "ios"];
                break;
            default:
                throw new InvalidArgumentException("Curso inválido!");
        }

        return $retorno;
    }

    private function pedirTech(): array
    {
        $schoolUser = $this->pedirEscola();
        $school = $schoolUser[0];
        $arrayTechs = array_slice($schoolUser, 1);

        echo implode(", ", $arrayTechs) . PHP_EOL;

        $tech = readline("Digite a tecnologia: ");

        if ($school == 0) $school = "programacao";
        elseif ($school == 1) $school = "front-end";
        elseif ($school == 2) $school = "data-science";
        elseif ($school == 3) $school = "devops";
        else $school = "mobile";

        return [
            "school" => $school,
            "tech" => strtolower($tech)
        ];
    }

    public function buscar(): array
    {
        $retorno = $this->pedirTech();

        $url = "/cursos-online-{$retorno['school']}/{$retorno['tech']}";
        $response = $this->httpClient->request("GET", $url);

        $html = $response->getBody();
        $this->crawler->addHtmlContent($html);

        $elementosCursos = $this->crawler->filter("span.card-curso__nome");

        $cursos = [];

        foreach ($elementosCursos as $elemento) {
            $cursos[] = $elemento->textContent;
        }

        return $cursos;
    }
}
