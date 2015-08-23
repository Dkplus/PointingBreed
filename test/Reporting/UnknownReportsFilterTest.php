<?php
namespace PointingBreedTest\Reporting;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Git\CommitReference;
use PointingBreed\Git\Diff;
use PointingBreed\Git\Git;
use PointingBreed\Reporting\Report;
use PointingBreed\Reporting\Reporter;
use PointingBreed\Reporting\UnknownReportsFilter;
use Prophecy\Argument;

/**
 * @covers PointingBreed\Reporting\UnknownReportsFilter
 */
class UnknownReportsFilterTest extends TestCase
{
    public function testItShouldNotFilterOutReportsForTheCompleteCommit()
    {
        $commit       = new CommitReference('abcd');
        $commitBefore = new CommitReference('1234');
        $git          = $this->aGit();
        $decorated    = $this->aReporter();
        $reports      = [$this->aCommitReport()->reveal()];
        $underTest    = new UnknownReportsFilter($git->reveal(), $commit, $commitBefore, $decorated->reveal());

        $underTest->report($reports);

        $decorated->report($reports)->shouldHaveBeenCalled();
    }

    public function testItShouldFilterOutReportsForFilesThatHasNotChanged()
    {
        $commit       = new CommitReference('abcd');
        $commitBefore = new CommitReference('1234');
        $git          = $this->aGitWithChangedFile($commit, $commitBefore, 'src/Reporting/UnknownReportsFilter.php', 1);
        $decorated    = $this->aReporter();
        $underTest    = new UnknownReportsFilter($git->reveal(), $commit, $commitBefore, $decorated->reveal());

        $reportOfChangedFile   = $this->aFileReport('src/Reporting/UnknownReportsFilter.php');
        $reportOfUnchangedFile = $this->aFileReport('src/Reporting/Report.php');

        $underTest->report([$reportOfChangedFile->reveal(), $reportOfUnchangedFile->reveal()]);

        $decorated->report([$reportOfChangedFile])->shouldHaveBeenCalled();
    }

    public function testItShouldFilterOutReportsForLinesThatHasNotChanged()
    {
        $commit       = new CommitReference('abcd');
        $commitBefore = new CommitReference('1234');
        $git          = $this->aGitWithChangedFile($commit, $commitBefore, 'src/Reporting/UnknownReportsFilter.php', 1);
        $decorated    = $this->aReporter();
        $underTest    = new UnknownReportsFilter($git->reveal(), $commit, $commitBefore, $decorated->reveal());

        $reportOfChangedLine   = $this->aLineReport('src/Reporting/UnknownReportsFilter.php', 1);
        $reportOfUnchangedLine = $this->aLineReport('src/Reporting/UnknownReportsFilter.php', 2);

        $underTest->report([$reportOfChangedLine->reveal(), $reportOfUnchangedLine->reveal()]);

        $decorated->report([$reportOfChangedLine])->shouldHaveBeenCalled();
    }

    private function aGit()
    {
        return $this->prophesize(Git::class);
    }

    private function aGitWithChangedFile($commit, $commitBefore, $file, $changedLine)
    {
        $diff   = $this->prophesize(Diff::class);
        $result = $this->prophesize(Git::class);
        $result->diff(getcwd(), $commit, $commitBefore)->willReturn($diff->reveal());

        $diff->containsFile($file)->willReturn(true);
        $diff->containsLine($file, $changedLine)->willReturn(true);
        $diff->containsFile(Argument::type('string'))->willReturn(false);
        $diff->containsLine(Argument::type('string'), Argument::type('int'))->willReturn(false);
        return $result;
    }

    private function aReporter()
    {
        return $this->prophesize(Reporter::class);
    }

    private function aCommitReport()
    {
        $report = $this->prophesize(Report::class);
        $report->toFile()->willReturn(null);
        $report->toChangedLine()->willReturn(null);
        return $report;
    }

    private function aFileReport($file)
    {
        $report = $this->prophesize(Report::class);
        $report->toFile()->willReturn($file);
        $report->toChangedLine()->willReturn(null);
        return $report;
    }

    private function aLineReport($file, $line)
    {
        $report = $this->prophesize(Report::class);
        $report->toFile()->willReturn($file);
        $report->toChangedLine()->willReturn($line);
        return $report;
    }
}
