<?php
namespace PointingBreedTest\Listener;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\AutodetectInputEvent;
use PointingBreed\Console\GitlabPrivateTokenOption;
use PointingBreed\Listener\AutodetectGitlabPrivateTokenListener;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @covers PointingBreed\Listener\AutodetectGitlabPrivateTokenListener
 */
class AutodetectGitlabPrivateTokenListenerTest extends TestCase
{
    /** @var string */
    private $backedPrivateToken;

    public function setUp()
    {
        $this->backedPrivateToken = getenv('GITLAB_PRIVATE_TOKEN');
        putenv('GITLAB_PRIVATE_TOKEN=');
    }

    public function tearDown()
    {
        putenv('GITLAB_PRIVATE_TOKEN=' . $this->backedPrivateToken);
    }

    public function testItShouldGrabTheGitlabPrivateTokenEnvironmentAndPutItIntoTheInput()
    {
        $token = '9EgMX8pmtZ2M6yhPWcfP';
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('GITLAB_PRIVATE_TOKEN=' . $token);
        $input->getOption(GitlabPrivateTokenOption::NAME)->willReturn(null);
        $input->setOption(GitlabPrivateTokenOption::NAME, $token)->shouldBeCalled();

        $listener = new AutodetectGitlabPrivateTokenListener();
        $listener($event);
    }

    public function testItShouldNotGrabTheGitlabPrivateTokenFromTheEnvironmentWhenItsAlreadyWithinTheInput()
    {
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('GITLAB_PRIVATE_TOKEN=9EgMX8pmtZ2M6yhPWcfP');
        $input->getOption(GitlabPrivateTokenOption::NAME)->willReturn('2EgMX8pmtZ5M6yhPWcfP');
        $input->setOption(Argument::any(), Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectGitlabPrivateTokenListener();
        $listener($event);
    }

    private function anInput()
    {
        return $this->prophesize(InputInterface::class);
    }
}
