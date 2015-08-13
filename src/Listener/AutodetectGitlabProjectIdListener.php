<?php
namespace PointingBreed\Listener;

use PointingBreed\GlobalOptions;
use Symfony\Component\Console\Event\ConsoleEvent;

/**
 * Autodetect the gitlab-url from CI_BUILD_REPO environment variable.
 */
final class AutodetectGitlabProjectIdListener
{
    public function __invoke(ConsoleEvent $event)
    {
        if ($event->getInput()->hasOption(GlobalOptions::GITLAB_PROJECT_ID)) {
            return;
        }
        if (! getenv('CI_BUILD_REPO')) {
            return;
        }

        $urlParts = parse_url(getenv('CI_BUILD_REPO'));
        if (! $urlParts || ! isset($urlParts['path'])) {
            return;
        }
        if (! preg_match('/.*\/(.*)\/(.*)\.git/', $urlParts['path'], $matches)) {
            return;
        }

        $namespace = $matches[1];
        $project   = $matches[2];
        $event->getInput()->setOption(GlobalOptions::GITLAB_PROJECT_ID, $namespace . '%2F' . $project);
    }
}
