<?php

namespace Laravelrus\Tests\LocalizedCarbon\Traits;

use Laravelrus\LocalizedCarbon\LocalizedCarbon;
use Laravelrus\LocalizedCarbon\Traits\LocalizedEloquentTrait;
use Laravelrus\Tests\LocalizedCarbon\AbstractTestCase;

class LocalizedEloquentTraitTest extends AbstractTestCase
{
    use LocalizedEloquentTrait;

    /**
     * @dataProvider asDateTimeProvider
     */
    public function testAsDateTime($value, $expected)
    {
        $carbon = $this->asDateTime($value);
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

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }
}
