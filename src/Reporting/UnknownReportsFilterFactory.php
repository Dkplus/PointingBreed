<?php
namespace PointingBreed\Reporting;

use PointingBreed\Console\CommitBeforeOption;
use PointingBreed\Console\CommitOption;
use PointingBreed\Console\FilterOption;
use PointingBreed\Git\CommitReference;
use PointingBreed\Git\Git;

final class UnknownReportsFilterFactory implements ReporterFactory
{
    /** @var Git */
    private $git;

    /** @var ReporterFactory */
    private $decorated;

    public function __construct(Git $git, ReporterFactory $decorated)
    {
        $this->git       = $git;
        $this->decorated = $decorated;
    }

    /**
     * @param array $options
     * @return Reporter
     * @throws NoReporterFoundException on missing options
     */
    public function authenticate(array $options)
    {
        $decoratedReport = $this->decorated->authenticate($options);
        if (! isset($options[CommitOption::NAME])
            || ! isset($options[CommitBeforeOption::NAME])
        ) {
            return $decoratedReport;
        }

        if (! isset($options[FilterOption::NAME])
            || $options[FilterOption::NAME] !== 'introduced'
        ) {
            return $decoratedReport;
        }

        return new UnknownReportsFilter(
            $this->git,
            new CommitReference($options[CommitOption::NAME]),
            new CommitReference($options[CommitBeforeOption::NAME]),
            $this->decorated->authenticate($options)
        );
    }
}
