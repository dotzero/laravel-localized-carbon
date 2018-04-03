<?php

namespace Laravelrus\Tests\LocalizedCarbon;

use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use Laravelrus\LocalizedCarbon\DiffFormatterFactory;

class LocalizedCarbonServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    public function testAmoCrmManagerIsInjectable()
    {
        $this->assertIsInjectable(DiffFormatterFactory::class);
    }
}