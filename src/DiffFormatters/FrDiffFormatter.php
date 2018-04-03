<?php

namespace Laravelrus\LocalizedCarbon\DiffFormatters;

class FrDiffFormatter implements DiffFormatterInterface
{
    public function format($isNow, $isFuture, $delta, $unit)
    {
        $unitStr = \Lang::choice("localized-carbon::units." . $unit, $delta, [], 'fr');

        $txt = $delta . ' ' . $unitStr;

        if ($isNow) {
            $when = ($isFuture) ? 'Dans ' : 'Il y a ';
            return $when . $txt;
        }

        $txt .= ($isFuture) ? ' après' : ' avant';

        return $txt;
    }
}
