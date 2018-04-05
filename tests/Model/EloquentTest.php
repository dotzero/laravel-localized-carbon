<?php

namespace Laravelrus\Tests\LocalizedCarbon\Traits;

use Laravelrus\LocalizedCarbon\LocalizedCarbon;
use Laravelrus\LocalizedCarbon\Models\Eloquent;
use Laravelrus\Tests\LocalizedCarbon\AbstractTestCase;

class EloquentTest extends AbstractTestCase
{
    /**
     * @dataProvider asDateTimeProvider
     */
    public function testAsDateTime($value, $expected)
    {
        $mock = $this->getMockBuilder(Eloquent::class)
            ->setMethods(['getDateFormat'])
            ->getMock();

        $mock->method('getDateFormat')
            ->willReturn('Y-m-d H:i:s');

        $carbon = $this->invokeMethod($mock, 'asDateTime', [$value]);
        self::assertInstanceOf(LocalizedCarbon::class, $carbon);
        self::assertEquals($expected, $carbon);
    }

    /**
     * @return \Generator
     */
    public function asDateTimeProvider()
    {
        $now = time();

        yield 'instanceof LocalizedCarbon' => [
            'value' => LocalizedCarbon::createFromTimestamp($now),
            'expected' => LocalizedCarbon::createFromTimestamp($now),
        ];

        yield 'instanceof Carbon' => [
            'value' => \Carbon\Carbon::createFromTimestamp($now),
            'expected' => LocalizedCarbon::createFromTimestamp($now),
        ];

        yield 'instanceof DateTime' => [
            'value' => (new \DateTime())->setTimestamp($now),
            'expected' => LocalizedCarbon::createFromTimestamp($now),
        ];

        yield 'instanceof integer' => [
            'value' => $now,
            'expected' => LocalizedCarbon::createFromTimestamp($now),
        ];

        yield 'format Y-m-d' => [
            'value' => '2018-01-01',
            'expected' => LocalizedCarbon::createFromTimestamp(strtotime('2018-01-01 00:00:00')),
        ];

        yield 'getDateFormat' => [
            'value' => '2018-01-01 00:00:00',
            'expected' => LocalizedCarbon::createFromTimestamp(strtotime('2018-01-01 00:00:00')),
        ];
    }
}
