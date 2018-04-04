<?php

namespace Laravelrus\Tests\LocalizedCarbon;

use Laravelrus\LocalizedCarbon\LocalizedCarbon;

class LocalizedCarbonTest extends AbstractTestCase
{
    /**
     * @dataProvider diffDataProvider
     *
     * @param string $before
     * @param string $after
     * @param string $expected
     */
    public function testDiffForHumans($before, $after, $expected)
    {
        $carbon = LocalizedCarbon::createFromTimestamp(strtotime($before), 'Europe/London');
        $other = LocalizedCarbon::createFromTimestamp(strtotime($after), 'Europe/London');

        self::assertSame($expected, $carbon->diffForHumans($other, 'en'));
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
        ];

        yield '1 minute before' => [
            'before' => '2018-04-01 00:00:00',
            'after' => '2018-04-01 00:01:00',
            'expected' => '1 minute before',
        ];

        yield '1 hour before' => [
            'before' => '2018-04-01 00:00:00',
            'after' => '2018-04-01 01:00:00',
            'expected' => '1 hour before',
        ];

        yield '1 day before' => [
            'before' => '2018-04-01 00:00:00',
            'after' => '2018-04-02 00:00:00',
            'expected' => '1 day before',
        ];

        yield '1 week before' => [
            'before' => '2018-04-01 00:00:00',
            'after' => '2018-04-08 00:00:00',
            'expected' => '1 week before',
        ];

        yield '1 month before' => [
            'before' => '2018-04-01 00:00:00',
            'after' => '2018-05-10 00:00:00',
            'expected' => '1 month before',
        ];

        yield '1 year before' => [
            'before' => '2018-04-01 00:00:00',
            'after' => '2019-05-01 00:00:00',
            'expected' => '1 year before',
        ];
    }
}