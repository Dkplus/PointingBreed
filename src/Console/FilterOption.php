<?php
namespace PointingBreed\Console;

use Symfony\Component\Console\Input\InputOption;

class FilterOption extends InputOption
{
    const NAME = 'filter';

    public function __construct()
    {
        parent::__construct(self::NAME, 'f', self::VALUE_REQUIRED, 'Implemented filter is ‘introduced’');
    }
}
