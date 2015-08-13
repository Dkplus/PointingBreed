<?php
namespace PointingBreed\Git;

use InvalidArgumentException;

class CommitReference
{
    /** @var string */
    private $value;

    /**
     * @param string $sha
     * @return self
     *
     * @throws InvalidArgumentException on invalid sha
     */
    public static function fromNative($sha)
    {
        if (! preg_match('/^[a-f0-9]{6}$/', $sha)
            && ! preg_match('/^[a-f0-9]{40}$/', $sha)
        ) {
            throw new InvalidArgumentException(sprintf('“%s” seems to be no valid git commit reference', $sha));
        }

        return new self($sha);
    }

    /** @param string $value */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /** @return string */
    public function toNative()
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
