<?php
namespace PointingBreedTest\Listener;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\AutodetectInputEvent;
use PointingBreed\Console\GitlabProjectIdOption;
use PointingBreed\Listener\AutodetectGitlabProjectIdListener;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @covers PointingBreed\Listener\AutodetectGitlabProjectIdListener
 */
class AutodetectGitlabProjectIdListenerTest extends TestCase
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
     * @param string $expectedProjectId
     * @return void
     */
    public function testItShouldExtractAValidUrlFromTheEnvironment($repositoryUrl, $expectedProjectId)
    {
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('CI_BUILD_REPO=' . $repositoryUrl);
        $input->hasOption(GitlabProjectIdOption::NAME)->willReturn(false);
        $input->setOption(GitlabProjectIdOption::NAME, $expectedProjectId)->shouldBeCalled();

        $listener = new AutodetectGitlabProjectIdListener();
        $listener($event);
    }

    public static function provideValidGitlabUrls()
    {
        return [
            [
                'https://gitlab-ci-token:068c84957409ba00e4a52315e6ccf6@gitlab.foo.net/foo/bar.git',
                'foo%2Fbar'
            ],
            [
                'http://gitlab-ci-token:068c849374098a00e0a523b5e6ccf6@gitlab.com/baz/foo.git',
                'baz%2Ffoo'
            ],
        ];
    }

    public function testItShouldNotDoAnythingWhenTheProjectIdHasBeenSetAlready()
    {
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('CI_BUILD_REPO=https://gitlab-ci-token:068c84957409ba00e4a52315e6ccf6@gitlab.foo.net/foo/bar.git');
        $input->hasOption(GitlabProjectIdOption::NAME)->willReturn(true);
        $input->setOption(GitlabProjectIdOption::NAME, Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectGitlabProjectIdListener();
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
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('CI_BUILD_REPO=' . $invalidUrl);
        $input->hasOption(GitlabProjectIdOption::NAME)->willReturn(false);
        $input->setOption()->shouldNotBeCalled();

        $listener = new AutodetectGitlabProjectIdListener();
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

    /** @return ObjectProphecy */
    private function anInput()
    {
        return $this->prophesize(InputInterface::class);
    }
}
