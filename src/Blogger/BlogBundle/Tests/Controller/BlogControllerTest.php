<?php
/**
 * Created by PhpStorm.
 * User: sprudnikov
 * Date: 06.12.2016
 * Time: 18:56
 */

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    public function testAddBlogComment()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/1/a-day-with-symfony');

        $this->assertEquals(1, $crawler->filter('h2:contains("A day with Symfony2")')->count());

        $form = $crawler->selectButton('Submit')->form();

        $crawler = $client->submit($form, array(
            'blogger_blogbundle_comment[user]' => 'name',
            'blogger_blogbundle_comment[comment]' => 'comment',
            ));
        $crawler = $client->followRedirect();
        $articleCrawler = $crawler->filter('section .previous-comments article')->last();

        $this->assertEquals('name', $articleCrawler->filter('header span.highlight')->text());
        $this->assertEquals('comment', $articleCrawler->filter('p')->last()->text());

        $this->assertEquals(10, $crawler->filter('aside.sidebar section')
                                        ->last()
                                        ->filter('article')
                                        ->count());

        $this->assertEquals('name', $crawler->filter('aside.sidebar section')
                                            ->last()
                                            ->filter('article')
                                            ->first()
                                            ->filter('header span.highlight')
                                            ->text());
    }
}
