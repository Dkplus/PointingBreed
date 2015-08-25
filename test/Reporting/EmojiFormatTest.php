<?php
namespace PointingBreedTest\Reporting;

use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Reporting\EmojiFormat;
use PointingBreed\Reporting\Report;
use PointingBreed\Reporting\Severity;
use PointingBreed\Reporting\Trend;

/**
 * @covers PointingBreed\Reporting\EmojiFormat
 */
class EmojiFormatTest extends TestCase
{
    public function testItShouldAppendAThumbUpToAnImprovement()
    {
        $severity  = Severity::error(Trend::improvement());
        $underTest = new EmojiFormat();

        $this->assertStringEndsWith(
            ' :+1:',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldAppendAThumbDownToAPejoration()
    {
        $severity  = Severity::error(Trend::pejoration());
        $underTest = new EmojiFormat();

        $this->assertStringEndsWith(
            ' :-1:',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldPrependAZapToAnError()
    {
        $severity  = Severity::error();
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':zap: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldPrependAWarningToAWarning()
    {
        $severity  = Severity::warning();
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':warning: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldPrependASpeechBalloonToANotice()
    {
        $severity  = Severity::notice();
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':speech_balloon: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldPrependARageToAProgressZero()
    {
        $severity  = Severity::progress(0);
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':rage: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldPrependAnAngryToAProgressOne()
    {
        $severity  = Severity::progress(1);
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':angry: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldPrependAWorriedToAProgressTwo()
    {
        $severity  = Severity::progress(2);
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':worried: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldPrependAConfusedToAProgressThree()
    {
        $severity  = Severity::progress(3);
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':confused: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldPrependABlushToAProgressFour()
    {
        $severity  = Severity::progress(4);
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':blush: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldPrependASmileToAProgressFive()
    {
        $severity  = Severity::progress(5);
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':smile: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }
}
