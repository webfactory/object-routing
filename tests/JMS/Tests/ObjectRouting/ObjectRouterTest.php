<?php

namespace JMS\Tests\ObjectRouting;

use JMS\ObjectRouting\Metadata\ClassMetadata;
use JMS\ObjectRouting\ObjectRouter;
use JMS\ObjectRouting\RouterInterface;
use Metadata\MetadataFactoryInterface;
use PHPUnit\Framework\TestCase;

class ObjectRouterTest extends TestCase
{
    /** @var ObjectRouter */
    private $router;
    private $adapter;
    private $factory;

    public function testGenerate()
    {
        $metadata = new ClassMetadata('stdClass');
        $metadata->addRoute('view', 'view_name');

        $this->factory->expects($this->once())
            ->method('getMetadataForClass')
            ->with('stdClass')
            ->willReturn($metadata);

        $this->adapter->expects($this->once())
            ->method('generate')
            ->with('view_name', [], false)
            ->willReturn('/foo');

        $this->assertEquals('/foo', $this->router->generate('view', new \stdClass()));
    }

    public function testGenerateWithParams()
    {
        $metadata = new ClassMetadata('stdClass');
        $metadata->addRoute('view', 'view_name', ['foo' => 'bar']);

        $object = new \stdClass();
        $object->bar = 'baz';

        $this->factory->expects($this->once())
            ->method('getMetadataForClass')
            ->willReturn($metadata);

        $this->adapter->expects($this->once())
            ->method('generate')
            ->with('view_name', ['foo' => 'baz'], false)
            ->willReturn('/foobar');

        $this->assertEquals('/foobar', $this->router->generate('view', $object));
    }

    public function testGenerateWithParamExpression()
    {
        $metadata = new ClassMetadata('stdClass');
        $metadata->addRoute('view', 'view_name', [], ['foo' => 'this.bar']);

        $object = new \stdClass();
        $object->bar = 'baz';

        $this->factory->expects($this->once())
            ->method('getMetadataForClass')
            ->willReturn($metadata);

        $this->adapter->expects($this->once())
            ->method('generate')
            ->with('view_name', ['foo' => 'baz'], false)
            ->willReturn('/foobar');

        $this->assertEquals('/foobar', $this->router->generate('view', $object));
    }

    public function testGenerateWithParamExpressionThatRefersToParam()
    {
        $metadata = new ClassMetadata('stdClass');
        $metadata->addRoute('view', 'view_name', ['foo' => 'bar'], ['concat' => 'params["foo"] ~ this.bar']);

        $object = new \stdClass();
        $object->bar = 'baz';

        $this->factory->expects($this->once())
            ->method('getMetadataForClass')
            ->willReturn($metadata);

        $this->adapter->expects($this->once())
            ->method('generate')
            ->with('view_name', ['foo' => 'baz', 'concat' => 'bazbaz'], false)
            ->willReturn('/foobar');

        $this->assertEquals('/foobar', $this->router->generate('view', $object));
    }

    public function testGenerateWithNullableParamExpression()
    {
        $metadata = new ClassMetadata('stdClass');
        $metadata->addRoute('view', 'view_name', [], ['?foo' => 'this.bar', '?quux' => 'this.barbaz']);

        $object = new \stdClass();
        $object->bar = 'baz';
        $object->barbaz = null;

        $this->factory->expects($this->once())
            ->method('getMetadataForClass')
            ->willReturn($metadata);

        $this->adapter->expects($this->once())
            ->method('generate')
            ->with('view_name', ['foo' => 'baz'], false)
            ->willReturn('/foobar');

        $this->assertEquals('/foobar', $this->router->generate('view', $object));
    }

    public function testGenerateNonExistentType()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The object of class "stdClass" has no route with type "foo". Available types: view');

        $metadata = new ClassMetadata('stdClass');
        $metadata->addRoute('view', 'view_name');

        $this->factory->expects($this->once())
            ->method('getMetadataForClass')
            ->willReturn($metadata);

        $this->router->generate('foo', new \stdClass());
    }

    public function testGenerateNoMetadata()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('There were no object routes defined for class "stdClass".');

        $this->factory->expects($this->once())
            ->method('getMetadataForClass')
            ->willReturn(null);

        $this->router->generate('foo', new \stdClass());
    }

    protected function setUp(): void
    {
        $this->router = new ObjectRouter(
            $this->adapter = $this->getMockBuilder(RouterInterface::class)->getMock(),
            $this->factory = $this->getMockBuilder(MetadataFactoryInterface::class)->getMock()
        );
    }
}
