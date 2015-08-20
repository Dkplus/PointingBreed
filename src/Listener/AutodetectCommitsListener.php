<?php
namespace PointingBreed\Listener;

use PointingBreed\Console\CommitBeforeOption;
use PointingBreed\Console\CommitOption;
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
        if (getenv('CI_BUILD_REF') && ! $event->getInput()->hasOption(CommitOption::NAME)) {
            $event->getInput()->setOption(CommitOption::NAME, getenv('CI_BUILD_REF'));
        }
        if (getenv('CI_BUILD_BEFORE_SHA') && ! $event->getInput()->hasOption(CommitBeforeOption::NAME)) {
            $event->getInput()->setOption(CommitBeforeOption::NAME, getenv('CI_BUILD_BEFORE_SHA'));
        }
    }
}
