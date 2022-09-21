<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ApiCallService;
use App\Service\ApiGenreService;
use Symfony\Component\HttpFoundation\Request;

class ApiMoviesController extends AbstractController
{
    private $apiService;
    private $apiGenreService;
    public function __construct(ApiCallService $apiService, ApiGenreService $apiGenreService)
    {
    $this->apiService = $apiService ;
    $this->apiGenreService = $apiGenreService ;
    }
    #[Route('/api/post/movies', name: 'api_movies')]
    public function apiMovies(Request $request): Response
    {
        $requestQuery = $request->query->get('query');
        $param = !empty($requestQuery) ? 'search' : null;
        $query = $requestQuery ?? null;
        $result =  $this->willCall($query, $param);
        return $this->render('api_movies/movies.html.twig', [
        'genre' =>$result["genre"]["genres"], 
        'movies' =>$result["movies"]["results"],
        'key' => $result["movies"]["key"]
        ]);
    }
    #[Route('/api/post/movie/{id}', name: 'view_movie')] 
    public function apiMovieDetails(int $id): Response
    {
        $param = 'videos';
        $result =  $this->willCall($id, $param);
        return $this->render('api_movies/view_movie.html.twig', [
        "movie" =>$result["movies"]["results"][0],   
        ]);
    }
    #[Route('/api/post/movies_genre/{id}', name: 'view_movie_genre')]
    public function apiMoviesSerchyGenre(int $id): Response
    {
        $param = "discover";
        $result =  $this->willCall($id, $param);
        return $this->render('api_movies/movies_genre.html.twig', [
        "movies" =>$result["movies"]["results"],
        "key" =>$result["movies"]["key"]
        ]);
    }
    private function willCall($query, $param) : array
    {
       $paramsQuery = ["query" => $query, "param" => $param];
       $movies = $this->apiService->callApi($paramsQuery);
       $genre = $this->apiGenreService->callApiGenre();
        return [ "movies" =>  $movies , "genre"=> $genre];
    }
   
}


