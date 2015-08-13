<?php
namespace PointingBreed\Listener;

use PointingBreed\GlobalOptions;
use Symfony\Component\Console\Event\ConsoleEvent;

/**
 * Autodetect the gitlab-url from GITLAB_CI_URL environment variable.
 */
final class AutodetectGitlabCiUrlListener
{
    public function __invoke(ConsoleEvent $event)
    {
        if ($event->getInput()->hasOption(GlobalOptions::GITLAB_CI_URL)) {
            return;
        }
        if (! getenv('GITLAB_CI_URL')) {
            return;
        }

        $event->getInput()->setOption(GlobalOptions::GITLAB_CI_URL, getenv('GITLAB_CI_URL'));
    }
}
