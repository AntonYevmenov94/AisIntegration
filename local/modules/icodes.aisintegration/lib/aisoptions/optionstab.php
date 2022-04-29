<?php
namespace ICodes\AISIntegration\AISOptions;

use Bitrix\Main\Localization\Loc;


Loc::loadMessages(__FILE__);

/** Define module options tabs
 * Class OptionsTab
 * @package ICodes\InsuranceExchange\Options
 * @copyright intellect.codes
 */

class OptionsTab
{
    public static function getTab($obj)
    {
        $section = $obj->getSection();
        $options = $obj->getOptions();
        $section["OPTIONS"] = $options;
        $section["ENUM_LIST"] = $obj->getEnumLists();
        return $section;
    }

    public static function getEnumProperties()
    {

    }
}