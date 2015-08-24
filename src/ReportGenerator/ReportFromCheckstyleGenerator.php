<?php
namespace PointingBreed\ReportGenerator;

use PointingBreed\Reporting\Report;
use PointingBreed\Reporting\Severity;

class ReportFromCheckstyleGenerator
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
     * @param string        $pathToCheckstyleXml
     * @param Severity|null $severity
     * @return Report[]
     */
    public function parse($pathToCheckstyleXml, Severity $severity = null)
    {
        $xml    = simplexml_load_file($pathToCheckstyleXml);
        $result = [];
        foreach ($xml->file as $file) {
            foreach ($file->error as $violation) {
                $eachSeverity = $severity;
                if ($eachSeverity === null) {
                    $eachSeverity = strval($violation['severity']) === 'error'
                        ? Severity::error()
                        : Severity::warning();
                }
                $result[] = Report::forLine(
                    (string) $violation['message'],
                    $eachSeverity,
                    substr(strval($file['name']), strlen($this->repositoryBaseDir . '/')),
                    (int) $violation['line']
                );
            }
        }
        return $result;
    }
}
