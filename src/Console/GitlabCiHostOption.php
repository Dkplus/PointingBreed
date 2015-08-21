<?php
namespace PointingBreed\Console;

use Symfony\Component\Console\Input\InputOption;

final class GitlabCiHostOption extends InputOption
{
    const NAME = 'gitlab-ci-host';

    public function __construct()
    {
        parent::__construct(self::NAME, 'ch', self::VALUE_REQUIRED, 'GitLab CI Host URL');
    }
}
