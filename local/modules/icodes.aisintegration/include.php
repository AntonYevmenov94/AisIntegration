<?php
use Bitrix\Main\Loader;
Loader::registerAutoLoadClasses(
    'icodes.aisintegration',
    array(
        'ICodes\AISIntegration\Helpers\Config'     => 'lib/helpers/config.php',
        'ICodes\AISIntegration\Helpers\OptionFields'     => 'lib/helpers/optionfields.php',
        'ICodes\AISIntegration\AISOptions\OptionsTab'       => 'lib/aisoptions/optionstab.php',
        'ICodes\AISIntegration\AISOptions\OptionsInterface' => 'lib/aisoptions/optionsinterface.php',
        'ICodes\AISIntegration\AISOptions\General'          => 'lib/aisoptions/general.php',
        'ICodes\AISIntegration\Helpers\FrontInterface' => 'lib/helpers/frontinterface.php',
        'ICodes\AISIntegration\Helpers\ExtendedOptions' => 'lib/helpers/extendedoptions.php',
    )
);
