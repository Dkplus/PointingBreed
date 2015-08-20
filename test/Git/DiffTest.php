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
        $this->assertTrue($underTest->contains('src/Git/Diff.php', 1));
    }

    public function testItShouldAssumeThatLinesAreUnchangedWhenNoInformationAreAvailable()
    {
        $underTest = new Diff([]);
        $this->assertFalse($underTest->contains('src/Git/Diff.php', 1));
    }
}
