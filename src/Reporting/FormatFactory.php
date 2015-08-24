<?php
namespace PointingBreed\Reporting;

interface FormatFactory
{
    /**
     * @param array $options
     * @return Format
     */
    public function create(array $options);
}
