<?php
namespace PointingBreed\Gitlab;

use Gitlab\Client;
use PointingBreed\Reporting\Format;
use PointingBreed\Git\CommitReference;
use PointingBreed\Reporting\Report;
use PointingBreed\Reporting\Reporter;

final class GitlabReporter implements Reporter
{
    /** @var Client */
    private $client;

    /** @var CommitReference */
    private $commit;

    /** @var int|string */
    private $projectId;

    /** @var Format */
    private $format;

    public function __construct(Format $format, Client $client, CommitReference $commit, $projectId)
    {
        $this->format    = $format;
        $this->client    = $client;
        $this->commit    = $commit;
        $this->projectId = $projectId;
    }

    public function report(array $reports)
    {
        array_walk($reports, [$this, 'reportSingle']);
    }

    private function reportSingle(Report $report)
    {
        $options = [];
        if ($report->toFile() !== null) {
            $options = [
                'path'      => $report->toFile(),
                'line'      => $report->toReportLine(),
                'line_type' => 'new'
            ];
        }
        $this->client->repositories->createCommitComment(
            $this->projectId,
            (string) $this->commit,
            $this->format->apply($report),
            $options
        );
    }
}
