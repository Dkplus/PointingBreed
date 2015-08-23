<?php
namespace PointingBreed\Gitlab;

use Gitlab\Client;
use PointingBreed\Console\CommitOption;
use PointingBreed\Console\GitlabHostOption;
use PointingBreed\Console\GitlabPrivateTokenOption;
use PointingBreed\Console\GitlabProjectIdOption;
use PointingBreed\Git\CommitReference;
use PointingBreed\Reporting\EmojiFormat;
use PointingBreed\Reporting\FormatFactory;
use PointingBreed\Reporting\NoReporterFoundException;
use PointingBreed\Reporting\Reporter;
use PointingBreed\Reporting\ReporterFactory;

final class GitlabReporterFactory implements ReporterFactory
{
    /** @var FormatFactory */
    private $formatFactory;

    public function __construct(FormatFactory $formatFactory)
    {
        $this->formatFactory = $formatFactory;
    }

    /**
     * @param array $options
     * @return Reporter
     * @throws NoReporterFoundException on missing options
     */
    public function authenticate(array $options)
    {
        $requiredOptions = [
            GitlabHostOption::NAME,
            GitlabPrivateTokenOption::NAME,
            CommitOption::NAME,
            GitlabProjectIdOption::NAME
        ];
        foreach ($requiredOptions as $each) {
            if (! isset($options[$each])) {
                throw new NoReporterFoundException();
            }
        }

        $client = new Client($options[GitlabHostOption::NAME]);
        $client->authenticate($options[GitlabPrivateTokenOption::NAME], Client::AUTH_URL_TOKEN);
        return new GitlabReporter(
            $this->formatFactory->create($options),
            $client,
            new CommitReference($options[CommitOption::NAME]),
            $options[GitlabProjectIdOption::NAME]
        );
    }
}
