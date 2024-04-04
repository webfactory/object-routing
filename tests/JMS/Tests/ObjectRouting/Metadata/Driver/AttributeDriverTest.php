<?php

namespace JMS\Tests\ObjectRouting\Metadata\Driver;

use JMS\ObjectRouting\Metadata\Driver\AttributeDriver;
use JMS\Tests\ObjectRouting\Metadata\Driver\Fixture\BlogPostWithAttributes;
use PHPUnit\Framework\TestCase;

class AttributeDriverTest extends TestCase
{
    private readonly AttributeDriver $driver;

    public function testLoad()
    {
        $metadata = $this->driver->loadMetadataForClass(new \ReflectionClass(BlogPostWithAttributes::class));
        $this->assertCount(2, $metadata->routes);

        $routes = [
            'view' => ['name' => 'blog_post_view', 'params' => ['slug' => 'slug']],
            'edit' => ['name' => 'blog_post_edit', 'params' => ['slug' => 'slug']],
        ];
        $this->assertEquals($routes, $metadata->routes);
    }

    public function testLoadReturnsNullWhenNoRoutes()
    {
        $this->assertNull($this->driver->loadMetadataForClass(new \ReflectionClass('stdClass')));
    }

    protected function setUp(): void
    {
        $this->driver = new AttributeDriver();
    }
}
