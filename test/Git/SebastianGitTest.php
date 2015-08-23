<?php
namespace PointingBreedTest\Git;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Git\CommitReference;
use PointingBreed\Git\SebastianGit;

/**
 * @covers PointingBreed\Git\SebastianGit
 */
class SebastianGitTest extends TestCase
{
    const FIRST_COMMIT_TO_THIS_REPOSITORY = 'a45776c';
    const SECOND_COMMIT_TO_THIS_REPOSITORY = 'b1a503c';

    /**
     * @dataProvider provideChangedLines
     *
     * @param string $file
     * @param string $line
     * @return void
     */
    public function testItShouldDetectChangedLines($file, $line)
    {
        $underTest = new SebastianGit();
        $diff = $underTest->diff(
            getcwd(),
            CommitReference::fromNative(self::FIRST_COMMIT_TO_THIS_REPOSITORY),
            CommitReference::fromNative(self::SECOND_COMMIT_TO_THIS_REPOSITORY)
        );

        $this->assertTrue($diff->containsLine($file, $line));
    }

    public static function provideChangedLines()
    {
        return [
            [getcwd() . '/src/Console/CheckCodeSnifferResultCommand.php', 4],
            [getcwd() . '/src/Git/Changes.php', 5]
        ];
    }

    /**
     * @dataProvider provideUnchangedLines
     *
     * @param string $file
     * @param string $line
     * @return void
     */
    public function testItShouldNotDetectUnchangedLines($file, $line)
    {
        $underTest = new SebastianGit();
        $diff = $underTest->diff(
            getcwd(),
            CommitReference::fromNative(self::FIRST_COMMIT_TO_THIS_REPOSITORY),
            CommitReference::fromNative(self::SECOND_COMMIT_TO_THIS_REPOSITORY)
        );

        $this->assertFalse($diff->containsLine($file, $line));
    }

    public static function provideUnchangedLines()
    {
        return [
            [getcwd() . '/src/Console/CheckCodeSnifferResultCommand.php', 1],
            [getcwd() . '/README.md', 1],
        ];
    }
}
