<?php
namespace PointingBreed\Reporting;

use PointingBreed\Git\CommitReference;
use PointingBreed\Git\Git;

final class UnknownReportsFilter implements Reporter
{
    /** @var Git */
    private $git;

    /** @var CommitReference */
    private $commit;

    /** @var CommitReference */
    private $commitBefore;

    /** @var Reporter */
    private $decorated;

    /**
     * @param Git             $git
     * @param CommitReference $commit
     * @param CommitReference $commitBefore
     * @param Reporter        $decorated
     */
    public function __construct(Git $git, CommitReference $commit, CommitReference $commitBefore, Reporter $decorated)
    {
        $this->git          = $git;
        $this->commit       = $commit;
        $this->commitBefore = $commitBefore;
        $this->decorated    = $decorated;
    }

    public function report(array $reports)
    {
        $diff = $this->git->diff(
            getcwd(),
            $this->commit,
            $this->commitBefore
        );

        $reports = array_filter($reports, function (Report $report) use ($diff) {
            if ($report->toFile() === null) {
                return true;
            }
            if ($report->toChangedLine() === null) {
                return $diff->containsFile($report->toFile());
            }
            return $diff->containsLine($report->toFile(), $report->toChangedLine());
        });

        $this->decorated->report($reports);
    }
}
