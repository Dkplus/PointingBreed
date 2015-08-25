<?php
namespace PointingBreedTest\Listener;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\AutodetectInputEvent;
use PointingBreed\Console\CommitBeforeOption;
use PointingBreed\Console\CommitOption;
use PointingBreed\Listener\AutodetectCommitsListener;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @covers PointingBreed\Listener\AutodetectCommitsListener
 */
class AutodetectCommitsListenerTest extends TestCase
{
    /** @var string */
    private $backedCommitSha;

    /** @var string */
    private $backedCommitShaBefore;

    public function setUp()
    {
        $this->backedCommitSha       = getenv('CI_BUILD_REF');
        $this->backedCommitShaBefore = getenv('CI_BUILD_BEFORE_SHA');
        putenv('CI_BUILD_REF=');
        putenv('CI_BUILD_BEFORE_SHA=');
    }

    public function tearDown()
    {
        putenv('CI_BUILD_REF=' . $this->backedCommitSha);
        putenv('CI_BUILD_BEFORE_SHA=' . $this->backedCommitShaBefore);
    }

    public function testItShouldGrabTheCurrentCommitShaFromTheEnvironmentAndPutItIntoTheInput()
    {
        $sha   = 'ab6a30e08f5932f04f16a1c5be564118a66b730e';
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('CI_BUILD_REF=' . $sha);
        $input->getOption(CommitOption::NAME)->willReturn(null);
        $input->setOption(CommitOption::NAME, $sha)->shouldBeCalled();

        $listener = new AutodetectCommitsListener();
        $listener($event);
    }

    public function testItShouldNotGrabTheCurrentCommitShaFromTheEnvironmentWhenItsAlreadyWithinTheInput()
    {
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('CI_BUILD_REF=ab6a30e08f5932f04f16a1c5be564118a66b730e');
        $input->getOption(CommitOption::NAME)->willReturn('bc3a30e08f5932f04f16a1c5be564118a66b730e');
        $input->setOption(Argument::any(), Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectCommitsListener();
        $listener($event);
    }

    public function testItShouldGrabTheCurrentCommitShaBeforeFromTheEnvironmentAndPutItIntoTheInput()
    {
        $sha   = 'ab6a30e08f5932f04f16a1c5be564118a66b730e';
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('CI_BUILD_BEFORE_SHA=' . $sha);
        $input->getOption(CommitBeforeOption::NAME)->willReturn(null);
        $input->setOption(CommitBeforeOption::NAME, $sha)->shouldBeCalled();

        $listener = new AutodetectCommitsListener();
        $listener($event);
    }

    public function testItShouldNotGrabTheCurrentCommitShaBeforeFromTheEnvironmentWhenItsAlreadyWithinTheInput()
    {
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('CI_BUILD_BEFORE_SHA=ab6a30e08f5932f04f16a1c5be564118a66b730e');
        $input->getOption(CommitBeforeOption::NAME)->willReturn('c8da30e08f5932f04f16a1c5be564118a66b730e');
        $input->setOption(Argument::any(), Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectCommitsListener();
        $listener($event);
    }

    private function anInput()
    {
        return $this->prophesize(InputInterface::class);
    }
}
