<?php
namespace PointingBreedTest\Console;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\GitlabHostOption;
use Symfony\Component\Console\Input\InputOption;

/**
 * @covers PointingBreed\Console\GitlabHostOption
 */
class GitlabHostOptionTest extends TestCase
{
    public function testItShouldBeAnInputOption()
    {
        $this->assertInstanceOf(InputOption::class, new GitlabHostOption());
    }

    public function testItShouldProvideItsNameAsConstant()
    {
        $this->assertSame((new GitlabHostOption())->getName(), GitlabHostOption::NAME);
    }

    public function testItShouldRequireAValue()
    {
        $this->assertTrue((new GitlabHostOption())->isValueRequired());
    }
}
