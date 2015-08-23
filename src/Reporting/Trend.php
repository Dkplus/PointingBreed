<?php
namespace PointingBreed\Reporting;

class Trend
{
    /** @var int */
    private $trend;

    /** @return self */
    public static function pejoration()
    {
        return new self(-1);
    }

    /** @return self */
    public static function improvement()
    {
        return new self(1);
    }

    private function __construct($trend)
    {
        $this->trend = $trend;
    }

    public function isGettingWorse()
    {
        return $this->trend === -1;
    }

    public function isGettingBetter()
    {
        return $this->trend === 1;
    }
}
