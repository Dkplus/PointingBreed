<?php
namespace PointingBreedTest\Console;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\GitlabPrivateTokenOption;
use Symfony\Component\Console\Input\InputOption;

/**
 * @covers PointingBreed\Console\GitlabPrivateTokenOption
 */
class GitlabPrivateTokenOptionTest extends TestCase
{
    public function testItShouldBeAnInputOption()
    {
        $this->assertInstanceOf(InputOption::class, new GitlabPrivateTokenOption());
    }

    public function testItShouldProvideItsNameAsConstant()
    {
        $this->assertSame((new GitlabPrivateTokenOption())->getName(), GitlabPrivateTokenOption::NAME);
    }

    public function testItShouldRequireAValue()
    {
        $this->assertTrue((new GitlabPrivateTokenOption())->isValueRequired());
    }
}
