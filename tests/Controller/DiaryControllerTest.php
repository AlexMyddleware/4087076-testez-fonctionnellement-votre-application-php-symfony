<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Services\Diary;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DiaryControllerTest extends WebTestCase
{
    private $client;
    private $userRepository;
    private $user;
    private $urlGenerator;

    public function setUp() : void

     {
        $this->client = static::createClient();

        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        $this->user = $this->userRepository->findOneByEmail('richard11@dbmail.com');

        $this->urlGenerator = $this->client->getContainer()->get('router.default');

        $this->client->loginUser($this->user);
   }

     public function testHomepageIsUp()

     {
          $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('homepage'));

          $this->assertResponseStatusCodeSame(Response::HTTP_OK);

          //    Crawler
          $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('homepage'));
          $this->assertSame(1, $crawler->filter('html:contains("Bienvenue sur FoodDiary!")')->count());

          $this->assertSame(1, $crawler->filter('h1')->count());

     }
}
