<?php
namespace PointingBreed\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\EventDispatcher\Event;

class AutodetectInputEvent extends Event
{
    const NAME = 'console.autodetect';

    /** @var InputInterface */
    private $input;

    public function __construct(InputInterface $input)
    {
        $this->input = $input;
    }

    /** @return InputInterface */
    public function getInput()
    {
        return $this->input;
    }
}
