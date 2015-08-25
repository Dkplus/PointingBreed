<?php
namespace PointingBreed\Listener;

use PointingBreed\Console\AutodetectInputEvent;
use PointingBreed\Console\GitlabPrivateTokenOption;

/**
 * Autodetect the gitlab private token from GITLAB_PRIVATE_TOKEN environment variable.
 */
final class AutodetectGitlabPrivateTokenListener
{
    public function __invoke(AutodetectInputEvent $event)
    {
        if ($event->getInput()->getOption(GitlabPrivateTokenOption::NAME) !== null) {
            return;
        }
        if (! getenv('GITLAB_PRIVATE_TOKEN')) {
            return;
        }

        $event->getInput()->setOption(GitlabPrivateTokenOption::NAME, getenv('GITLAB_PRIVATE_TOKEN'));
    }
}
