<?php
namespace PointingBreed\Listener;

use PointingBreed\GlobalOptions;
use Symfony\Component\Console\Event\ConsoleEvent;

/**
 * Autodetects commit sha from the environment.
 *
 * Gets the commit sha from the current commit and the last commit before the merge request from the env
 * and puts it as options into the input.
 */
final class AutodetectCommitsListener
{
    public function __invoke(ConsoleEvent $event)
    {
        if (getenv('CI_BUILD_REF') && ! $event->getInput()->hasOption(GlobalOptions::COMMIT_SHA)) {
            $event->getInput()->setOption(GlobalOptions::COMMIT_SHA, getenv('CI_BUILD_REF'));
        }
        if (getenv('CI_BUILD_BEFORE_SHA') && ! $event->getInput()->hasOption(GlobalOptions::COMMIT_SHA_BEFORE)) {
            $event->getInput()->setOption(GlobalOptions::COMMIT_SHA_BEFORE, getenv('CI_BUILD_BEFORE_SHA'));
        }
    }
}
