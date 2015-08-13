<?php
namespace PointingBreedTest\Listener;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\GlobalOptions;
use PointingBreed\Listener\AutodetectGitlabCiProjectIdListener;
use PointingBreed\Listener\AutodetectGitlabCiUrlListener;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $id      = 5;
        $command = $this->aCommand();
        $input   = $this->anInput();
        $output  = $this->anOutput();
        $event   = new ConsoleEvent($command->reveal(), $input->reveal(), $output->reveal());

        putenv('CI_PROJECT_ID=' . $id);
        $input->hasOption(GlobalOptions::GITLAB_CI_PROJECT_ID)->willReturn(false);
        $input->setOption(GlobalOptions::GITLAB_CI_PROJECT_ID, $id)->shouldBeCalled();

        $listener = new AutodetectGitlabCiProjectIdListener();
        $listener($event);
    }

    public function testItShouldNotDoAnythingWhenTheIdHasNotBeenSetAlreadyAndItsNotWithinTheEnvironment()
    {
        $command = $this->aCommand();
        $input   = $this->anInput();
        $output  = $this->anOutput();
        $event   = new ConsoleEvent($command->reveal(), $input->reveal(), $output->reveal());

        $input->hasOption(GlobalOptions::GITLAB_CI_PROJECT_ID)->willReturn(false);
        $input->setOption(GlobalOptions::GITLAB_CI_PROJECT_ID, Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectGitlabCiProjectIdListener();
        $listener($event);
    }

    public function testItShouldNotDoAnythingWhenTheIdHasBeenSetAlready()
    {
        $command = $this->aCommand();
        $input   = $this->anInput();
        $output  = $this->anOutput();
        $event   = new ConsoleEvent($command->reveal(), $input->reveal(), $output->reveal());

        putenv('CI_PROJECT_ID=5');
        $input->hasOption(GlobalOptions::GITLAB_CI_PROJECT_ID)->willReturn(true);
        $input->setOption(GlobalOptions::GITLAB_CI_PROJECT_ID, Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectGitlabCiProjectIdListener();
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
