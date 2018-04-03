<?php

namespace Laravelrus\LocalizedCarbon\DiffFormatters;

class ArDiffFormatter implements DiffFormatterInterface
{
    public function format($isNow, $isFuture, $delta, $unit)
    {
        $unitStr = \Lang::choice("localized-carbon::units." . $unit, $delta, [], 'ar');

        $txt = $delta . ' ' . $unitStr;

        if ($isNow) {
            $when = ($isFuture) ? ' من الآن' : ' مرّت';
            return $txt . $when;
        }

        $txt .= ($isFuture) ? 'بعد ' : 'قبل ';

        return $txt;
    }
}
