<?php
namespace PointingBreedTest\Gitlab;

use Gitlab\Api\Repositories;
use Gitlab\Client;
use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Git\CommitReference;
use PointingBreed\Gitlab\GitlabReporter;
use PointingBreed\Reporting\Format;
use PointingBreed\Reporting\Report;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @covers PointingBreed\Gitlab\GitlabReporter
 */
class GitlabReporterTest extends TestCase
{
    public function testItShouldCreateCommitsToTheGivenRepository()
    {
        $repositories = $this->aRepositories();
        $client       = $this->aGitlabClientForRepositories($repositories);
        $report       = $this->aReportForTheCompleteCommit();
        $format       = $this->aFormat($report, 'abcd');
        $underTest    = new GitlabReporter(
            $format->reveal(),
            $client->reveal(),
            new CommitReference('a1b2'),
            'dkplus/pointing-breed'
        );

        $underTest->report([$report->reveal()]);

        $repositories
            ->createCommitComment('dkplus/pointing-breed', 'a1b2', 'abcd', [])
            ->shouldHaveBeenCalled();
    }

    public function testItShouldCreateCommitsToAFile()
    {
        $repositories = $this->aRepositories();
        $client       = $this->aGitlabClientForRepositories($repositories);
        $report       = $this->aReportForASingleFile('src/Gitlab/GitlabReporter.php');
        $format       = $this->aFormat($report, 'abcd');
        $underTest    = new GitlabReporter(
            $format->reveal(),
            $client->reveal(),
            new CommitReference('a1b2'),
            'dkplus/pointing-breed'
        );

        $underTest->report([$report->reveal()]);

        $repositories->createCommitComment(
            'dkplus/pointing-breed',
            'a1b2',
            'abcd',
            [
                'path'      => 'src/Gitlab/GitlabReporter.php',
                'line'      => 1,
                'line_type' => 'new'
            ]
        )->shouldHaveBeenCalled();
    }
    public function testItShouldCreateCommitsToASingleLine()
    {
        $repositories = $this->aRepositories();
        $client       = $this->aGitlabClientForRepositories($repositories);
        $report       = $this->aReportForASingleLine('src/Gitlab/GitlabReporter.php', 5);
        $format       = $this->aFormat($report, 'abcd');
        $underTest    = new GitlabReporter(
            $format->reveal(),
            $client->reveal(),
            new CommitReference('a1b2'),
            'dkplus/pointing-breed'
        );

        $underTest->report([$report->reveal()]);

        $repositories->createCommitComment(
            'dkplus/pointing-breed',
            'a1b2',
            'abcd',
            [
                'path'      => 'src/Gitlab/GitlabReporter.php',
                'line'      => 5,
                'line_type' => 'new'
            ]
        )->shouldHaveBeenCalled();
    }

    /**
     * @param ObjectProphecy $repositories
     * @return ObjectProphecy
     */
    private function aGitlabClientForRepositories(ObjectProphecy $repositories)
    {
        $client = $this->prophesize(Client::class);
        $client->api('repositories')->willReturn($repositories->reveal());
        return $client;
    }

    /** @return ObjectProphecy */
    private function aRepositories()
    {
        return $this->prophesize(Repositories::class);
    }

    /**
     * @param ObjectProphecy $report
     * @param string         $formatResult The result of the format for the given report
     * @return ObjectProphecy
     */
    private function aFormat(ObjectProphecy $report, $formatResult)
    {
        $result = $this->prophesize(Format::class);
        $result->apply($report->reveal())->willReturn($formatResult);
        return $result;
    }

    /** @return ObjectProphecy */
    private function aReportForTheCompleteCommit()
    {
        return $this->prophesize(Report::class);
    }

    /**
     * @param string $filePath
     * @return ObjectProphecy
     */
    private function aReportForASingleFile($filePath)
    {
        $result = $this->prophesize(Report::class);
        $result->toReportLine()->willReturn(1);
        $result->toFile()->willReturn($filePath);
        return $result;
    }

    /**
     * @param string $filePath
     * @param int    $line
     * @return ObjectProphecy
     */
    private function aReportForASingleLine($filePath, $line)
    {
        $result = $this->prophesize(Report::class);
        $result->toFile()->willReturn($filePath);
        $result->toReportLine()->willReturn($line);
        return $result;
    }
}
