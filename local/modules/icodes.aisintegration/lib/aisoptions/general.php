<?php
namespace ICodes\AISIntegration\AISOptions;

use Bitrix\Main\Localization\Loc;


Loc::loadMessages(__FILE__);

/** Define general module options fields
 * Class General
 * @package ICodes\InsuranceExchange\Options
 * @copyright intellect.codes
 */

class General implements OptionsInterface
{

    private static $enumLists = array();

    /** Get enum properties
     * @return array
     */
    static public function getEnumLists()
    {
        return array_keys(self::$enumLists);
    }

    /** Get option tab
     * @return array
     */
    static public function getSection()
    {
        $tab = array(
            "DIV" => "general",
            "TAB" => Loc::getMessage("ICODES_AISINTEGR_OPTION_TAB_TITLE"),
            "TITLE" => Loc::getMessage("ICODES_AISINTEGR_OPTION_TAB_HEAD"),
        );
        return $tab;
    }

    /** Get option fields
     * @return array
     */
    static public function getOptions()
    {
        $options = array(
            Loc::getMessage("ICODES_AISINTEGR_AUTH_SECTION"),
            array(
                "url",
                Loc::getMessage("ICODES_AISINTEGR_OPTION_URL"),
                false,
                ["text"]
            ),
            array(
                "login",
                Loc::getMessage("ICODES_AISINTEGR_OPTION_LOGIN"),
                false,
                ["text"]
            ),
            array(
                "password",
                Loc::getMessage("ICODES_AISINTEGR_OPTION_PASSWORD"),
                false,
                ["password"]
            ),
        );

        return $options;
    }

}