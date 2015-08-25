<?php
namespace PointingBreedTest\Listener;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\AutodetectInputEvent;
use PointingBreed\Console\GitlabCiHostOption;
use PointingBreed\Listener\AutodetectGitlabCiHostListener;
use Prophecy\Argument;
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
        $input->getOption(GitlabCiHostOption::NAME)->willReturn(null);
        $input->setOption(GitlabCiHostOption::NAME, $url)->shouldBeCalled();

        $listener = new AutodetectGitlabCiHostListener();
        $listener($event);
    }

    public function testItShouldNotDoAnythingWhenTheGitlabCiUrlHasNotBeenSetAlreadyAndItsNotWithinTheEnvironment()
    {
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        $input->getOption(GitlabCiHostOption::NAME)->willReturn(null);
        $input->setOption(GitlabCiHostOption::NAME, Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectGitlabCiHostListener();
        $listener($event);
    }

    public function testItShouldNotDoAnythingWhenTheGitlabCiUrlHasBeenSetAlready()
    {
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('GITLAB_CI_URL=http://gitlab-ci.com/');
        $input->getOption(GitlabCiHostOption::NAME)->willReturn('https://ci.com/');
        $input->setOption(GitlabCiHostOption::NAME, Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectGitlabCiHostListener();
        $listener($event);
    }

    private function anInput()
    {
        return $this->prophesize(InputInterface::class);
    }
}
