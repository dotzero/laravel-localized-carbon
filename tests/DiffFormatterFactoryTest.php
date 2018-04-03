<?php

namespace Laravelrus\Tests\LocalizedCarbon;

use Laravelrus\LocalizedCarbon\DiffFormatterFactory;
use Laravelrus\LocalizedCarbon\DiffFormatters;

class DiffFormatterFactoryTest extends AbstractTestCase
{
    public function testExtend()
    {
        $factory = $this->getFactory();
        $formatter = new DiffFormatters\EnDiffFormatter();
        $factory->extend('En', $formatter);

        self::assertAttributeSame(['en' => $formatter], 'formatters', $factory);
    }

    public function testAlias()
    {
        $factory = $this->getFactory();
        $factory->alias('zz', 'En');

        self::assertAttributeSame(['zz' => 'en'], 'aliases', $factory);
    }

    /**
     * @dataProvider getDataProvider
     *
     * @param string $language
     * @param string $expected
     */
    public function testGet($language, $expected)
    {
        $factory = $this->getFactory();
        $factory->alias('zz', 'ru');

        try {
            $formatter = $factory->get($language);
        } catch (\Exception $e) {
            $formatter = null;
        }

        self::assertInstanceOf($expected, $formatter);
    }

    /**
     * @return \Generator
     */
    public function getDataProvider()
    {
        yield 'simple' => [
            'language' => 'fr',
            'expected' => DiffFormatters\FrDiffFormatter::class,
        ];

        yield 'alias' => [
            'language' => 'zz',
            'expected' => DiffFormatters\RuDiffFormatter::class,
        ];

        yield 'fallback' => [
            'language' => 'yy',
            'expected' => DiffFormatters\EnDiffFormatter::class,
        ];
    }

    /**
     * @return DiffFormatterFactory
     */
    protected function getFactory()
    {
        return new DiffFormatterFactory($this->app);
    }
}
