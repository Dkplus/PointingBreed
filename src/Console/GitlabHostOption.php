<?php
namespace PointingBreed\Console;

use Symfony\Component\Console\Input\InputOption;

final class GitlabHostOption extends InputOption
{
    const NAME = 'gitlab-host';

    public function __construct()
    {
        parent::__construct(self::NAME, 'h', self::VALUE_REQUIRED, 'GitLab Host URL');
    }
}
