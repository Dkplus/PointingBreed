<?php
namespace PointingBreed\Console;

use Gitlab\Client as GitlabClient;
use PointingBreed\GlobalOptions;
use SebastianBergmann\Diff\Line;
use SebastianBergmann\Diff\Parser;
use SebastianBergmann\Git\Git;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class CheckCodeSnifferResultCommand extends Command
{
    public function __construct()
    {
        parent::__construct('pointingbreed:code-sniffer:check');

        $this->setDefinition([
            new InputArgument('checkstyle.xml', InputArgument::REQUIRED),
            new InputOption(GlobalOptions::COMMIT_SHA, 'c', InputOption::VALUE_REQUIRED),
            new InputOption(GlobalOptions::COMMIT_SHA_BEFORE, 'b', InputOption::VALUE_REQUIRED),
            new InputOption(GlobalOptions::GITLAB_URL, 'u', InputOption::VALUE_REQUIRED),        // environment
            new InputOption(GlobalOptions::GITLAB_TOKEN, 't', InputOption::VALUE_REQUIRED), // environment
            new InputOption(GlobalOptions::GITLAB_PROJECT_ID, 'id', InputOption::VALUE_REQUIRED), // environment+api
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $xml = simplexml_load_file($input->getArgument('checkstyle.xml'));
        $errors = [];
        foreach ($xml->file as $file) {
            foreach ($file->error as $violation) {
                $errors[] = [
                    'file' => (string) $file['name'],
                    'line' => (string) $violation['line'],
                    'message' => (string) $violation['message'],
                ];
            }
        }

        $git = new Git(getcwd());
        $diff = (new Parser())->parse($git->getDiff($input->getArgument('sha-now'), $input->getArgument('sha-before')));

        $changedLines = [];
        foreach ($diff as $eachDiff) {
            $file = getcwd() . substr($eachDiff->getTo(), 1);
            if (! array_key_exists($file, $changedLines)) {
                $changedLines[$file] = [];
            }

            foreach ($eachDiff->getChunks() as $eachChunk) {
                $currentLineNumber = $eachChunk->getStart();
                foreach ($eachChunk->getLines() as $eachLine) {
                    /* @var $eachLine Line */
                    if ($eachLine->getType() === Line::ADDED) {
                        $changedLines[$file][] = $currentLineNumber;
                    }

                    if ($eachLine->getType() !== Line::REMOVED) {
                        ++$currentLineNumber;
                    }
                }
            }
            $changedLines[$file] = array_unique($changedLines[$file]);
        }

        $pointableErrors = [];
        foreach ($errors as $each) {
            if (! isset($changedLines[$each['file']])) {
                continue;
            }
            if (! in_array($each['line'], $changedLines[$each['file']])) {
                continue;
            }
            $pointableErrors[] = $each;
        }

        $gitlab = new GitlabClient($input->getArgument('gitlab-url'));
        $gitlab->authenticate($input->getArgument('gitlab-auth-token'), GitlabClient::AUTH_URL_TOKEN);
        foreach ($pointableErrors as $each) {
            $gitlab->repositories->createCommitComment(
                $input->getArgument('gitlab-project-id'),
                $input->getArgument('sha-now'),
                $each['message'],
                [
                    'path' => substr($each['file'], strlen(getcwd() . '/')),
                    'line' => $each['line'],
                    'line_type' => 'new'
                ]
            );
        }
    }
}
