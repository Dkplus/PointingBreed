<?php
namespace PointingBreed\Reporting;

interface ReporterFactory
{
    /**
     * @param array $options
     * @return Reporter
     * @throws NoReporterFoundException on missing options
     */
    public function authenticate(array $options);
}
