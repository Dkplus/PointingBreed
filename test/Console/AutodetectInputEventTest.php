<?php
namespace PointingBreedTest\Console;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\AutodetectInputEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * @covers PointingBreed\Console\AutodetectInputEvent
 */
class AutodetectInputEventTest extends TestCase
{
    public function testItShouldBeAnEvent()
    {
        $this->assertInstanceOf(Event::class, new AutodetectInputEvent($this->anInput()->reveal()));
    }

    public function testItShouldProvideTheInput()
    {
        $input = $this->anInput()->reveal();
        $this->assertSame($input, (new AutodetectInputEvent($input))->getInput());
    }

    private function anInput()
    {
        return $this->prophesize(InputInterface::class);
    }
}
