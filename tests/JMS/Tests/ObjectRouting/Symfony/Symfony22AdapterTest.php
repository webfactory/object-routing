<?php

namespace JMS\Tests\ObjectRouting\Symfony;

use JMS\ObjectRouting\Symfony\Symfony22Adapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class Symfony22AdapterTest extends TestCase
{
    /** @var Symfony22Adapter */
    private $adapter;
    private $router;

    public function testGenerate()
    {
        $this->router->expects($this->once())
            ->method('generate')
            ->with('foo', ['bar' => 'baz'], UrlGeneratorInterface::ABSOLUTE_URL)
            ->willReturn('/foo-bar-baz');

        $this->assertEquals('/foo-bar-baz', $this->adapter->generate('foo', ['bar' => 'baz'], true));
    }

    protected function setUp(): void
    {
        $this->adapter = new Symfony22Adapter(
            $this->router = $this->getMockBuilder(RouterInterface::class)->getMock()
        );
    }
}
