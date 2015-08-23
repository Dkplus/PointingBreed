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

        if ($severity->isGettingBetter()) {
            $result .= ' :+1:';
        }
        if ($severity->isGettingWorse()) {
            $result .= ' :-1:';
        }
        if ($severity->isNotice()) {
            $result = ':speech_balloon: ' . $result;
        }
        if ($severity->isWarning()) {
            $result = ':white_up_pointing_index: ' . $result;
        }
        if ($severity->isError()) {
            $result = ':high_voltage_sign: ' . $result;
        }
        if ($severity->toProgress() === 0) {
            $result = ':puke_finger: ' . $result;
        }
        if ($severity->toProgress() === 1) {
            $result = ':face_with_no_good_gesture: ' . $result;
        }
        if ($severity->toProgress() === 2) {
            $result = ':person_with_pouting_face: ' . $result;
        }
        if ($severity->toProgress() === 3) {
            $result = ':person_frowning: ' . $result;
        }
        if ($severity->toProgress() === 4) {
            $result = ':face_with_ok_gesture: ' . $result;
        }
        if ($severity->toProgress() === 5) {
            $result = ':happy_person_raising_one_hand: ' . $result;
        }

        return $result;
    }
}
