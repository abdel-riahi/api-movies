<?php
// src/Services/ApiGenreService.php
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
        try{
    $response = $this->httpClient->request('GET', $this->baseUrl . 'genre/movie/list?&api_key=' . $this->apiKey);
    $contentType = $response->getHeaders()['content-type'][0];
    $content = $response->getContent();
    return $response->toArray();
    }catch(\Exception $ex){
        if ($response->getStatusCode() !== 200) {
            dd("erreur dans" . $ex->getFile() . "dans " . $ex->getLine() .":". $ex->getMessage());
            exit;    
        } 
    
   
   }
}
}