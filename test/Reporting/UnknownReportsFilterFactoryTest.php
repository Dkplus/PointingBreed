<?php
namespace PointingBreedTest\Reporting;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\CommitBeforeOption;
use PointingBreed\Console\CommitOption;
use PointingBreed\Console\FilterOption;
use PointingBreed\Git\Git;
use PointingBreed\Reporting\Reporter;
use PointingBreed\Reporting\ReporterFactory;
use PointingBreed\Reporting\UnknownReportsFilter;
use PointingBreed\Reporting\UnknownReportsFilterFactory;
use Prophecy\Argument;

/**
 * @covers PointingBreed\Reporting\UnknownReportsFilterFactory
 */
class UnknownReportsFilterFactoryTest extends TestCase
{
    public function testItShouldBeAReporterFactory()
    {
        $decorated = $this->aReporterFactory();
        $this->assertInstanceOf(
            ReporterFactory::class,
            new UnknownReportsFilterFactory($this->aGit()->reveal(), $decorated->reveal())
        );
    }

    public function testItShouldProvideAFilterIfAllNecessaryOptionsHasBeenPassed()
    {
        $decorated = $this->aReporterFactory();
        $underTest = new UnknownReportsFilterFactory($this->aGit()->reveal(), $decorated->reveal());
        $options   = [
            FilterOption::NAME       => 'introduced',
            CommitOption::NAME       => '2fa9',
            CommitBeforeOption::NAME => 'f8a3',
        ];

        $this->assertInstanceOf(UnknownReportsFilter::class, $underTest->authenticate($options));
    }

    /**
     * @dataProvider provideInsufficientOptions
     *
     * @param array $options
     * @return void
     */
    public function testItShouldProvideAReportOfTheDecoratedFactoryIfNotAllNecessaryOptionsHasBeenPassed(array $options)
    {
        $decorated = $this->aReporterFactory();
        $underTest = new UnknownReportsFilterFactory($this->aGit()->reveal(), $decorated->reveal());

        $result = $underTest->authenticate($options);
        $this->assertInstanceOf(Reporter::class, $result);
        $this->assertNotInstanceOf(UnknownReportsFilter::class, $result);
    }

    public static function provideInsufficientOptions()
    {
        return [
            'missing FilterOption' => [[
                CommitOption::NAME       => '2fa9',
                CommitBeforeOption::NAME => 'f8a3',
            ]],
            'missing CommitOption' => [[
                FilterOption::NAME       => 'introduced',
                CommitBeforeOption::NAME => 'f8a3',
            ]],
            'missing CommitBeforeOption' => [[
                FilterOption::NAME => 'introduced',
                CommitOption::NAME => '2fa9',
            ]],
        ];
    }

    private function aReporterFactory()
    {
        $reporter = $this->prophesize(Reporter::class);
        $factory  = $this->prophesize(ReporterFactory::class);
        $factory->authenticate(Argument::type('array'))->willReturn($reporter->reveal());
        return $factory;
    }

    private function aGit()
    {
        return $this->prophesize(Git::class);
    }
}
