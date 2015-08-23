<?php
namespace PointingBreedTest\Reporting;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Reporting\Trend;

/**
 * @covers PointingBreed\Reporting\Trend
 */
class TrendTest extends TestCase
{
    public function testItShouldGiveATrendImprovement()
    {
        $trend = Trend::improvement();
        $this->assertTrue($trend->isGettingBetter());
        $this->assertFalse($trend->isGettingWorse());
    }

    public function testItShouldGiveATrendPejoration()
    {
        $trend = Trend::pejoration();
        $this->assertTrue($trend->isGettingWorse());
        $this->assertFalse($trend->isGettingBetter());
    }
}
