<?php

namespace Laravelrus\Tests\LocalizedCarbon;

use GrahamCampbell\TestBenchCore\FacadeTrait;
use Laravelrus\LocalizedCarbon\DiffFactoryFacade;
use Laravelrus\LocalizedCarbon\DiffFormatterFactory;

class DiffFactoryFacadeTest extends AbstractTestCase
{
    use FacadeTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'difffactory';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    protected function getFacadeClass()
    {
        return DiffFactoryFacade::class;
    }

    /**
     * Get the facade root.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return DiffFormatterFactory::class;
    }
}