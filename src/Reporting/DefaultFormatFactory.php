<?php
namespace PointingBreed\Reporting;

final class DefaultFormatFactory implements FormatFactory
{
    /** @var Format */
    private $defaultFormat;

    public function __construct(Format $defaultFormat)
    {
        $this->defaultFormat = $defaultFormat;
    }

    public function create(array $options)
    {
        return $this->defaultFormat;
    }
}
