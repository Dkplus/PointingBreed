<?php
namespace PointingBreed\ReportGenerator;

use PointingBreed\Reporting\Report;
use PointingBreed\Reporting\Severity;
use ReflectionClass;

class ReportFromFinalizerGenerator
{
    /** @var string */
    private $repositoryBaseDir;

    /**
     * @param string $repositoryBaseDir Base dir of the directory
     */
    public function __construct($repositoryBaseDir)
    {
        $this->repositoryBaseDir = realpath($repositoryBaseDir);
    }

    /**
     * @param string   $pathToLog
     * @param Severity $severity
     * @return Report[]
     */
    public function parse($pathToLog, Severity $severity)
    {
        $makeFinalMessage   = '`%s` need to be made final.';
        $removeFinalMessage = '`%s` need to be made extensible again.';
        $add                = ' For the reasons see [When to declare classes final]'
                            . '(http://ocramius.github.io/blog/when-to-declare-classes-final/).';
        $makeFinalMessage   .= $add;
        $removeFinalMessage .= $add;

        $message = '';
        $result  = [];
        foreach (file($pathToLog) as $eachLine) {
            $eachLine = trim($eachLine);
            if ($eachLine === 'Following classes need to be made final:') {
                $message = $makeFinalMessage;
                continue;
            }
            if ($eachLine === 'Following classes are final and need to be made extensible again:') {
                $message = $removeFinalMessage;
                continue;
            }
            if (substr($eachLine, 0, 2) === '| ' && substr($eachLine, -2) === ' |') {
                $fqcn = substr($eachLine, 2, -2);
                $reflection = new ReflectionClass($fqcn);
                $result[]   = Report::forFile(
                    sprintf($message, $fqcn),
                    $severity,
                    substr($reflection->getFileName(), (string) strlen($this->repositoryBaseDir . '/')),
                    $reflection->getStartLine()
                );
            }
        }
        return $result;
    }
}
