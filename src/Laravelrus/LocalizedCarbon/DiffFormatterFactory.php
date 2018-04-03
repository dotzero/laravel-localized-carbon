<?php

namespace Laravelrus\LocalizedCarbon;

use Laravelrus\LocalizedCarbon\DiffFormatters\DiffFormatterInterface;

class DiffFormatterFactory
{
    protected $formatters = [];
    protected $aliases = [];

    /**
     * @param $language
     * @param $formatter
     */
    public function extend($language, $formatter)
    {
        $language = strtolower($language);
        $this->formatters[$language] = $formatter;
    }

    /**
     * @param $alias
     * @param $language
     */
    public function alias($alias, $language)
    {
        $language = strtolower($language);
        $this->aliases[$alias] = $language;
    }

    /**
     * @param $language
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
                $formatter = \App::make($formatter);
            }
        } else {
            $formatterClass = $this->getFormatterClassName($language);
            try {
                $formatter = \App::make($formatterClass);
            } catch (\Exception $e) {
                // In case desired formatter could not be loaded
                // load a formatter for application's fallback locale
                $language = $this->getFallbackLanguage();
                $formatterClass = $this->getFormatterClassName($language);
                $formatter = \App::make($formatterClass);
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
     * @param $language
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
        return \Config::get('app.fallback_locale');
    }
} 
