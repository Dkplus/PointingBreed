<?php
namespace PointingBreed\Console;

use PointingBreed\ReportGenerator\ReportFromCheckstyleGenerator;
use PointingBreed\Reporting\ReporterFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReportCheckstyleCommand extends Command
{
    /** @var ReporterFactory */
    private $reporters;

    /** @var ReportFromCheckstyleGenerator */
    private $reportGenerator;

    public function __construct(ReportFromCheckstyleGenerator $reportGenerator, ReporterFactory $reporters)
    {
        parent::__construct('report:checkstyle');

        $this->setDefinition([new InputArgument('checkstyle-xml', InputArgument::REQUIRED)]);
        $this->reporters       = $reporters;
        $this->reportGenerator = $reportGenerator;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $reports  = $this->reportGenerator->parse($input->getArgument('checkstyle-xml'));
        $reporter = $this->reporters->authenticate($input->getOptions());
        $reporter->report($reports);
    }
}
