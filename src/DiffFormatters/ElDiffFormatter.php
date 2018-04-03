<?php

namespace Laravelrus\LocalizedCarbon\DiffFormatters;

class ElDiffFormatter implements DiffFormatterInterface
{
    public function format($isNow, $isFuture, $delta, $unit)
    {
        $unitStr = \Lang::choice("localized-carbon::units." . $unit, $delta, [], 'el');
        $txt = $delta . ' ' . $unitStr;
        if ($isNow) {
            $when = ($isFuture) ? 'από τώρα' : 'πρίν';
            return $when . $txt;
        }

        $txt .= ($isFuture) ? 'μετά' : ' πρίν';

        return $txt;
    }
}
