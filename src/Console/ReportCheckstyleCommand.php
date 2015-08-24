<?php
namespace PointingBreed\Console;

use PointingBreed\ReportGenerator\ReportFromCheckstyleGenerator;
use PointingBreed\Reporting\ReporterFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ReportCheckstyleCommand extends Command
{
    /** @var EventDispatcherInterface */
    private $events;

    /** @var ReporterFactory */
    private $reporters;

    /** @var ReportFromCheckstyleGenerator */
    private $reportGenerator;

    public function __construct(
        EventDispatcherInterface $events,
        ReportFromCheckstyleGenerator $reportGenerator,
        ReporterFactory $reporters
    ) {
        parent::__construct('report:checkstyle');

        $this->setDefinition([new InputArgument('checkstyle-xml', InputArgument::REQUIRED)]);
        $this->events          = $events;
        $this->reporters       = $reporters;
        $this->reportGenerator = $reportGenerator;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->events->dispatch(AutodetectInputEvent::NAME, new AutodetectInputEvent($input));

        $reports  = $this->reportGenerator->parse($input->getArgument('checkstyle-xml'));
        $reporter = $this->reporters->authenticate($input->getOptions());
        $reporter->report($reports);
    }
}
