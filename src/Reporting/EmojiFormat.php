<?php
namespace PointingBreed\Reporting;

final class EmojiFormat implements Format
{
    /**
     * @param Report $report
     * @return string
     */
    public function apply(Report $report)
    {
        $result   = $report->toText();
        $severity = $report->toSeverity();

        $suffixes = [
            ' :+1:' => $severity->isGettingBetter(),
            ' :-1:' => $severity->isGettingWorse(),
        ];
        $prefixes = [
            ':speech_balloon: ' => $severity->isNotice(),
            ':warning: '        => $severity->isWarning(),
            ':zap: '            => $severity->isError(),
            ':rage: '           => $severity->toProgress() === 0,
            ':angry: '          => $severity->toProgress() === 1,
            ':worried: '        => $severity->toProgress() === 2,
            ':confused: '       => $severity->toProgress() === 3,
            ':blush: '          => $severity->toProgress() === 4,
            ':smile: '          => $severity->toProgress() === 5,
        ];

        foreach ($suffixes as $eachEmoji => $apply) {
            if ($apply) {
                $result .= $eachEmoji;
                break;
            }
        }
        foreach ($prefixes as $eachEmoji => $apply) {
            if ($apply) {
                $result = $eachEmoji . $result;
                break;
            }
        }

        return $result;
    }
}
