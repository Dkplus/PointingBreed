<?php
namespace PointingBreedTest\Console;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Console\AutodetectInputEvent;
use PointingBreed\Console\ReportCheckstyleCommand;
use PointingBreed\ReportGenerator\ReportFromCheckstyleGenerator;
use PointingBreed\Reporting\Report;
use PointingBreed\Reporting\Reporter;
use PointingBreed\Reporting\ReporterFactory;
use Prophecy\Argument;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @covers PointingBreed\Console\ReportCheckstyleCommand
 */
class ReportCheckstyleCommandTest extends TestCase
{
    public function testItShouldReportTheReportsFromCheckstyle()
    {
        $options         = ['foo' => 'bar'];
        $reports         = [$this->aReport()->reveal()];
        $reportGenerator = $this->aReportGeneratorForFile('checkstyle.xml', $reports);
        $reporter        = $this->aReporter();
        $reporters       = $this->aReportFactoryThatGenerates($reporter->reveal(), $options);
        $events          = $this->anEventDispatcher();

        $underTest = new ReportCheckstyleCommand($events->reveal(), $reportGenerator->reveal(), $reporters->reveal());
        $underTest->addOption('foo'); // we need an option to assure that the right input has been used
        $underTest->run($this->anInputWithOptions('checkstyle.xml', $options), $this->anOutput());

        $reporter->report($reports)->shouldHaveBeenCalled();
    }

    public function testItShouldAllowAutomaticCollectionOfOptions()
    {
        $options         = ['foo' => 'bar'];
        $reports         = [$this->aReport()->reveal()];
        $reportGenerator = $this->aReportGeneratorForFile('checkstyle.xml', $reports);
        $reporter        = $this->aReporter();
        $reporters       = $this->aReportFactoryThatGenerates($reporter->reveal(), $options);
        $events          = $this->anEventDispatcher();

        $underTest = new ReportCheckstyleCommand($events->reveal(), $reportGenerator->reveal(), $reporters->reveal());
        $underTest->addOption('foo'); // we need an option to assure that the right input has been used
        $underTest->run($this->anInputWithOptions('checkstyle.xml', $options), $this->anOutput());

        $events
            ->dispatch(AutodetectInputEvent::NAME, Argument::type(AutodetectInputEvent::class))
            ->shouldHaveBeenCalled();
    }

    private function aReport()
    {
        return $this->prophesize(Report::class);
    }

    private function anEventDispatcher()
    {
        return $this->prophesize(EventDispatcherInterface::class);
    }

    private function aReportGeneratorForFile($file, array $reports)
    {
        $result = $this->prophesize(ReportFromCheckstyleGenerator::class);
        $result->parse($file)->willReturn($reports);
        return $result;
    }

    private function aReportFactoryThatGenerates(Reporter $reporter, array $options)
    {
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
        $input = new ArrayInput(array_merge(['checkstyle-xml' => $file], $options));
        return $input;
    }

    private function anOutput()
    {
        return $this->prophesize(OutputInterface::class)->reveal();
    }
}
