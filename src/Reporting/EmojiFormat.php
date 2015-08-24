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
            ':speech_balloon: '                => $severity->isNotice(),
            ':white_up_pointing_index: '       => $severity->isWarning(),
            ':high_voltage_sign: '             => $severity->isError(),
            ':puke_finger: '                   => $severity->toProgress() === 0,
            ':face_with_no_good_gesture: '     => $severity->toProgress() === 1,
            ':person_with_pouting_face: '      => $severity->toProgress() === 2,
            ':person_frowning: '               => $severity->toProgress() === 3,
            ':face_with_ok_gesture: '          => $severity->toProgress() === 4,
            ':happy_person_raising_one_hand: ' => $severity->toProgress() === 5,
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
