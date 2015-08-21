<?php
namespace PointingBreedTest\Console;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\GitlabCiProjectIdOption;
use Symfony\Component\Console\Input\InputOption;

/**
 * @covers PointingBreed\Console\GitlabCiProjectIdOption
 */
class GitlabCiProjectIdOptionTest extends TestCase
{
    public function testItShouldBeAnInputOption()
    {
        $this->assertInstanceOf(InputOption::class, new GitlabCiProjectIdOption());
    }

    public function testItShouldProvideItsNameAsConstant()
    {
        $this->assertSame((new GitlabCiProjectIdOption())->getName(), GitlabCiProjectIdOption::NAME);
    }

    public function testItShouldRequireAValue()
    {
        $this->assertTrue((new GitlabCiProjectIdOption())->isValueRequired());
    }
}
