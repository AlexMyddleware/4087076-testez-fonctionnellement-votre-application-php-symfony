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
        $this->client->followRedirects();

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

     public function testRecordAction(){
               
               $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('add-new-record'));

               // var_dump($this->client->getResponse()->getContent());
     
               $this->assertResponseStatusCodeSame(Response::HTTP_OK);
     
               $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('add-new-record'));
     
               $this->assertSame(1, $crawler->filter('h1')->count());

               $form = $crawler->selectButton('Enregistrer')->form();

               $form['food[entitled]'] = 'Plat de pâtes';
               $form['food[calories]'] = 600;

               $this->client->submit($form);
               // $this->client->followRedirect();

               // thouroughly test the form
               $this->assertResponseStatusCodeSame(Response::HTTP_OK);

               // echo $this->client->getResponse()->getContent();

               $this->assertSelectorTextContains('div.alert.alert-success','Une nouvelle entrée dans votre journal a bien été ajoutée');

     
     }

     public function testList()
     {
          // On se connecte
          $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('diary'));

          // echo $crawler->html();
          // save the content of crawler->html() in a file
          // file_put_contents('tests/Controller/list.html', $crawler->html());

          // On sélectionne le lien "Voir tous mes rapports"
          $link = $crawler->selectLink('Voir tous les rapports')->link();
          // On clique sur le lien
          $crawler = $this->client->click($link);

          $unfilteredHtml = $crawler->html();

          // echo $unfilteredHtml;
          // On vérifie cherche le titre de la page
          $info = $crawler->filter('h1')->text();

          // On retire les retours à la ligne pour faciliter la vérification
          $info = $string = trim(preg_replace('/\s\s+/', ' ', $info));
          // On vérifie que le titre est bien celui attendu
          $this->assertSame("Tous les rapports Tout ce qui a été mangé !", $info);
     }
}
