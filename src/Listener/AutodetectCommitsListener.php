<?php
namespace PointingBreed\Listener;

use PointingBreed\Console\AutodetectInputEvent;
use PointingBreed\Console\CommitBeforeOption;
use PointingBreed\Console\CommitOption;

/**
 * Autodetects commit sha from the environment.
 *
 * Gets the commit sha from the current commit and the last commit before the merge request from the env
 * and puts it as options into the input.
 */
final class AutodetectCommitsListener
{
    public function __invoke(AutodetectInputEvent $event)
    {
        if (getenv('CI_BUILD_REF') && $event->getInput()->getOption(CommitOption::NAME) === null) {
            $event->getInput()->setOption(CommitOption::NAME, getenv('CI_BUILD_REF'));
        }
        if (getenv('CI_BUILD_BEFORE_SHA') && $event->getInput()->getOption(CommitBeforeOption::NAME) === null) {
            $event->getInput()->setOption(CommitBeforeOption::NAME, getenv('CI_BUILD_BEFORE_SHA'));
        }
    }
}
