<?php
namespace PointingBreed\Reporting;

use Assert\Assertion;

class Severity
{
    /** @var int */
    private $severity;

    /** @var Trend|null */
    private $trend;

    public static function error(Trend $trend = null)
    {
        return new self(-3, $trend);
    }

    public static function warning(Trend $trend = null)
    {
        return new self(-2, $trend);
    }

    public static function notice(Trend $trend = null)
    {
        return new self(-1, $trend);
    }

    public static function progress($progress, Trend $trend = null)
    {
        Assertion::min($progress, 0);
        Assertion::max($progress, 5);
        return new self((int) $progress, $trend);
    }

    private function __construct($severity, Trend $trend = null)
    {
        $this->severity = $severity;
        $this->trend    = $trend;
    }

    public function isNotice()
    {
        return $this->severity === -1;
    }

    public function isWarning()
    {
        return $this->severity === -2;
    }

    public function isError()
    {
        return $this->severity === -3;
    }

    public function isProgress()
    {
        return $this->severity > -1
            && $this->severity <= 100;
    }

    public function toProgress()
    {
        return $this->isProgress()
             ? $this->severity
             : null;
    }

    public function isGettingWorse()
    {
        return $this->trend && $this->trend->isGettingWorse();
    }

    public function isGettingBetter()
    {
        return $this->trend && $this->trend->isGettingBetter();
    }
}
