<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ApiGenreService
{
    private $httpClient;
    private $parameters;

    public function __construct(ParameterBagInterface $parameters, HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->parameters = $parameters;
        $this->apiKey = $this->parameters->get('app.apikey');
        $this->baseUrl = $this->parameters->get('app.baseUrl');
    }
    public function callApiGenre(): array
    {
    $response = $this->httpClient->request('GET', $this->baseUrl . 'genre/movie/list', [
        'query' => [
            'api_key' => $this->apiKey
            ]
         ]);
        if ($response->getStatusCode() !== 200) {
           throw new \Exception("le système attrape une exception :" . $response->getStatusCode());
        }else{
            $contentType = $response->getHeaders()['content-type'][0];
            $content = $response->getContent();
            $content = $response->toArray();
            return $content;
        }
    }
}
