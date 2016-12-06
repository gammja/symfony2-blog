<?php
/**
 * Created by PhpStorm.
 * User: sprudnikov
 * Date: 06.12.2016
 * Time: 16:52
 */

namespace Blogger\BlogBundle\Entity;

class BlogTest extends \PHPUnit_Framework_TestCase
{
    public function testSlugify()
    {
        $blog = new Blog();

        $this->assertEquals('hello-world', $blog->slugify('Hello World'));
        $this->assertEquals('a-day-with-symfony2', $blog->slugify('A Day With Symfony2'));
        $this->assertEquals('symblog', $blog->slugify('symblog '));
        $this->assertEquals('symblog', $blog->slugify(' symblog'));
    }

    public function testTitle()
    {
        $blog = new Blog();

        $blog->setTitle("Test test");

        $this->assertEquals('test-test', $blog->getSlug());
    }
}