<?php
namespace PointingBreedTest\Listener;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\AutodetectInputEvent;
use PointingBreed\Console\GitlabCiProjectIdOption;
use PointingBreed\Listener\AutodetectGitlabCiProjectIdListener;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @covers PointingBreed\Listener\AutodetectGitlabCiProjectIdListener
 */
class AutodetectGitlabCiProjectIdListenerTest extends TestCase
{
    /** @var string */
    private $backedId;

    public function setUp()
    {
        $this->backedId = getenv('CI_PROJECT_ID');
        putenv('CI_PROJECT_ID=');
    }

    public function tearDown()
    {
        putenv('CI_PROJECT_ID=' . $this->backedId);
    }

    public function testItShouldExtractTheIdFromTheEnvironment()
    {
        $id    = 5;
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('CI_PROJECT_ID=' . $id);
        $input->getOption(GitlabCiProjectIdOption::NAME)->willReturn(null);
        $input->setOption(GitlabCiProjectIdOption::NAME, $id)->shouldBeCalled();

        $listener = new AutodetectGitlabCiProjectIdListener();
        $listener($event);
    }

    public function testItShouldNotDoAnythingWhenTheIdHasNotBeenSetAlreadyAndItsNotWithinTheEnvironment()
    {
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        $input->getOption(GitlabCiProjectIdOption::NAME)->willReturn(null);
        $input->setOption(GitlabCiProjectIdOption::NAME, Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectGitlabCiProjectIdListener();
        $listener($event);
    }

    public function testItShouldNotDoAnythingWhenTheIdHasBeenSetAlready()
    {
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('CI_PROJECT_ID=5');
        $input->getOption(GitlabCiProjectIdOption::NAME)->willReturn('6');
        $input->setOption(GitlabCiProjectIdOption::NAME, Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectGitlabCiProjectIdListener();
        $listener($event);
    }

    private function anInput()
    {
        return $this->prophesize(InputInterface::class);
    }
}
