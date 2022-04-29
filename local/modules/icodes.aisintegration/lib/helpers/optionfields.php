<?php
namespace ICodes\AISIntegration\Helpers;

use Bitrix\Main\Loader;

/** Get crm entities fields
 * Class OptionFields
 * @package ICodes\AISIntegration\Helpers
 * @copyright intellect.codes
 */

class OptionFields
{
    /** Get Deal fields
     * @return array
     */
    public static function getDealFields()
    {
        Loader::includeModule('crm');
        global $USER_FIELD_MANAGER;

        $arDealFields['all'] = array('');

        $CCrmFields = new \CCrmFields($USER_FIELD_MANAGER, 'CRM_DEAL');
        $rsFields = $CCrmFields->GetFields();

        foreach ($rsFields as $field)
        {
            if (!$arDealFields[$field['USER_TYPE_ID']]) $arDealFields[$field['USER_TYPE_ID']] = array("");

            $arDealFields[$field['USER_TYPE_ID']][$field['FIELD_NAME']] = $field['EDIT_FORM_LABEL'] . ' (' . $field['FIELD_NAME'] . ')';
            $arDealFields['all'][$field['FIELD_NAME']] = $field['EDIT_FORM_LABEL'] . ' (' . $field['FIELD_NAME'] . ')';
        }

        return $arDealFields;
    }

    /** Get enumerations of list fields
     * @param $fieldName
     * @return mixed
     */
    function getFieldEnums($fieldName)
    {
        global $USER_FIELD_MANAGER;
        $list = array('');

        if($fieldName) {
            $CCrmFields = new \CCrmFields($USER_FIELD_MANAGER, 'CRM_DEAL');
            $arField = $CCrmFields->GetByName($fieldName);
            if($arField['USER_TYPE_ID'] == 'enumeration') {
                $rsFieldEnum = \CUserFieldEnum::GetList(array(), array("USER_FIELD_ID"=>$arField['ID']));
                while ($enum = $rsFieldEnum->Fetch()) {
                    $list[$enum['ID']] = $enum['VALUE'];
                }
            }
        }
        return $list;
    }

}