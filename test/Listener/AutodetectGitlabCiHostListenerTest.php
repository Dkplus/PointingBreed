<?php
namespace PointingBreedTest\Listener;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\AutodetectInputEvent;
use PointingBreed\Console\GitlabCiHostOption;
use PointingBreed\Listener\AutodetectGitlabCiHostListener;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @covers PointingBreed\Listener\AutodetectGitlabCiHostListener
 */
class AutodetectGitlabCiHostListenerTest extends TestCase
{
    /** @var string */
    private $backedUrl;

    public function setUp()
    {
        $this->backedUrl = getenv('GITLAB_CI_URL');
        putenv('GITLAB_CI_URL=');
    }

    public function tearDown()
    {
        putenv('GITLAB_CI_URL=' . $this->backedUrl);
    }

    public function testItShouldExtractTheUrlFromTheEnvironment()
    {
        $url   = 'http://gitlab-ci.com/';
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('GITLAB_CI_URL=' . $url);
        $input->hasOption(GitlabCiHostOption::NAME)->willReturn(false);
        $input->setOption(GitlabCiHostOption::NAME, $url)->shouldBeCalled();

        $listener = new AutodetectGitlabCiHostListener();
        $listener($event);
    }

    public function testItShouldNotDoAnythingWhenTheGitlabCiUrlHasNotBeenSetAlreadyAndItsNotWithinTheEnvironment()
    {
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        $input->hasOption(GitlabCiHostOption::NAME)->willReturn(false);
        $input->setOption(GitlabCiHostOption::NAME, Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectGitlabCiHostListener();
        $listener($event);
    }

    public function testItShouldNotDoAnythingWhenTheGitlabCiUrlHasBeenSetAlready()
    {
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('GITLAB_CI_URL=http://gitlab-ci.com/');
        $input->hasOption(GitlabCiHostOption::NAME)->willReturn(true);
        $input->setOption(GitlabCiHostOption::NAME, Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectGitlabCiHostListener();
        $listener($event);
    }

    /** @return InputInterface|ObjectProphecy */
    private function anInput()
    {
        return $this->prophesize(InputInterface::class);
    }
}
