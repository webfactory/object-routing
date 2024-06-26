<?php

$metadata = new JMS\ObjectRouting\Metadata\ClassMetadata('JMS\Tests\ObjectRouting\Metadata\Driver\Fixture\BlogPost');

$metadata->addRoute('view', 'blog_post_view', ['slug' => 'slug'], ['?year' => 'this.isArchived ? this.year : null']);
$metadata->addRoute('edit', 'blog_post_edit', ['slug' => 'slug']);

return $metadata;
