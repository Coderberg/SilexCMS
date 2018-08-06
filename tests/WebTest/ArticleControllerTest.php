<?php

use Silex\WebTestCase;

/**
 * Test of ArticleController
 *
 * @author Coderberg
 */
class ArticleControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = $this->createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertContains('Latest articles', $crawler->filter('body')->text());
    }

    public function testArticle()
    {
        $client = $this->createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/articles/21.html');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertContains('Posted by', $crawler->filter('body')->text());
    }

    public function createApplication()
    {
        $app = require __DIR__.'/../../src/app.php';
        require __DIR__.'/../../config/dev.php';
        require __DIR__.'/../../src/controllers.php';
        $app['session.test'] = true;
        
        require __DIR__.'/../../src/routes.php';

        return $this->app = $app;
    }
}
