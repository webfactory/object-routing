<?php

namespace JMS\Tests\ObjectRouting\Metadata\Driver\Fixture;

use JMS\ObjectRouting\Attribute\ObjectRoute;

#[ObjectRoute(type: "view", name: "blog_post_view", params: ['slug' => 'slug'], paramExpressions: ['?year' => 'this.isArchived ? this.year : null'])]
#[ObjectRoute(type: "edit", name: "blog_post_edit", params: ['slug' => 'slug'])]
class BlogPostWithAttributes
{
    private $slug;
    private $archived;
    private $year;

    public function __construct($slug, $archived, $year)
    {
        $this->slug = $slug;
        $this->archived = $archived;
        $this->year = $year;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function isArchived()
    {
        return $this->archived;
    }

    public function getYear()
    {
        return $this->year;
    }
}
