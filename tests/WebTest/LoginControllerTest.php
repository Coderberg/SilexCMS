<?php

use Silex\WebTestCase;

/**
 * Test of LoginControllerTest
 *
 * @author Coderberg
 */
class LoginControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = $this->createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/login');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form'));
    }
    
    public function testRedirectToLoginPage ()
    {
        $client = $this->createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/admin');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form'));
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
