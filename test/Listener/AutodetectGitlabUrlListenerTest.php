<?php
namespace PointingBreedTest\Listener;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\GlobalOptions;
use PointingBreed\Listener\AutodetectGitlabUrlListener;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @covers PointingBreed\Listener\AutodetectGitlabUrlListener
 */
class AutodetectGitlabUrlListenerTest extends TestCase
{
    /** @var string */
    private $backedRepoEnv;

    public function setUp()
    {
        $this->backedRepoEnv = getenv('CI_BUILD_REPO');
        putenv('CI_BUILD_REPO=');
    }

    public function tearDown()
    {
        putenv('CI_BUILD_REPO=' . $this->backedRepoEnv);
    }

    /**
     * @dataProvider provideValidGitlabUrls
     *
     * @param string $repositoryUrl
     * @param string $expectedApiUrl
     * @return void
     */
    public function testItShouldExtractAValidUrlFromTheEnvironment($repositoryUrl, $expectedApiUrl)
    {
        $command = $this->aCommand();
        $input   = $this->anInput();
        $output  = $this->anOutput();
        $event   = new ConsoleEvent($command->reveal(), $input->reveal(), $output->reveal());

        putenv('CI_BUILD_REPO=' . $repositoryUrl);
        $input->hasOption(GlobalOptions::GITLAB_URL)->willReturn(false);
        $input->setOption(GlobalOptions::GITLAB_URL, $expectedApiUrl)->shouldBeCalled();

        $listener = new AutodetectGitlabUrlListener();
        $listener($event);
    }

    public static function provideValidGitlabUrls()
    {
        return [
            [
                'https://gitlab-ci-token:068c84957409ba00e4a52315e6ccf6@gitlab.foo.net/foo/bar.git',
                'https://gitlab.foo.net/api/v3/'
            ],
            [
                'http://gitlab-ci-token:068c849374098a00e0a523b5e6ccf6@gitlab.com/baz/foo.git',
                'http://gitlab.com/api/v3/'
            ],
        ];
    }

    public function testItShouldNotDoAnythingWhenTheGitlabUrlHasBeenSetAlready()
    {
        $command = $this->aCommand();
        $input   = $this->anInput();
        $output  = $this->anOutput();
        $event   = new ConsoleEvent($command->reveal(), $input->reveal(), $output->reveal());

        putenv('CI_BUILD_REPO=https://gitlab-ci-token:068c84957409ba00e4a52315e6ccf6@gitlab.foo.net/foo/bar.git');
        $input->hasOption(GlobalOptions::GITLAB_URL)->willReturn(true);
        $input->setOption(GlobalOptions::GITLAB_URL, Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectGitlabUrlListener();
        $listener($event);
    }

    /**
     * @dataProvider provideInvalidGitlabUrls
     *
     * @param string $invalidUrl
     * @return void
     */
    public function testItShouldFilterOutInvalidUrls($invalidUrl)
    {
        $command = $this->aCommand();
        $input   = $this->anInput();
        $output  = $this->anOutput();
        $event   = new ConsoleEvent($command->reveal(), $input->reveal(), $output->reveal());

        putenv('CI_BUILD_REPO=' . $invalidUrl);
        $input->hasOption(GlobalOptions::GITLAB_URL)->willReturn(false);
        $input->setOption()->shouldNotBeCalled();

        $listener = new AutodetectGitlabUrlListener();
        $listener($event);
    }

    public static function provideInvalidGitlabUrls()
    {
        return [
            [''],
            ['foo'],
            ['https://gitlab.foo.net'],
            ['http://gitlab-ci-token:068c849374098a00e0a523b5e6ccf6@gitlab.com/baz/foo']
        ];
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
