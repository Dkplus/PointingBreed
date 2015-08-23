<?php
namespace PointingBreedTest\Reporting;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Reporting\Report;
use PointingBreed\Reporting\Severity;

/**
 * @covers PointingBreed\Reporting\Report
 */
class ReportTest extends TestCase
{
    public function testItShouldGiveReportsForTheCompleteCommit()
    {
        $report = Report::forCommit('any text', Severity::error());
        $this->assertEquals('any text', $report->toText());
        $this->assertEquals(Severity::error(), $report->toSeverity());
        $this->assertNull($report->toFile());
        $this->assertNull($report->toReportLine());
        $this->assertNull($report->toChangedLine());
    }
    
    public function testItShouldGiveReportsForCompleteFiles()
    {
        $report = Report::forFile('any text', Severity::error(), 'test/Reporting/ReportTest.php');
        $this->assertEquals('any text', $report->toText());
        $this->assertEquals(Severity::error(), $report->toSeverity());
        $this->assertEquals('test/Reporting/ReportTest.php', $report->toFile());
        $this->assertEquals(1, $report->toReportLine());
        $this->assertNull($report->toChangedLine());
    }

    public function testItShouldGiveReportsForCompleteFilesReportedAtASingleLine()
    {
        $report = Report::forFile('any text', Severity::error(), 'test/Reporting/ReportTest.php', 3);
        $this->assertEquals('any text', $report->toText());
        $this->assertEquals(Severity::error(), $report->toSeverity());
        $this->assertEquals('test/Reporting/ReportTest.php', $report->toFile());
        $this->assertEquals(3, $report->toReportLine());
        $this->assertNull($report->toChangedLine());
    }

    public function testItShouldGiveReportsForSingleLines()
    {
        $report = Report::forLine('any text', Severity::error(), 'test/Reporting/ReportTest.php', 5);
        $this->assertEquals('any text', $report->toText());
        $this->assertEquals(Severity::error(), $report->toSeverity());
        $this->assertEquals('test/Reporting/ReportTest.php', $report->toFile());
        $this->assertEquals(5, $report->toReportLine());
        $this->assertEquals(5, $report->toChangedLine());
    }
}
