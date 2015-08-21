<?php
namespace PointingBreed\Console;

use Symfony\Component\Console\Input\InputOption;

final class GitlabPrivateTokenOption extends InputOption
{
    const NAME = 'gitlab-private-token';

    public function __construct()
    {
        parent::__construct(self::NAME, 't', self::VALUE_REQUIRED, 'GitLab private token');
    }
}
