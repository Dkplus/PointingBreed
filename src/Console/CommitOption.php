<?php
namespace PointingBreed\Console;

use Symfony\Component\Console\Input\InputOption;

class CommitOption extends InputOption
{
    const NAME = 'commit';

    public function __construct()
    {
        parent::__construct(self::NAME, 'c', self::VALUE_REQUIRED, 'HEAD commit');
    }
}
