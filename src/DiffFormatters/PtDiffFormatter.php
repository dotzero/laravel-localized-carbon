<?php

namespace Laravelrus\LocalizedCarbon\DiffFormatters;

class PtDiffFormatter implements DiffFormatterInterface
{
    public function format($isNow, $isFuture, $delta, $unit)
    {
        $unitStr = \Lang::choice("localized-carbon::units." . $unit, $delta, [], 'pt');

        $txt = $delta . ' ' . $unitStr;

        if ($isNow) {
            $txt .= ($isFuture) ? ' a partir de agora' : ' atrás';
            return $txt;
        }

        $txt .= ($isFuture) ? ' depois' : ' antes';
        return $txt;
    }
}
