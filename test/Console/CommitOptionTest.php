<?php
namespace PointingBreedTest\Console;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\CommitOption;
use Symfony\Component\Console\Input\InputOption;

/**
 * @covers PointingBreed\Console\CommitOption
 */
class CommitOptionTest extends TestCase
{
    public function testItShouldBeAnInputOption()
    {
        $this->assertInstanceOf(InputOption::class, new CommitOption());
    }

    public function testItShouldProvideItsNameAsConstant()
    {
        $this->assertSame((new CommitOption())->getName(), CommitOption::NAME);
    }

    public function testItShouldRequireAValue()
    {
        $this->assertTrue((new CommitOption())->isValueRequired());
    }
}
