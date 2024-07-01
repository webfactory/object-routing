<?php

namespace JMS\Tests\ObjectRouting\Metadata\Driver;

use JMS\ObjectRouting\Metadata\Driver\XmlDriver;
use Metadata\Driver\FileLocator;
use PHPUnit\Framework\TestCase;

class XmlDriverTest extends TestCase
{
    /** @var XmlDriver */
    private $driver;

    public function testLoad()
    {
        $metadata = $this->driver->loadMetadataForClass(new \ReflectionClass('JMS\Tests\ObjectRouting\Metadata\Driver\Fixture\BlogPost'));
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
        $this->driver = new XmlDriver(new FileLocator(['' => realpath(__DIR__.'/../../Resources/config')]));
    }
}
