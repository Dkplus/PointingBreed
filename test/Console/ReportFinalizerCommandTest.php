<?php
namespace PointingBreedTest\Console;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\ReportFinalizerCommand;
use PointingBreed\ReportGenerator\ReportFromCheckstyleGenerator;
use PointingBreed\ReportGenerator\ReportFromFinalizerGenerator;
use PointingBreed\Reporting\Report;
use PointingBreed\Reporting\Reporter;
use PointingBreed\Reporting\ReporterFactory;
use PointingBreed\Reporting\Severity;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @covers PointingBreed\Console\ReportFinalizerCommand
 */
class ReportFinalizerCommandTest extends TestCase
{
    public function testItShouldReportWithNoticeAsDefaultSeverity()
    {
        $options         = [];
        $reports         = [$this->aReport()->reveal()];
        $reportGenerator = $this->aReportGeneratorForFile('finalizer.log', Severity::notice(), $reports);
        $reporter        = $this->aReporter();
        $reporters       = $this->aReportFactoryThatGenerates($reporter->reveal(), $options);

        $underTest = new ReportFinalizerCommand($reportGenerator->reveal(), $reporters->reveal());
        $underTest->run($this->anInputWithOptions('finalizer.log', $options), $this->anOutput());

        $reporter->report($reports)->shouldHaveBeenCalled();
    }

    /**
     * @dataProvider provideAllowedSeverities
     *
     * @param string   $textualSeverity
     * @param Severity $severity
     * @return void
     */
    public function testItShouldReportTheReportsFromCheckstyle($textualSeverity, Severity $severity)
    {
        $options         = ['severity' => $textualSeverity];
        $reports         = [$this->aReport()->reveal()];
        $reportGenerator = $this->aReportGeneratorForFile('finalizer.log', $severity, $reports);
        $reporter        = $this->aReporter();
        $reporters       = $this->aReportFactoryThatGenerates($reporter->reveal(), $options);

        $underTest = new ReportFinalizerCommand($reportGenerator->reveal(), $reporters->reveal());
        $underTest->run($this->anInputWithOptions('finalizer.log', $options), $this->anOutput());

        $reporter->report($reports)->shouldHaveBeenCalled();
    }

    public static function provideAllowedSeverities()
    {
        return [
            ['error', Severity::error()],
            ['warning', Severity::warning()],
            ['notice', Severity::notice()],
        ];
    }

    private function aReport()
    {
        return $this->prophesize(Report::class);
    }

    private function aReportGeneratorForFile($file, Severity $severity, array $reports)
    {
        $result = $this->prophesize(ReportFromFinalizerGenerator::class);
        $result->parse($file, $severity)->willReturn($reports);
        return $result;
    }

    private function aReportFactoryThatGenerates(Reporter $reporter, array $options)
    {
        if (! isset($options['severity'])) {
            $options['severity'] = null;
        }
        $result = $this->prophesize(ReporterFactory::class);
        $result->authenticate($options)->willReturn($reporter);
        return $result;
    }

    private function aReporter()
    {
        return $this->prophesize(Reporter::class);
    }

    private function anInputWithOptions($file, array $options)
    {
        foreach (array_keys($options) as $each) {
            $options['--' . $each] = $options[$each];
            unset($options[$each]);
        }
        $input = new ArrayInput(array_merge(['output-log' => $file], $options));
        return $input;
    }

    private function anOutput()
    {
        return $this->prophesize(OutputInterface::class)->reveal();
    }
}
