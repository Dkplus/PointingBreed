<?php
namespace PointingBreedTest\Console;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\CommitBeforeOption;
use Symfony\Component\Console\Input\InputOption;

/**
 * @covers PointingBreed\Console\CommitBeforeOption
 */
class CommitBeforeOptionTest extends TestCase
{
    public function testItShouldBeAnInputOption()
    {
        $this->assertInstanceOf(InputOption::class, new CommitBeforeOption());
    }

    public function testItShouldProvideItsNameAsConstant()
    {
        $this->assertSame((new CommitBeforeOption())->getName(), CommitBeforeOption::NAME);
    }

    public function testItShouldRequireAValue()
    {
        $this->assertTrue((new CommitBeforeOption())->isValueRequired());
    }
}
