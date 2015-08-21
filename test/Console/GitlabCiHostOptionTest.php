<?php
namespace PointingBreedTest\Console;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\GitlabCiHostOption;
use Symfony\Component\Console\Input\InputOption;

/**
 * @covers PointingBreed\Console\GitlabCiHostOption
 */
class GitlabCiHostOptionTest extends TestCase
{
    public function testItShouldBeAnInputOption()
    {
        $this->assertInstanceOf(InputOption::class, new GitlabCiHostOption());
    }

    public function testItShouldProvideItsNameAsConstant()
    {
        $this->assertSame((new GitlabCiHostOption())->getName(), GitlabCiHostOption::NAME);
    }

    public function testItShouldRequireAValue()
    {
        $this->assertTrue((new GitlabCiHostOption())->isValueRequired());
    }
}
