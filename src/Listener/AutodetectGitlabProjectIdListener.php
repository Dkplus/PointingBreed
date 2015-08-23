<?php
namespace PointingBreed\Listener;

use PointingBreed\Console\GitlabProjectIdOption;
use Symfony\Component\Console\Event\ConsoleEvent;

/**
 * Autodetect the gitlab-url from CI_BUILD_REPO environment variable.
 */
final class AutodetectGitlabProjectIdListener
{
    public function __invoke(ConsoleEvent $event)
    {
        if ($event->getInput()->hasOption(GitlabProjectIdOption::NAME)) {
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
        $event->getInput()->setOption(GitlabProjectIdOption::NAME, $namespace . '%2F' . $project);
    }
}
