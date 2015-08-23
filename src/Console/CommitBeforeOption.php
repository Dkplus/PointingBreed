<?php
namespace PointingBreed\Console;

use Symfony\Component\Console\Input\InputOption;

class CommitBeforeOption extends InputOption
{
    const NAME = 'commit-before';

    public function __construct()
    {
        parent::__construct(self::NAME, 'b', self::VALUE_REQUIRED, 'Commit before this build');
    }
}
