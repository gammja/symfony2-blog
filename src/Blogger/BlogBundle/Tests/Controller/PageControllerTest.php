<?php
/**
 * Created by PhpStorm.
 * User: sprudnikov
 * Date: 06.12.2016
 * Time: 17:15
 */

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testAbout()
    {
        $clent = static::createClient();

        $crawler = $clent->request('GET', '/about');

        $this->assertEquals(1, $crawler->filter('h1:contains("About symblog")')->count());
    }

    public function testIndexWithoutClick()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('article.blog')->count() > 0);
    }

    public function testIndexWithClick()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $blogLink = $crawler->filter('article.blog h2 a')->first();
        $blogTitle = $blogLink->text();
        $crawler = $client->click($blogLink->link());

        $this->assertEquals(1, $crawler->filter("h2:contains('$blogTitle')")->count());
    }

    public function testContact()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');
        $this->assertEquals(1, $crawler->filter('h1:contains("Contact symblog")')->count());
        $form = $crawler->selectButton('Submit')->form();
        $form['contact[name]'] = 'name';
        $form['contact[email]'] = 'email@mail.com';
        $form['contact[subject]'] = 'Subject';
        $form['contact[body]'] = 'The comment body must be at least 50 characters long as there
         is a validation constrain on the Enquiry entity';

        $crawler = $client->submit($form);

        if ($profile = $client->getProfile())
        {
            $swiftMailerProfiler = $profile->getCollector('swiftmailer');
            $this->assertEquals(1, $swiftMailerProfiler->getMessageCount());

            $messages = $swiftMailerProfiler->getMessages();
            $message = array_shift($messages);

            $email = $client->getContainer()->getParameter('blog.emails.contact_email');
            $this->assertArrayHasKey($email, $message->getTo());
        }

        $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler
            ->filter('.blogger-notice:contains("Your contact enquiry was successfully sent. Thank you!")')->count());
    }
}
