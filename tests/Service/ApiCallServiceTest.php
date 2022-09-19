<?php
namespace App\Tests\Service;

use App\Service\ApiCallService;
use App\Service\ApiGenreService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiCallServiceTest extends WebTestCase
{
    
 
   /** @test */
   public function willCallServiceGenre()
   {
       $response = [1 => "foo", 2 => 'bar' ];
       $client = static::createClient();
       $container = self::$container;
       $CallApiServiceMock = $this->getMockBuilder(ApiGenreService::class)
           ->disableOriginalConstructor()
           ->disableOriginalClone()
           ->onlyMethods(['callApiGenre'])
           ->getMock();
       $CallApiServiceMock->method('callApiGenre')->willReturn($response);
       $container->set('App\Service\ApiGenreService', $CallApiServiceMock);
       $client->request('GET', '/api/post/movies');
       $this->assertSame($response , $CallApiServiceMock->callApiGenre());


   }


    /** @test */
    public function willCallServiceMovies()
    {
        $params = [1 => "param_1", 2 => 'param_2' ];
        $response =[1 => "foo", 2 => 'bar' ];
        $serviceMock = $this->createMock(ApiCallService::class);
        $serviceMock 
        ->expects(self::once())
        ->method('callApi')
        ->with($params)
        ->willReturn($response);
        $this->assertSame( $response , $serviceMock->callApi($params));
    }
    
}

