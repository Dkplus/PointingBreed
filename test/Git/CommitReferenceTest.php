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
    /**
     * @dataProvider provideShortRefs
     *
     * @param string $ref
     * @return void
     */
    public function testItShouldAllowShortRefs($ref)
    {
        $this->assertInstanceOf(CommitReference::class, CommitReference::fromNative($ref));
    }

    public static function provideShortRefs()
    {
        return [
            ['2a41'],
            ['2a410efb'],
            ['2a410efbd135'],
            ['2a410efbd1359'],
            ['2a410efbd13591db0749'],
            ['2a410efbd13591db074966'],
            ['2a410efbd13591db07496601ebc'],
            ['2a410efbd13591db07496601ebc7a059dd55cfe'],
        ];
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
            ['1a0'],
            ['1g410'],
            ['2a410efbd13591db07496601ebc7a059dd55cfe9g'],
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
