<?php
/**
 * Created by PhpStorm.
 * User: sprudnikov
 * Date: 06.12.2016
 * Time: 17:02
 */

namespace Blogger\BlogBundle\Tests\Twig\Extensions;


use Blogger\BlogBundle\Twig\Extensions\BlogExtension;


class BlogExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateAgo()
    {
        $ext = new BlogExtension();

        $this->assertEquals("0 seconds ago", $ext->createdAgo(new \DateTime()));
        $this->assertEquals("34 seconds ago", $ext->createdAgo($this->getDateTime(-34)));
        $this->assertEquals("1 minute ago", $ext->createdAgo($this->getDateTime(-60)));
        $this->assertEquals("2 minutes ago", $ext->createdAgo($this->getDateTime(-120)));
        $this->assertEquals("1 hour ago", $ext->createdAgo($this->getDateTime(-3600)));
        $this->assertEquals("1 hour ago", $ext->createdAgo($this->getDateTime(-3601)));
        $this->assertEquals("2 hours ago", $ext->createdAgo($this->getDateTime(-7200)));

        $this->setExpectedException('\InvalidArgumentException');
        $ext->createdAgo($this->getDateTime(60));
    }

    private function getDateTime($delta)
    {
        return new \DateTime(date("Y-m-d H:i:s", time()+$delta));
    }
}
