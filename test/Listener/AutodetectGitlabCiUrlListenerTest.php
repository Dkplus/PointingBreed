<?php
namespace PointingBreedTest\Listener;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\GlobalOptions;
use PointingBreed\Listener\AutodetectGitlabCiUrlListener;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @covers PointingBreed\Listener\AutodetectGitlabCiUrlListener
 */
class AutodetectGitlabCiUrlListenerTest extends TestCase
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
        $url     = 'http://gitlab-ci.com/';
        $command = $this->aCommand();
        $input   = $this->anInput();
        $output  = $this->anOutput();
        $event   = new ConsoleEvent($command->reveal(), $input->reveal(), $output->reveal());

        putenv('GITLAB_CI_URL=' . $url);
        $input->hasOption(GlobalOptions::GITLAB_CI_URL)->willReturn(false);
        $input->setOption(GlobalOptions::GITLAB_CI_URL, $url)->shouldBeCalled();

        $listener = new AutodetectGitlabCiUrlListener();
        $listener($event);
    }

    public function testItShouldNotDoAnythingWhenTheGitlabCiUrlHasNotBeenSetAlreadyAndItsNotWithinTheEnvironment()
    {
        $command = $this->aCommand();
        $input   = $this->anInput();
        $output  = $this->anOutput();
        $event   = new ConsoleEvent($command->reveal(), $input->reveal(), $output->reveal());

        $input->hasOption(GlobalOptions::GITLAB_CI_URL)->willReturn(false);
        $input->setOption(GlobalOptions::GITLAB_CI_URL, Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectGitlabCiUrlListener();
        $listener($event);
    }

    public function testItShouldNotDoAnythingWhenTheGitlabCiUrlHasBeenSetAlready()
    {
        $command = $this->aCommand();
        $input   = $this->anInput();
        $output  = $this->anOutput();
        $event   = new ConsoleEvent($command->reveal(), $input->reveal(), $output->reveal());

        putenv('GITLAB_CI_URL=http://gitlab-ci.com/');
        $input->hasOption(GlobalOptions::GITLAB_CI_URL)->willReturn(true);
        $input->setOption(GlobalOptions::GITLAB_CI_URL, Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectGitlabCiUrlListener();
        $listener($event);
    }

    /** @return Command|ObjectProphecy */
    private function aCommand()
    {
        return $this->prophesize(Command::class);
    }

    /** @return InputInterface|ObjectProphecy */
    private function anInput()
    {
        return $this->prophesize(InputInterface::class);
    }

    /** @return OutputInterface|ObjectProphecy */
    private function anOutput()
    {
        return $this->prophesize(OutputInterface::class);
    }
}
