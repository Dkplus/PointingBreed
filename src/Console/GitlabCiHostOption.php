<?php
namespace PointingBreed\Console;

use Symfony\Component\Console\Input\InputOption;

class GitlabCiHostOption extends InputOption
{
    const NAME = 'gitlab-ci-host';

    public function __construct()
    {
        parent::__construct(self::NAME, 'cu', self::VALUE_REQUIRED, 'GitLab CI Host URL');
    }
}
