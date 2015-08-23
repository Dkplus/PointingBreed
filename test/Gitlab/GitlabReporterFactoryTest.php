<?php
namespace PointingBreedTest\Gitlab;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\CommitOption;
use PointingBreed\Console\GitlabHostOption;
use PointingBreed\Console\GitlabPrivateTokenOption;
use PointingBreed\Console\GitlabProjectIdOption;
use PointingBreed\Gitlab\GitlabReporter;
use PointingBreed\Gitlab\GitlabReporterFactory;
use PointingBreed\Reporting\Format;
use PointingBreed\Reporting\FormatFactory;
use PointingBreed\Reporting\NoReporterFoundException;
use PointingBreed\Reporting\ReporterFactory;

/**
 * @covers PointingBreed\Gitlab\GitlabReporterFactory
 */
class GitlabReporterFactoryTest extends TestCase
{
    public function testItShouldBeAReporterFactory()
    {
        $formatFactory = $this->prophesize(FormatFactory::class);
        $this->assertInstanceOf(ReporterFactory::class, new GitlabReporterFactory($formatFactory->reveal()));
    }
    
    public function testItShouldProvideAGitlabReporterIfAllNecessaryOptionsHasBeenPassed()
    {
        $options = [
            CommitOption::NAME             => '2fa9',
            GitlabHostOption::NAME         => 'https://gitlab.net/api/v3/',
            GitlabPrivateTokenOption::NAME => 'JCsgEFGZWgBBLs_Ps2Ri',
            GitlabProjectIdOption::NAME    => 'gitlab/gitlab',
        ];
        $formatFactory = $this->prophesize(FormatFactory::class);
        $formatFactory->create($options)->willReturn($this->prophesize(Format::class)->reveal());

        $underTest = new GitlabReporterFactory($formatFactory->reveal());

        $this->assertInstanceOf(GitlabReporter::class, $underTest->authenticate($options));
    }

    /**
     * @dataProvider provideInsufficientOptions
     *
     * @param array $options
     * @return void
     */
    public function testItShouldThrowAnExceptionIfNotAllNecessaryOptionsHasBeenPassed(array $options)
    {
        $formatFactory = $this->prophesize(FormatFactory::class);
        $formatFactory->create($options)->willReturn($this->prophesize(Format::class)->reveal());

        $underTest = new GitlabReporterFactory($formatFactory->reveal());

        $this->setExpectedException(NoReporterFoundException::class);
        $underTest->authenticate($options);
    }

    public static function provideInsufficientOptions()
    {
        return [
            'missing CommitOption' => [[
                GitlabHostOption::NAME         => 'https://gitlab.net/api/v3/',
                GitlabPrivateTokenOption::NAME => 'JCsgEFGZWgBBLs_Ps2Ri',
                GitlabProjectIdOption::NAME    => 'gitlab/gitlab',
            ]],
            'missing GitlabHostOption' => [[
                CommitOption::NAME             => '2fa9',
                GitlabPrivateTokenOption::NAME => 'JCsgEFGZWgBBLs_Ps2Ri',
                GitlabProjectIdOption::NAME    => 'gitlab/gitlab',
            ]],
            'missing GitlabPrivateTokenOption' => [[
                CommitOption::NAME             => '2fa9',
                GitlabHostOption::NAME         => 'https://gitlab.net/api/v3/',
                GitlabProjectIdOption::NAME    => 'gitlab/gitlab',
            ]],
            'missing GitlabProjectIdOption' => [[
                CommitOption::NAME             => '2fa9',
                GitlabHostOption::NAME         => 'https://gitlab.net/api/v3/',
                GitlabPrivateTokenOption::NAME => 'JCsgEFGZWgBBLs_Ps2Ri',
            ]],
        ];
    }
}
