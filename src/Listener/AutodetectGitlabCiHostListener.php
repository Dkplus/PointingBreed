<?php
namespace PointingBreed\Listener;

use PointingBreed\Console\AutodetectInputEvent;
use PointingBreed\Console\GitlabCiHostOption;

/**
 * Autodetect the gitlab-ci-host from GITLAB_CI_URL environment variable.
 */
final class AutodetectGitlabCiHostListener
{
    public function __invoke(AutodetectInputEvent $event)
    {
        if ($event->getInput()->hasOption(GitlabCiHostOption::NAME)) {
            return;
        }
        if (! getenv('GITLAB_CI_URL')) {
            return;
        }

        $event->getInput()->setOption(GitlabCiHostOption::NAME, getenv('GITLAB_CI_URL'));
    }
}
