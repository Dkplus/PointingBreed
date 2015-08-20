<?php
namespace PointingBreed\Git;

interface Git
{
    /**
     * @param string $pathToRepository
     * @param CommitReference $shaFrom
     * @param CommitReference $shaTo
     * @return Diff
     */
    public function diff($pathToRepository, CommitReference $shaFrom, CommitReference $shaTo);
}
