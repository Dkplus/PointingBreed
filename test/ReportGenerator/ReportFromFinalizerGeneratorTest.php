<?php
namespace PointingBreedTest\ReportGenerator;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\ReportGenerator\ReportFromFinalizerGenerator;
use PointingBreed\Reporting\Severity;

/**
 * @covers PointingBreed\ReportGenerator\ReportFromFinalizerGenerator
 */
class ReportFromFinalizerGeneratorTest extends TestCase
{
    public function testItShouldProvideReportsAccordingToTheGivenSeverity()
    {
        $result = (new ReportFromFinalizerGenerator(__DIR__ . '/../..'))
            ->parse(__DIR__ . '/assets/finalizer.log', Severity::notice());

        $this->assertCount(2, $result);
        $this->assertEquals(
            '`PointingBreed\\Reporting\\DefaultFormatFactory` need to be made final. For the reasons see '
            . '[When to declare classes final](http://ocramius.github.io/blog/when-to-declare-classes-final/).',
            $result[0]->toText()
        );
        $this->assertEquals('src/Reporting/DefaultFormatFactory.php', $result[0]->toFile());
        $this->assertEquals(Severity::notice(), $result[0]->toSeverity());
        $this->assertEquals(4, $result[0]->toReportLine());
        $this->assertNull($result[0]->toChangedLine());

        $this->assertEquals(
            '`PointingBreed\\Reporting\\NoReporterFoundException` need to be made extensible again. For the reasons '
            . 'see [When to declare classes final](http://ocramius.github.io/blog/when-to-declare-classes-final/).',
            $result[1]->toText()
        );
        $this->assertEquals('src/Reporting/NoReporterFoundException.php', $result[1]->toFile());
        $this->assertEquals(Severity::notice(), $result[1]->toSeverity());
        $this->assertEquals(6, $result[1]->toReportLine());
        $this->assertNull($result[1]->toChangedLine());
    }
}
