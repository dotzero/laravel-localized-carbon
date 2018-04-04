<?php

namespace Laravelrus\Tests\LocalizedCarbon;

use Laravelrus\LocalizedCarbon\LocalizedCarbon;

class LocalizedCarbonTest extends AbstractTestCase
{
    public function testDetermineLanguage()
    {
        $carbon = LocalizedCarbon::createFromTimestamp(
            strtotime('2018-04-01 00:00:00'),
            'Europe/London'
        );

        $other = LocalizedCarbon::createFromTimestamp(
            strtotime('2018-04-01 00:00:01'),
            'Europe/London'
        );

        $this->app->setLocale('en');
        self::assertSame('en', $carbon->determineLanguage());
        self::assertSame('1 second before', $carbon->diffForHumans($other));
    }

    public function testFormatLocalized()
    {
        $carbon = LocalizedCarbon::createFromTimestamp(
            strtotime('2018-04-01 00:00:00'),
            'Europe/London'
        );

        $this->app->setLocale('en');
        self::assertSame('l, d-M-Y H:i:s T', $carbon->formatLocalized());

        $this->app->setLocale('ru');
        self::assertSame('01 апреля 2018', $carbon->formatLocalized('%d %f %Y'));
    }

    /**
     * @dataProvider diffDataProvider
     *
     * @param string $before
     * @param string $after
     * @param string $expected
     * @param string|\Closure $formatter
     */
    public function testDiffForHumans($before, $after, $expected, $formatter)
    {
        $carbon = LocalizedCarbon::createFromTimestamp(strtotime($before), 'Europe/London');
        $other = LocalizedCarbon::createFromTimestamp(strtotime($after), 'Europe/London');

        self::assertSame($expected, $carbon->diffForHumans($other, $formatter));
    }

    /**
     * @return \Generator
     */
    public function diffDataProvider()
    {
        yield '1 second before' => [
            'before' => '2018-04-01 00:00:00',
            'after' => '2018-04-01 00:00:01',
            'expected' => '1 second before',
            'formatter' => 'en',
        ];

        yield '1 second after' => [
            'before' => '2018-04-01 00:00:01',
            'after' => '2018-04-01 00:00:00',
            'expected' => '1 second after',
            'formatter' => 'en',
        ];

        yield '1 minute before' => [
            'before' => '2018-04-01 00:00:00',
            'after' => '2018-04-01 00:01:00',
            'expected' => '1 minute before',
            'formatter' => 'en',
        ];

        yield '1 minute after' => [
            'before' => '2018-04-01 00:01:00',
            'after' => '2018-04-01 00:00:00',
            'expected' => '1 minute after',
            'formatter' => 'en',
        ];

        yield '1 hour before' => [
            'before' => '2018-04-01 00:00:00',
            'after' => '2018-04-01 01:00:00',
            'expected' => '1 hour before',
            'formatter' => 'en',
        ];

        yield '1 hour after' => [
            'before' => '2018-04-01 01:00:00',
            'after' => '2018-04-01 00:00:00',
            'expected' => '1 hour after',
            'formatter' => 'en',
        ];

        yield '1 day before' => [
            'before' => '2018-04-01 00:00:00',
            'after' => '2018-04-02 00:00:00',
            'expected' => '1 day before',
            'formatter' => 'en',
        ];

        yield '1 day after' => [
            'before' => '2018-04-02 00:00:00',
            'after' => '2018-04-01 00:00:00',
            'expected' => '1 day after',
            'formatter' => 'en',
        ];

        yield '1 week before' => [
            'before' => '2018-04-01 00:00:00',
            'after' => '2018-04-08 00:00:00',
            'expected' => '1 week before',
            'formatter' => 'en',
        ];

        yield '1 week after' => [
            'before' => '2018-04-08 00:00:00',
            'after' => '2018-04-01 00:00:00',
            'expected' => '1 week after',
            'formatter' => 'en',
        ];

        yield '1 month before' => [
            'before' => '2018-04-01 00:00:00',
            'after' => '2018-05-10 00:00:00',
            'expected' => '1 month before',
            'formatter' => 'en',
        ];

        yield '1 month after' => [
            'before' => '2018-05-10 00:00:00',
            'after' => '2018-04-01 00:00:00',
            'expected' => '1 month after',
            'formatter' => 'en',
        ];

        yield '1 year before' => [
            'before' => '2018-04-01 00:00:00',
            'after' => '2019-05-01 00:00:00',
            'expected' => '1 year before',
            'formatter' => 'en',
        ];

        yield '1 year after' => [
            'before' => '2019-05-01 00:00:00',
            'after' => '2018-04-01 00:00:00',
            'expected' => '1 year after',
            'formatter' => 'en',
        ];

        $formatter = function ($isNow, $isFuture, $delta, $unit) {
            return 'Some formatter diff string!';
        };

        yield 'custom formatter' => [
            'before' => '2018-04-01 00:00:00',
            'after' => '2019-05-01 00:00:00',
            'expected' => 'Some formatter diff string!',
            'formatter' => $formatter,
        ];
    }
}
