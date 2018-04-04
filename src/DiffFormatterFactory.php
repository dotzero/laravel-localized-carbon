<?php

namespace Laravelrus\LocalizedCarbon;

use Illuminate\Contracts\Container\Container;
use Laravelrus\LocalizedCarbon\DiffFormatters\DiffFormatterInterface;

class DiffFormatterFactory
{
    /**
     * @var array List of formatters
     */
    protected $formatters = [];

    /**
     * @var array List of language aliases
     */
    protected $aliases = [];

    /**
     * @var Container
     */
    protected $app;

    /**
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Extends list of formatters
     *
     * @param string $language
     * @param \Closure|DiffFormatterInterface|string $formatter
     */
    public function extend($language, $formatter)
    {
        $language = strtolower($language);
        $this->formatters[$language] = $formatter;
    }

    /**
     * Extends list of language aliases
     *
     * @param string $alias
     * @param string $language
     */
    public function alias($alias, $language)
    {
        $language = strtolower($language);
        $this->aliases[$alias] = $language;
    }

    /**
     * @param string $language
     * @return mixed
     * @throws \Exception
     */
    public function get($language)
    {
        $language = strtolower($language);

        if (isset($this->aliases[$language])) {
            $language = $this->aliases[$language];
        }

        if (isset($this->formatters[$language])) {
            $formatter = $this->formatters[$language];

            if (is_string($formatter)) {
                $formatter = $this->app->make($formatter);
            }
        } else {
            $formatterClass = $this->getFormatterClassName($language);
            try {
                $formatter = $this->app->make($formatterClass);
            } catch (\Exception $e) {
                // In case desired formatter could not be loaded
                // load a formatter for application's fallback locale
                $language = $this->getFallbackLanguage();
                $formatterClass = $this->getFormatterClassName($language);
                $formatter = $this->app->make($formatterClass);
            }
        }

        if (!$formatter instanceof \Closure && !$formatter instanceof DiffFormatterInterface) {
            throw new \Exception('Formatter for language ' . $language . ' should implement DiffFormatterInterface or must be a Closure.');
        }

        // Remember instance for further use
        $this->extend($language, $formatter);

        return $formatter;
    }

    /**
     * @param string $language
     * @return string
     */
    protected function getFormatterClassName($language)
    {
        $name = ucfirst(strtolower($language));
        $name = 'Laravelrus\\LocalizedCarbon\\DiffFormatters\\' . $name . 'DiffFormatter';

        return $name;
    }

    /**
     * @return mixed
     */
    protected function getFallbackLanguage()
    {
        return data_get($this->app, 'config.app.fallback_locale');
    }
}
