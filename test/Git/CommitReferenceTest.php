<?php
namespace PointingBreedTest\Git;

use InvalidArgumentException;
use PointingBreed\Git\CommitReference;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers PointingBreed\Git\CommitReference
 */
class CommitReferenceTest extends TestCase
{
    public function testItShouldAllowShortRefs()
    {
        $this->assertInstanceOf(CommitReference::class, CommitReference::fromNative('1a410e'));
    }

    public function testItShouldAllowLongRefs()
    {
        $this->assertInstanceOf(
            CommitReference::class,
            CommitReference::fromNative('2a410efbd13591db07496601ebc7a059dd55cfe9')
        );
    }

    /**
     * @dataProvider provideInvalidFormats
     *
     * @param string $invalidFormat
     * @return void
     */
    public function testItShouldNotAllowOtherFormats($invalidFormat)
    {
        $this->setExpectedException(InvalidArgumentException::class);
        CommitReference::fromNative($invalidFormat);
    }

    public static function provideInvalidFormats()
    {
        return [
            ['1a410'],
            ['1a410efbd13591db07496601ebc7a059dd55cfe'],
            ['1a410efbd13591db07496601ebc7a059dd55cf9g']
        ];
    }

    public function testItShouldProvideAStringRepresentation()
    {
        $sha = '1a410e';
        $this->assertSame($sha, CommitReference::fromNative($sha)->toNative());
    }

    public function testItShouldConvertedIntoString()
    {
        $sha = '1a410e';
        $this->assertSame($sha, (string) CommitReference::fromNative($sha));
    }
}
