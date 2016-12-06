<?php
/**
 * Created by PhpStorm.
 * User: sprudnikov
 * Date: 06.12.2016
 * Time: 19:17
 */

namespace Blogger\BlogBundle\Entity\Repository;

use Blogger\BlogBundle\Entity\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogRepositoryTest extends WebTestCase
{
    /**
     * @var \Blogger\BlogBundle\Entity\Repository\BlogRepository
     */
    private $blogRepository;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->blogRepository = $kernel->getContainer()
                                       ->get('doctrine.orm.entity_manager')
                                       ->getRepository('BlogBundle:Blog');
    }

    public function testGetTags()
    {
        $tags = $this->blogRepository->getTags();

        $this->assertTrue(count($tags) > 1);
        $this->assertContains('symblog', $tags);
    }

    public function testGetTagWeights()
    {
        $tagWeights = $this->blogRepository->getTagWeights(array('php', 'code', 'blog'));

        $this->assertTrue(count($tagWeights) > 1);

        $tagWeights = $this->blogRepository->getTagWeights(array_fill(0, 10, 'php'));

        $this->assertTrue(count($tagWeights) >= 1);

        $tagsWeight = $this->blogRepository->getTagWeights(
            array_merge(array_fill(0, 10, 'php'), array_fill(0, 2, 'html'), array_fill(0, 6, 'js'))
        );

        $this->assertEquals(5, $tagsWeight['php']);
        $this->assertEquals(3, $tagsWeight['js']);
        $this->assertEquals(1, $tagsWeight['html']);

        $tagsWeight = $this->blogRepository->getTagWeights(array());

        $this->assertEmpty($tagsWeight);
    }
}
