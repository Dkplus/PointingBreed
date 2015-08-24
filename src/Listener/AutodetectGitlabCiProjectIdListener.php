<?php
namespace PointingBreed\Listener;

use PointingBreed\Console\AutodetectInputEvent;
use PointingBreed\Console\GitlabCiProjectIdOption;

/**
 * Autodetect the gitlab ci project id from CI_PROJECT_ID environment variable.
 */
final class AutodetectGitlabCiProjectIdListener
{
    public function __invoke(AutodetectInputEvent $event)
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
