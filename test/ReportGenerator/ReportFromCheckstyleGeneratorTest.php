<?php
namespace PointingBreedTest\ReportGenerator;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\ReportGenerator\ReportFromCheckstyleGenerator;
use PointingBreed\Reporting\Report;
use PointingBreed\Reporting\Severity;

/**
 * @covers PointingBreed\ReportGenerator\ReportFromCheckstyleGenerator
 */
class ReportFromCheckstyleGeneratorTest extends TestCase
{
    public function testItShouldParseReportsFromAnCheckstyleFile()
    {
        $filePath  = $this->provideCheckstyleFile();
        $underTest = new ReportFromCheckstyleGenerator(__DIR__ . '/../..');

        $reports = $underTest->parse($filePath);

        $this->assertCount(3, $reports);
        $this->assertInstanceOf(Report::class, $reports[0]);
        $this->assertInstanceOf(Report::class, $reports[1]);
        $this->assertInstanceOf(Report::class, $reports[2]);
        $this->assertEquals('Line exceeds 120 characters; contains 127 characters', $reports[0]->toText());
        $this->assertEquals('src/Console/CommitOption.php', $reports[0]->toFile());
        $this->assertEquals(7, $reports[0]->toReportLine());
        $this->assertEquals('Opening brace should be on a new line', $reports[1]->toText());
        $this->assertEquals('src/Console/CommitOption.php', $reports[1]->toFile());
        $this->assertEquals(13, $reports[1]->toReportLine());
        $this->assertEquals('Expected 1 newline at end of file; 0 found', $reports[2]->toText());
        $this->assertEquals('src/Console/CommitOption.php', $reports[2]->toFile());
        $this->assertEquals(16, $reports[2]->toReportLine());
    }

    /**
     * @return string path to checkstyle file
     */
    private function provideCheckstyleFile()
    {
        $baseDir = realpath(__DIR__ . '/../../..');
        $result  = __DIR__ . '/assets/tmp/checkstyle.xml';

        copy(__DIR__ . '/assets/checkstyle.xml', $result);
        file_put_contents(
            $result,
            str_replace('/path/to/project', $baseDir, file_get_contents($result))
        );
        return $result;
    }

    public function testItShouldUseTheSeverityFromTheCheckstyleFileAsReportWhenNoneGiven()
    {
        $filePath  = $this->provideCheckstyleFile();
        $underTest = new ReportFromCheckstyleGenerator(__DIR__ . '/../..');

        $reports = $underTest->parse($filePath);
        $this->assertEquals(Severity::warning(), $reports[0]->toSeverity());
        $this->assertEquals(Severity::error(), $reports[1]->toSeverity());
        $this->assertEquals(Severity::error(), $reports[2]->toSeverity());
    }

    public function testItShouldOverwriteTheSeverityFromTheCheckstyleFileWithAGivenSeverity()
    {
        $filePath  = $this->provideCheckstyleFile();
        $underTest = new ReportFromCheckstyleGenerator(__DIR__ . '/../..');

        $reports = $underTest->parse($filePath, Severity::notice());
        $this->assertEquals(Severity::notice(), $reports[0]->toSeverity());
        $this->assertEquals(Severity::notice(), $reports[1]->toSeverity());
        $this->assertEquals(Severity::notice(), $reports[2]->toSeverity());
    }
}
