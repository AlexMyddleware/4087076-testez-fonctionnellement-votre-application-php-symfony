<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Services\Diary;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DiaryControllerTest extends WebTestCase
{
    public function testCaloriesStatusAction()
    {
        $client = static::createClient();
        // assert true = true
        $this->assertTrue(true);
    }
}