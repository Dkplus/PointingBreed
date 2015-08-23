<?php
namespace PointingBreedTest\Reporting;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase as TestCase;
use PointingBreed\Reporting\Severity;
use PointingBreed\Reporting\Trend;

/**
 * @covers PointingBreed\Reporting\Severity
 */
class SeverityTest extends TestCase
{
    public function testItShouldGiveASeverityError()
    {
        $severity = Severity::error();
        $this->assertTrue($severity->isError());
        $this->assertFalse($severity->isWarning());
        $this->assertFalse($severity->isNotice());
        $this->assertFalse($severity->isProgress());
    }

    public function testItShouldGiveASeverityWarning()
    {
        $severity = Severity::warning();
        $this->assertTrue($severity->isWarning());
        $this->assertFalse($severity->isError());
        $this->assertFalse($severity->isNotice());
        $this->assertFalse($severity->isProgress());
    }

    public function testItShouldGiveASeverityNotice()
    {
        $severity = Severity::notice();
        $this->assertTrue($severity->isNotice());
        $this->assertFalse($severity->isError());
        $this->assertFalse($severity->isWarning());
        $this->assertFalse($severity->isProgress());
    }

    public function testItShouldGiveASeverityProgress()
    {
        $severity = Severity::progress(2);
        $this->assertTrue($severity->isProgress());
        $this->assertFalse($severity->isError());
        $this->assertFalse($severity->isWarning());
        $this->assertFalse($severity->isNotice());
    }

    public function testItShouldNotHaveAlwaysATrend()
    {
        $notice   = Severity::notice();
        $warning  = Severity::warning();
        $error    = Severity::error();
        $progress = Severity::progress(2);
        $this->assertFalse($notice->isGettingWorse());
        $this->assertFalse($notice->isGettingBetter());
        $this->assertFalse($progress->isGettingBetter());
        $this->assertFalse($progress->isGettingWorse());
        $this->assertFalse($warning->isGettingWorse());
        $this->assertFalse($warning->isGettingBetter());
        $this->assertFalse($error->isGettingWorse());
        $this->assertFalse($error->isGettingBetter());
    }

    public function testItShouldGettingBetter()
    {
        $notice   = Severity::notice(Trend::improvement());
        $warning  = Severity::warning(Trend::improvement());
        $error    = Severity::error(Trend::improvement());
        $progress = Severity::progress(2, Trend::improvement());
        $this->assertFalse($notice->isGettingWorse());
        $this->assertTrue($notice->isGettingBetter());
        $this->assertFalse($progress->isGettingWorse());
        $this->assertTrue($progress->isGettingBetter());
        $this->assertFalse($warning->isGettingWorse());
        $this->assertTrue($warning->isGettingBetter());
        $this->assertFalse($error->isGettingWorse());
        $this->assertTrue($error->isGettingBetter());
    }

    public function testItShouldGettingWorse()
    {
        $notice   = Severity::notice(Trend::pejoration());
        $warning  = Severity::warning(Trend::pejoration());
        $error    = Severity::error(Trend::pejoration());
        $progress = Severity::progress(1, Trend::pejoration());
        $this->assertTrue($notice->isGettingWorse());
        $this->assertFalse($notice->isGettingBetter());
        $this->assertTrue($progress->isGettingWorse());
        $this->assertFalse($progress->isGettingBetter());
        $this->assertTrue($warning->isGettingWorse());
        $this->assertFalse($warning->isGettingBetter());
        $this->assertTrue($error->isGettingWorse());
        $this->assertFalse($error->isGettingBetter());
    }

    public function testItShouldNotAllowProgressUnderZero()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        Severity::progress(-1);
    }

    public function testItShouldNotAllowProgressAboveFive()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        Severity::progress(6);
    }

    public function testItShouldProvideAProgressIfItsAProgressSeverity()
    {
        $progress = Severity::progress(1);
        $this->assertSame(1, $progress->toProgress());

        $notice = Severity::notice();
        $this->assertNull($notice->toProgress());
    }
}
