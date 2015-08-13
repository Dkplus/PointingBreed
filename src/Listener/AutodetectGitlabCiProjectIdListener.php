<?php
namespace PointingBreed\Listener;

use PointingBreed\GlobalOptions;
use Symfony\Component\Console\Event\ConsoleEvent;

/**
 * Autodetect the gitlab ci project id from CI_PROJECT_ID environment variable.
 */
final class AutodetectGitlabCiProjectIdListener
{
    public function __invoke(ConsoleEvent $event)
    {
        if ($event->getInput()->hasOption(GlobalOptions::GITLAB_CI_PROJECT_ID)) {
            return;
        }
        if (! getenv('CI_PROJECT_ID')) {
            return;
        }

        $event->getInput()->setOption(GlobalOptions::GITLAB_CI_PROJECT_ID, getenv('CI_PROJECT_ID'));
    }
}
