<?php
namespace PointingBreedTest\Console;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\FilterOption;
use Symfony\Component\Console\Input\InputOption;

/**
 * @covers PointingBreed\Console\FilterOption
 */
class FilterOptionTest extends TestCase
{
    public function testItShouldBeAnInputOption()
    {
        $this->assertInstanceOf(InputOption::class, new FilterOption());
    }

    public function testItShouldProvideItsNameAsConstant()
    {
        $this->assertSame((new FilterOption())->getName(), FilterOption::NAME);
    }

    public function testItShouldRequireAValue()
    {
        $this->assertTrue((new FilterOption())->isValueRequired());
    }
}
