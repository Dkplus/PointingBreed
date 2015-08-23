<?php
namespace PointingBreedTest\Reporting;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Reporting\DefaultFormatFactory;
use PointingBreed\Reporting\Format;

/**
 * @covers PointingBreed\Reporting\DefaultFormatFactory
 */
class DefaultFormatFactoryTest extends TestCase
{
    public function testItShouldProvideTheDefaultFormat()
    {
        $defaultFormat = $this->prophesize(Format::class)->reveal();
        $underTest     = new DefaultFormatFactory($defaultFormat);
        $this->assertSame($defaultFormat, $underTest->create([]));
    }
}
