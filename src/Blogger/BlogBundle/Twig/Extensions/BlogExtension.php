<?php

namespace Blogger\BlogBundle\Twig\Extensions;

class BlogExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('created_ago', array($this, 'createdAgo')),
        );
    }

    public function getName()
    {
        return 'blogger_blog_extension';
    }

    public function createdAgo(\DateTime $dateTime)
    {
        $delta = time() - $dateTime->getTimestamp();
        if ($delta < 0){
            throw new \InvalidArgumentException('createdAgo is unable to handle dates in the future');
        }

        if ($delta < 60){
            $time = $delta;
            $duration = "second";
        } elseif ($delta < 3600){
            $time = floor($delta / 60);
            $duration = "minute";
        } elseif ($delta < 86400){
            $time = floor($delta / 3600);
            $duration = "hour";
        } else {
            $time = floor($delta / 86400);
            $duration = "day";
        }
        $duration = $duration . (($time === 0 || $time > 1) ? "s" : "");
        return "$time $duration ago";
    }
}
