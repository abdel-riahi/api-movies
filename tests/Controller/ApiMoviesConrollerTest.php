<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiMoviesControllerTest extends WebTestCase
{   
    public function TestapiMovies(){
        $client = static::createClient();
        $client->request('GET','/api/post/movies');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    public function TestSearchMovieWithBedCredentials(){
        $client = static::createClient();
        $crowler = $client->request('GET','/api/post/movies');
        $form = $crowler->selectButton('Rechercher')->form([
            'query' => 'The Godfather'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/api/post/movies');
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    public function testgetMovieById(){
        $client = static::createClient();
        $client->request('GET','/api/post/movie/35');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    public function testgetMovieGenre(){
        $client = static::createClient();
        $client->request('GET','/api/post/movies_genre/1000');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    } 
}

