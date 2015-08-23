<?php
namespace PointingBreed\Reporting;

interface Reporter
{
    /**
     * @param Report[] $reports
     * @return void
     */
    public function report(array $reports);
}
