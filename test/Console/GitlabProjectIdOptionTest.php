<?php
namespace PointingBreedTest\Console;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\GitlabProjectIdOption;
use Symfony\Component\Console\Input\InputOption;

/**
 * @covers PointingBreed\Console\GitlabProjectIdOption
 */
class GitlabProjectIdOptionTest extends TestCase
{
    public function testItShouldBeAnInputOption()
    {
        $this->assertInstanceOf(InputOption::class, new GitlabProjectIdOption());
    }

    public function testItShouldProvideItsNameAsConstant()
    {
        $this->assertSame((new GitlabProjectIdOption())->getName(), GitlabProjectIdOption::NAME);
    }

    public function testItShouldRequireAValue()
    {
        $this->assertTrue((new GitlabProjectIdOption())->isValueRequired());
    }
}
