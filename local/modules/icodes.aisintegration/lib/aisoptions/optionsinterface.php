<?php

namespace ICodes\AISIntegration\AISOptions;

/**
 * Interface to provide base list of options related methods.
 */
interface OptionsInterface
{
    public static function getSection();

    public static function getOptions();

    public static function getEnumLists();
}