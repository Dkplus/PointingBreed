<?php
namespace PointingBreed\Reporting;

class Report
{
    /** @var Severity */
    private $severity;

    /** @var string */
    private $text;

    /** @var null|string */
    private $file;

    /** @var null|int */
    private $reportLine;

    /** @var null|int */
    private $changedLine;

    public static function forCommit($text, Severity $severity)
    {
        return new self($text, $severity);
    }

    public static function forFile($text, Severity $severity, $path, $reportLine = null)
    {
        return new self($text, $severity, $path, $reportLine ?: 1);
    }

    public static function forLine($text, Severity $severity, $path, $line)
    {
        return new self($text, $severity, $path, $line, $line);
    }

    private function __construct($text, Severity $severity, $file = null, $reportLine = null, $changedLine = null)
    {
        $this->severity    = $severity;
        $this->text        = $text;
        $this->file        = $file;
        $this->reportLine  = $reportLine;
        $this->changedLine = $changedLine;
    }

    /** @return string */
    public function toText()
    {
        return $this->text;
    }

    /** @return null|string */
    public function toFile()
    {
        return $this->file;
    }

    /** @return null|int */
    public function toReportLine()
    {
        return $this->reportLine;
    }

    /** @return int|null */
    public function toChangedLine()
    {
        return $this->changedLine;
    }

    /** @return Severity */
    public function toSeverity()
    {
        return $this->severity;
    }
}
