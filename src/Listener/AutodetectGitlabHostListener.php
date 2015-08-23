<?php
namespace PointingBreed\Listener;

use PointingBreed\Console\GitlabHostOption;
use Symfony\Component\Console\Event\ConsoleEvent;

/**
 * Autodetect the gitlab-host from CI_BUILD_REPO environment variable.
 */
final class AutodetectGitlabHostListener
{
    public function __invoke(ConsoleEvent $event)
    {
        if ($event->getInput()->hasOption(GitlabHostOption::NAME)) {
            return;
        }
        if (! getenv('CI_BUILD_REPO')) {
            return;
        }

        $urlParts = parse_url(getenv('CI_BUILD_REPO'));
        if (! $urlParts || ! isset($urlParts['scheme']) || ! isset($urlParts['host']) || ! isset($urlParts['path'])) {
            return;
        }
        if (! preg_match('/(.*)\/.*\/.*\.git/', $urlParts['path'], $matches)) {
            return;
        }

        $pathWithoutRepo = $matches[1];
        $url = $urlParts['scheme'] . '://' . $urlParts['host'] . $pathWithoutRepo . '/api/v3/';
        $event->getInput()->setOption(GitlabHostOption::NAME, $url);
    }
}
