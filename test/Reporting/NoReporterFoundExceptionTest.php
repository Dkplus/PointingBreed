<?php
namespace PointingBreedTest\Reporting;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Reporting\NoReporterFoundException;

/**
 * @covers PointingBreed\Reporting\NoReporterFoundException
 */
class NoReporterFoundExceptionTest extends TestCase
{
    public function testItShouldBeAnInvalidArgumentException()
    {
        $this->assertInstanceOf(InvalidArgumentException::class, new NoReporterFoundException());
    }
}
