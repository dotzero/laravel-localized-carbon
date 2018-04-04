<?php

namespace Laravelrus\LocalizedCarbon;

use Carbon\Carbon;
use Laravelrus\LocalizedCarbon\DiffFormatters\DiffFormatterInterface;

class LocalizedCarbon extends Carbon
{
    const DEFAULT_LANGUAGE = 'en';

    /**
     * Get the current application locale.
     *
     * @return string
     */
    public function determineLanguage()
    {
        return \App::getLocale();
    }

    /**
     * Get the difference in a human readable format in the current locale.
     *
     * When comparing a value in the past to default now:
     * 1 hour ago
     * 5 months ago
     *
     * When comparing a value in the future to default now:
     * 1 hour from now
     * 5 months from now
     *
     * When comparing a value in the past to another value:
     * 1 hour before
     * 5 months before
     *
     * When comparing a value in the future to another value:
     * 1 hour after
     * 5 months after
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $other
     * @param string|null $formatter
     * @param bool $short
     * @param int $parts
     *
     * @return string
     */
    public function diffForHumans($other = null, $formatter = null, $short = false, $parts = 1)
    {
        if ($formatter === null) {
            $formatter = DiffFactoryFacade::get($this->determineLanguage());
        } elseif (is_string($formatter)) {
            $formatter = DiffFactoryFacade::get($formatter);
        }

        // Original logic from Carbon
        $isNow = $other === null;

        if ($isNow) {
            $other = static::now($this->tz);
        }

        $isFuture = $this->gt($other);

        $delta = $other->diffInSeconds($this);

        // 4.35 weeks per year. 365 days in a year, 12 months, 7 days in a week:
        // 365/12/7 = 4.345238095238095 4.35 is good enough for big time calculations!
        $divs = [
            'second' => self::SECONDS_PER_MINUTE,
            'minute' => self::MINUTES_PER_HOUR,
            'hour' => self::HOURS_PER_DAY,
            'day' => self::DAYS_PER_WEEK,
            'week' => 4.35,
            'month' => self::MONTHS_PER_YEAR
        ];

        $unit = 'year';

        foreach ($divs as $divUnit => $divValue) {
            if ($delta < $divValue) {
                $unit = $divUnit;
                break;
            }

            $delta = floor($delta / $divValue);
        }

        if ($delta == 0) {
            $delta = 1;
        }

        // Format and return
        $result = null;
        if ($formatter instanceof DiffFormatterInterface) {
            $result = $formatter->format($isNow, $isFuture, $delta, $unit);
        } elseif ($formatter instanceof \Closure) {
            $result = $formatter($isNow, $isFuture, $delta, $unit);
        }

        return $result;
    }

    /**
     * Format the instance with the current locale.  You can set the current
     * locale using setlocale() http://php.net/setlocale.
     *
     * @param string $format
     *
     * @return string
     */
    public function formatLocalized($format = self::COOKIE)
    {
        if (strpos($format, '%f') !== false) {
            $langKey = strtolower(parent::format("F"));
            $replace = \Lang::get("localized-carbon::months." . $langKey);
            $result = str_replace('%f', $replace, $format);
        } else {
            $result = $format;
        }

        $result = parent::formatLocalized($result);

        return $result;
    }
}
