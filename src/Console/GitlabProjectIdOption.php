<?php
namespace PointingBreed\Console;

use Symfony\Component\Console\Input\InputOption;

class GitlabProjectIdOption extends InputOption
{
    const NAME = 'gitlab-project-id';

    public function __construct()
    {
        parent::__construct(self::NAME, 'i', self::VALUE_REQUIRED, 'Project id at GitLab');
    }
}
