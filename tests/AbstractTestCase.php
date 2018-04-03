<?php

namespace Laravelrus\Tests\LocalizedCarbon;

use GrahamCampbell\TestBench\AbstractPackageTestCase;
use Laravelrus\LocalizedCarbon\LocalizedCarbonServiceProvider;

abstract class AbstractTestCase extends AbstractPackageTestCase
{
    /**
     * Get the service provider class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string
     */
    protected function getServiceProviderClass($app)
    {
        return LocalizedCarbonServiceProvider::class;
    }
}