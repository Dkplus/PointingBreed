<?php
namespace PointingBreed\Listener;

use PointingBreed\Console\GitlabCiProjectIdOption;
use Symfony\Component\Console\Event\ConsoleEvent;

/**
 * Autodetect the gitlab ci project id from CI_PROJECT_ID environment variable.
 */
final class AutodetectGitlabCiProjectIdListener
{
    public function __invoke(ConsoleEvent $event)
    {
        if ($event->getInput()->hasOption(GitlabCiProjectIdOption::NAME)) {
            return;
        }
        if (! getenv('CI_PROJECT_ID')) {
            return;
        }

        $event->getInput()->setOption(GitlabCiProjectIdOption::NAME, getenv('CI_PROJECT_ID'));
    }
}
