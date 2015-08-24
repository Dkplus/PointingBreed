<?php
namespace PointingBreedTest\Listener;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\AutodetectInputEvent;
use PointingBreed\Console\GitlabHostOption;
use PointingBreed\GlobalOptions;
use PointingBreed\Listener\AutodetectGitlabHostListener;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @covers PointingBreed\Listener\AutodetectGitlabHostListener
 */
class AutodetectGitlabHostListenerTest extends TestCase
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
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('CI_BUILD_REPO=' . $repositoryUrl);
        $input->hasOption(GitlabHostOption::NAME)->willReturn(false);
        $input->setOption(GitlabHostOption::NAME, $expectedApiUrl)->shouldBeCalled();

        $listener = new AutodetectGitlabHostListener();
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
        $input = $this->anInput();
        $event = new AutodetectInputEvent($input->reveal());

        putenv('CI_BUILD_REPO=https://gitlab-ci-token:068c84957409ba00e4a52315e6ccf6@gitlab.foo.net/foo/bar.git');
        $input->hasOption(GitlabHostOption::NAME)->willReturn(true);
        $input->setOption(GitlabHostOption::NAME, Argument::any())->shouldNotBeCalled();

        $listener = new AutodetectGitlabHostListener();
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
        $input->hasOption(GitlabHostOption::NAME)->willReturn(false);
        $input->setOption()->shouldNotBeCalled();

        $listener = new AutodetectGitlabHostListener();
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
