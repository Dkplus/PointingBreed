<?php
namespace PointingBreed\Reporting;

interface Format
{
    /**
     * @param Report $report
     * @return string
     */
    public function apply(Report $report);
}
