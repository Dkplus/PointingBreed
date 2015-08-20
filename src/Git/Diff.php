<?php
namespace PointingBreed\Git;

/**
 * Collection of changes between two git commits
 */
class Diff
{
    /** @var array */
    private $changes;

    /**
     * @param array $changes Containing the files as key and an array of lines as value
     */
    public function __construct(array $changes)
    {
        $this->changes = $changes;
    }

    /**
     * @param string $filePath
     * @param int    $line
     * @return boolean
     */
    public function contains($filePath, $line)
    {
        return isset($this->changes[$filePath])
        && in_array((int) $line, $this->changes[$filePath], true);
    }
}
