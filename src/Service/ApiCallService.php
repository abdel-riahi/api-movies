<?php
namespace App\Service;

use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ApiCallService
{
    const DISCOVER = 'discover/movie';
    const SEARCH = 'search/movie';
    const POPULAR = 'movie/popular';

    private $httpClient;
    private $parameters; 

    public function __construct(ParameterBagInterface $parameters, HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->parameters = $parameters;
        $this->apiKey = $this->parameters->get('app.apikey');
        $this->baseUrl = $this->parameters->get('app.baseUrl');
        $this->language = $this->parameters->get('app.language');
    }

    private function getResponse($url){

        try{
        return $this->httpClient->request('GET',  $url)->toArray();
        }catch (\Exception $ex) {
        dd("erreur dans" . $ex->getFile() . "dans " . $ex->getLine() .":". $ex->getMessage());
        exit;        
    }
    }

    private function execute(array $paramsQuery)
    {
            if ($paramsQuery["slug"] === "movie/popular")
        {
            $listMovies = $this->getResponse($this->baseUrl . $paramsQuery['slug'] . '?api_key=' . $this->apiKey);
          if(!empty($listMovies)){
            $response = $listMovies;
            $movie = $this->getResponse($this->baseUrl . 'movie/' . $response['results'][0]['id']   . '/videos?api_key=' . $this->apiKey);
            $response ['key'] = $movie['results'][0]['key'];
            return $response ;
          }
        }else{
            $response = $this->getResponse($this->baseUrl . $paramsQuery['slug'] . '?api_key=' . $this->apiKey);
            $response = $response;
            $response ['key'] = null;
            return $response;        
    }
}
   private function searchMovies(array $paramsQuery): array
    {
        $paramsQuery ['slug'] = self::SEARCH;
        $paramsQuery ['query'] = '&query=' . $paramsQuery["query"];
        return $this->execute($paramsQuery);
    }
    private function discoverMovies(array $paramsQuery): array
    {
       $paramsQuery ['slug'] = self::DISCOVER;
       $paramsQuery ['query'] = '&with_genres=' . $paramsQuery["query"];
        return $this->execute($paramsQuery);
    }
    private function serchMovieById(array $paramsQuery): array
    {
       $paramsQuery['slug'] ='movie/' . $paramsQuery ["query"] . '/' . $paramsQuery ["param"] ;
       return $this->execute($paramsQuery);
    }
    private function getAllMovies(array $paramsQuery): array
    {
        $paramsQuery ['slug'] = self::POPULAR;
        return $this->execute($paramsQuery);
    }
    
    /**
     * @param $parameters  array of parameters : baseUrl, laguageUsed, query, param
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function callApi(array $paramsQuery): array
    {
       
        switch ($paramsQuery["param"]) {
            case 'search':
                $response = $this->searchMovies($paramsQuery);
                break;
                case 'discover':
                $response = $this->discoverMovies($paramsQuery);
                break;
                case 'videos':
                $response = $this->serchMovieById($paramsQuery);
                break;
            default:
                $response = $this->getAllMovies($paramsQuery);
                break;
        }
        return $response;
    }
}