<?php
namespace PointingBreed\Git;

use SebastianBergmann\Diff\Line;
use SebastianBergmann\Diff\Parser;
use SebastianBergmann\Git\Git as GitBySebastian;

/**
 * Implementation of Git using sebastian/git and sebastian/diff.
 */
final class SebastianGit implements Git
{
    /**
     * @param string $pathToRepository
     * @param CommitReference $shaFrom
     * @param CommitReference $shaTo
     * @return Diff
     */
    public function diff($pathToRepository, CommitReference $shaFrom, CommitReference $shaTo)
    {
        $git  = new GitBySebastian($pathToRepository);
        $diff = (new Parser())->parse(
            $git->getDiff($shaFrom, $shaTo)
        );

        $changedLines = [];
        foreach ($diff as $eachDiff) {
            $file = $pathToRepository . substr($eachDiff->getTo(), 1);
            if (! array_key_exists($file, $changedLines)) {
                $changedLines[$file] = [];
            }

            foreach ($eachDiff->getChunks() as $eachChunk) {
                $currentLineNumber = $eachChunk->getStart();
                foreach ($eachChunk->getLines() as $eachLine) {
                    /* @var $eachLine Line */
                    if ($eachLine->getType() === Line::ADDED) {
                        $changedLines[$file][] = $currentLineNumber;
                    }

                    if ($eachLine->getType() !== Line::REMOVED) {
                        ++$currentLineNumber;
                    }
                }
            }
            $changedLines[$file] = array_unique($changedLines[$file]);
        }
        return new Diff($changedLines);
    }
}
