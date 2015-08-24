<?php
namespace PointingBreed\Reporting;

use InvalidArgumentException;

class NoReporterFoundException extends InvalidArgumentException
{
    public function __construct()
    {
        // @todo Add link to reporter documentation
        parent::__construct('Could not find a reporter. Did you pass the right options?');
    }
}
