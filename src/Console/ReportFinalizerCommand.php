<?php
namespace PointingBreed\Console;

use PointingBreed\ReportGenerator\ReportFromFinalizerGenerator;
use PointingBreed\Reporting\ReporterFactory;
use PointingBreed\Reporting\Severity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ReportFinalizerCommand extends Command
{
    /** @var EventDispatcherInterface */
    private $events;

    /** @var ReportFromFinalizerGenerator */
    private $reportGenerator;

    /** @var ReporterFactory */
    private $reporters;

    public function __construct(
        EventDispatcherInterface $events,
        ReportFromFinalizerGenerator $reportGenerator,
        ReporterFactory $reporters
    ) {
        parent::__construct('report:finalizer');

        $this->setDefinition([
            new InputArgument('output-log', InputArgument::REQUIRED),
            new InputOption(
                'severity',
                's',
                InputOption::VALUE_REQUIRED,
                'Can be ‘error’, ‘warning’ or ‘notice’. Defaults to ‘notice’'
            )
        ]);
        $this->events          = $events;
        $this->reporters       = $reporters;
        $this->reportGenerator = $reportGenerator;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->events->dispatch(AutodetectInputEvent::NAME, new AutodetectInputEvent($input));

        $severities = ['error' => Severity::error(), 'warning' => Severity::warning()];
        $severity   = Severity::notice();
        if ($input->hasOption('severity') && isset($severities[$input->getOption('severity')])) {
            $severity = $severities[$input->getOption('severity')];
        }

        $reporter = $this->reporters->authenticate($input->getOptions());
        $reports  = $this->reportGenerator->parse($input->getArgument('output-log'), $severity);
        $reporter->report($reports);
    }
}
