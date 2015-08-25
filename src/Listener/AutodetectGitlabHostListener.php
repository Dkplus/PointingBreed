<?php
namespace PointingBreed\Listener;

use PointingBreed\Console\AutodetectInputEvent;
use PointingBreed\Console\GitlabHostOption;

/**
 * Autodetect the gitlab-host from CI_BUILD_REPO environment variable.
 */
final class AutodetectGitlabHostListener
{
    public function __invoke(AutodetectInputEvent $event)
    {
        if ($event->getInput()->getOption(GitlabHostOption::NAME) !== null) {
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
