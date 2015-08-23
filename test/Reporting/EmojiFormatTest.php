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

    public function testItShouldPrependAHighVoltageToAnError()
    {
        $severity  = Severity::error();
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':high_voltage_sign: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldPrependAWhiteUpPointingIndexToAWarning()
    {
        $severity  = Severity::warning();
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':white_up_pointing_index: ',
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

    public function testItShouldPrependAPukeFingerToAProgressZero()
    {
        $severity  = Severity::progress(0);
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':puke_finger: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldPrependAFaceWithNoGoodGestureToAProgressOne()
    {
        $severity  = Severity::progress(1);
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':face_with_no_good_gesture: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldPrependAPersonWithPoutingFaceToAProgressTwo()
    {
        $severity  = Severity::progress(2);
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':person_with_pouting_face: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldPrependAPersonFrowningToAProgressThree()
    {
        $severity  = Severity::progress(3);
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':person_frowning: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldPrependAFaceWithOkGestureToAProgressFour()
    {
        $severity  = Severity::progress(4);
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':face_with_ok_gesture: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }

    public function testItShouldPrependAHappyPersonRaisingOneHandToAProgressFive()
    {
        $severity  = Severity::progress(5);
        $underTest = new EmojiFormat();

        $this->assertStringStartsWith(
            ':happy_person_raising_one_hand: ',
            $underTest->apply(Report::forCommit('abc', $severity))
        );
    }
}
