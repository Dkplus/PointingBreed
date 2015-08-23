<?php
namespace PointingBreed\Listener;

use PointingBreed\Console\GitlabCiHostOption;
use Symfony\Component\Console\Event\ConsoleEvent;

/**
 * Autodetect the gitlab-ci-host from GITLAB_CI_URL environment variable.
 */
final class AutodetectGitlabCiHostListener
{
    public function __invoke(ConsoleEvent $event)
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
