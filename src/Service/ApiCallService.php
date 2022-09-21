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
    private function getResponse($response): array
    {
        if ($response->getStatusCode() !== 200) {
            throw new \Exception("le système attrape une exception :" . $response->getStatusCode());
        }else{
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return $content;
        }
    }
    private function execute(array $paramsQuery): array
    {
        $response = $this->httpClient->request('GET', $this->baseUrl . $paramsQuery['slug'], [
            'query' => [
                'api_key' => $this->apiKey,
                'language' => $this->language,
                $paramsQuery['value'] => $paramsQuery['query'] ?? null],
            ]);
          
        $contentList = $this->getResponse($response);
       if(!empty($contentList)){
            $id = $contentList['results'][0]['id'];
            $response = $this->httpClient->request('GET', $this->baseUrl . 'movie/' . $id  . '/videos', [
                'query' => [
                    'api_key' => $this->apiKey,
                    'language' => 'en-US'],
            ]);
            $contentMovie = $this->getResponse($response);
            $contentList ['key'] = $contentMovie['results'][0]['key'];

            return $contentList;
            }
    } 
   private function searchMovies(array $paramsQuery): array
    {
        $paramsQuery ['slug'] = self::SEARCH;
        $paramsQuery ['value'] = 'query' ;
        return $this->execute($paramsQuery);
    }
    private function discoverMovies(array $paramsQuery): array
    {
       $paramsQuery ['slug'] = self::DISCOVER;
       $paramsQuery ['value'] = 'with_genres' ;
        return $this->execute($paramsQuery);
    }
    private function getAllMovies(array $paramsQuery): array
    {
        $paramsQuery ['slug'] = self::POPULAR;
        $paramsQuery ['value'] = null ;
        return $this->execute($paramsQuery);
    }
    private function serchMovieById(array $paramsQuery): array
    {
       $response = $this->httpClient->request('GET', $this->baseUrl . 'movie/' . $paramsQuery ["query"] . '/' . $paramsQuery ["param"] ,[
            'query' => [
                'api_key' => $this->apiKey,
                'language' => 'language=en-US'
                ],
            ]); 
        $content = $this->getResponse($response); 
        return $content;
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
