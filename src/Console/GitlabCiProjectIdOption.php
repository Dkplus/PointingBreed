<?php
namespace PointingBreed\Console;

use Symfony\Component\Console\Input\InputOption;

class GitlabCiProjectIdOption extends InputOption
{
    const NAME = 'gitlab-ci-project-id';

    public function __construct()
    {
        parent::__construct(self::NAME, 'ci', self::VALUE_REQUIRED, 'Project id at GitLab CI');
    }
}
