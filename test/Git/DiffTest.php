<?php
namespace PointingBreedTest\Git;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Git\Diff;

/**
 * @covers PointingBreed\Git\Diff
 */
class DiffTest extends TestCase
{
    public function testItShouldKnowWhenALineHasChanged()
    {
        $underTest = new Diff(['src/Git/Diff.php' => [1, 2, 3]]);
        $this->assertTrue($underTest->containsLine('src/Git/Diff.php', 1));
    }

    public function testItShouldKnowWhenAnyLineInAFileHasChanged()
    {
        $underTest = new Diff(['src/Git/Diff.php' => [1, 2, 3]]);
        $this->assertTrue($underTest->containsFile('src/Git/Diff.php'));
    }

    public function testItShouldAssumeThatLinesAreUnchangedWhenNoInformationAreAvailable()
    {
        $underTest = new Diff([]);
        $this->assertFalse($underTest->containsLine('src/Git/Diff.php', 1));
    }
}
