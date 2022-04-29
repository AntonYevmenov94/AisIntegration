<?php
namespace ICodes\AISIntegration\AISOptions;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Config\Option,
    ICodes\AISIntegration\Helpers\Config,
    ICodes\AISIntegration\Helpers\OptionFields;


Loc::loadMessages(__FILE__);

/** Define general module options fields
 * Class General
 * @package ICodes\InsuranceExchange\Options
 * @copyright intellect.codes
 */

class MainMapping implements OptionsInterface
{

    private static $enumLists = array();
    private static $enumReference = array();


    /** Get enum properties
     * @return array
     */
    static public function getEnumLists()
    {
        return self::$enumReference;
//        return array(
//            'insured_person_type' => [
//                'insured_person_type_individual',
//                'insured_person_type_individual_entrepreneur',
//                'insured_person_type_enterprise'
//            ],
//            'assistance' => [
//                'assistance_balt',
//                'assistance_kaliptus',
//                'assistance_test'
//            ],
//            'insurance_currency' => [
//                'insurance_currency_eur',
//                'insurance_currency_usd',
//            ],
//            'contribution_payment_currency' => [
//                'contribution_payment_currency_eur',
//                'contribution_payment_currency_usd',
//                'contribution_payment_currency_rub',
//                'contribution_payment_currency_byr'
//            ],
//            'territory_of_action_by_country' => [
//                'territory_of_action_by_country_schengen',
//                'territory_of_action_by_country_all',
//                'territory_of_action_by_country_except_set_1'
//            ],
//            'contribution_payment_mode' => [
//                'contribution_payment_mode_annually',
//                'contribution_payment_mode_at_a_time'
//            ],
//            'regular_customer' => [
//                'regular_customer_more_than_twice',
//                'regular_customer_re-appeal',
//                'regular_customer_no'
//            ],
//            'existing_contracts_for_other_types' => [
//                'existing_contracts_for_other_types_by_two_types',
//                'existing_contracts_for_other_types_three_or_more_types',
//                'existing_contracts_for_other_types_no',
//            ],
//            'franchise' => [
//                'franchise_unconditional',
//                'franchise_no_franchise',
//                ],
//            'insurance_object_type' => [
//                'insurance_object_type_individual'
//            ],
//            'sum_insured' => [
//                'sum_insured_30001-30_000',
//                'sum_insured_60000-60_000',
//                'sum_insured_100000-100_000',
//            ],
//            'active_rest' => [
//                'active_rest_yes',
//                'active_rest_no_increased_risk',
//                'active_rest_high_risk',
//            ],
//            'employment' => [
//                'employment_office_workers',
//                'employment_knowledge_workers',
//                'employment_workers_at_increased_risk_to_life_and_health',
//                'employment_no',
//            ],
//        );
    }

    /** Get option tab
     * @return array
     */
    static public function getSection()
    {
        $tab = array(
            "DIV" => "mainmapping",
            "TAB" => Loc::getMessage("ICODES_AISINTEGR_OPTION_TAB_TITLE"),
            "TITLE" => Loc::getMessage("ICODES_AISINTEGR_OPTION_TAB_HEAD"),
        );
        return $tab;
    }

    /** Get property enum list
     * @param $parent
     * @param $child
     * @return mixed
     */
    private static function getFieldEnums($parent, $child)
    {
        if(!isset(self::$enumLists[$parent])) {
            $fieldName = Option::get(Config::MODULE_ID, $parent);
            self::$enumLists[$parent] = OptionFields::getFieldEnums($fieldName);
        }

        self::setPropReference($parent,$child);

        return self::$enumLists[$parent];
    }


    /** Set property references
     * @param $parent
     * @param $chaild
     */
    private static function setPropReference($parent, $chaild)
    {
        if(!isset(self::$enumReference[$parent])) {
            self::$enumReference[$parent] = array();
        }
        self::$enumReference[$parent][] = $chaild;
    }

    /** Get Deal fields
     * @return array
     */
    private static function getDealFields()
    {
        return OptionFields::getDealFields();
    }

    /** Get option fields
     * @return array
     */
    static public function getOptions()
    {
        $arDealFields = self::getDealFields();

        $options = array(
            Loc::getMessage("ICODES_AISINTEGR_GENERAL_AUTH_SECTION"),
            array(
                'note' => Loc::getMessage("ICODES_AISINTEGR_OPTION_BEGIN_END_CONTRACT_NOTIFICATION")
            ),

            /**
             * Dates
             */
            array(
                'conclusion_date_contract',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_CONCLUSION_DATE_CONTRACT"),
                false,
                ["selectbox", $arDealFields['date']]
            ),
            array(
                'entering_length_of_stay',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_ENTERING_LENGTH_OF_STAY"),
                false,
                ["text", $arDealFields['string']],
            ),


            /**
             * Person Types
             */
            array(
                'insured_person_type',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_INSURED_PERSON_TYPE"),
                false,
                ["selectbox", $arDealFields['enumeration']]
            ),
                    array(
                        'insured_person_type_individual',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_INSURED_PERSON_TYPE_INDIVIDUAL"),
                        false,
                        [
                            "selectbox",
                            self::getFieldEnums('insured_person_type', 'insured_person_type_individual')]
                    ),
                    array(
                        'insured_person_type_individual_entrepreneur',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_INSURED_PERSON_TYPE_INDIVIDUAL_ENTREPRENEUR"),
                        false,
                        ["selectbox", self::getFieldEnums('insured_person_type', 'insured_person_type_individual_entrepreneur')]
                    ),
                    array(
                        'insured_person_type_enterprise',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_INSURED_PERSON_TYPE_ENTERPRICE"),
                        false,
                        ["selectbox", self::getFieldEnums('insured_person_type', 'insured_person_type_enterprise')]
                    ),


            /**
             * Assistance
             */
            array(
                'assistance',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_ASSISTANCE"),
                false,
                ["selectbox", $arDealFields['enumeration']]
            ),
                    array(
                        'assistance_balt',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_ASSISTANCE_BALT"),
                        false,
                        ["selectbox", self::getFieldEnums('assistance', 'assistance_balt')]
                    ),
                    array(
                        'assistance_kaliptus',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_ASSISTANCE_KALIPTUS"),
                        false,
                        ["selectbox", self::getFieldEnums('assistance', 'assistance_kaliptus')]
                    ),
                    array(
                        'assistance_test',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_ASSISTANCE_TEST"),
                        false,
                        ["selectbox", self::getFieldEnums('assistance', 'assistance_test')]
                    ),

            /**
             * Currency
             */
            array(
                'insurance_currency',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_INSURANCE_CURRENCY"),
                false,
                ["selectbox", $arDealFields['enumeration']],
            ),
                    array(
                        'insurance_currency_eur',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_CURRENCY_EUR"),
                        false,
                        ["selectbox", self::getFieldEnums('insurance_currency', 'insurance_currency_eur')]
                    ),
                    array(
                        'insurance_currency_usd',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_CURRENCY_USD"),
                        false,
                        ["selectbox", self::getFieldEnums('insurance_currency', 'insurance_currency_usd')]
                    ),
            array(
                'contribution_payment_currency',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_PAYMENT_CURRENCY"),
                false,
                ["selectbox", $arDealFields['enumeration']],
            ),
                    array(
                        'contribution_payment_currency_eur',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_CURRENCY_EUR"),
                        false,
                        ["selectbox", self::getFieldEnums('contribution_payment_currency', 'contribution_payment_currency_eur')]
                    ),
                    array(
                        'contribution_payment_currency_usd',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_CURRENCY_USD"),
                        false,
                        ["selectbox", self::getFieldEnums('contribution_payment_currency', 'contribution_payment_currency_usd')]
                    ),
                    array(
                        'contribution_payment_currency_rub',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_CURRENCY_RUB"),
                        false,
                        ["selectbox", self::getFieldEnums('contribution_payment_currency', 'contribution_payment_currency_rub')]
                    ),
                    array(
                        'contribution_payment_currency_byr',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_CURRENCY_BYR"),
                        false,
                        ["selectbox", self::getFieldEnums('contribution_payment_currency', 'contribution_payment_currency_byr')]
                    ),

            /**
             * Territory of action
             */
            array(
                'territory_of_action_by_country',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_TERRITORY_OF_ACTION_BY_COUNTRY"),
                false,
                ["selectbox", $arDealFields['enumeration']],
            ),
                    array(
                        'territory_of_action_by_country_schengen',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_TERRITORY_OF_ACTION_BY_COUNTRY_SCHENGEN"),
                        false,
                        ["selectbox", self::getFieldEnums('territory_of_action_by_country', 'territory_of_action_by_country_schengen')]
                    ),
                    array(
                        'territory_of_action_by_country_all',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_TERRITORY_OF_ACTION_BY_COUNTRY_ALL"),
                        false,
                        ["selectbox", self::getFieldEnums('territory_of_action_by_country', 'territory_of_action_by_country_all')]
                    ),
                    array(
                        'territory_of_action_by_country_except_set_1',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_TERRITORY_OF_ACTION_BY_COUNTRY_SET_1"),
                        false,
                        ["selectbox", self::getFieldEnums('territory_of_action_by_country', 'territory_of_action_by_country_except_set_1')]
                    ),


            /**
             *  Payment mode
             */
            array(
                'contribution_payment_mode',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_CONTRIBUTION_PAYMENT_MODE"),
                false,
                ["selectbox", $arDealFields['enumeration']],
            ),
                    array(
                        'contribution_payment_mode_annually',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_CONTRIBUTION_PAYMENT_MODE_ANNUALLY"),
                        false,
                        ["selectbox", self::getFieldEnums('contribution_payment_mode', 'contribution_payment_mode_annually')]
                    ),
                    array(
                        'contribution_payment_mode_at_a_time',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_CONTRIBUTION_PAYMENT_MODE_AT_A_TIME"),
                        false,
                        ["selectbox", self::getFieldEnums('contribution_payment_mode', 'contribution_payment_mode_at_a_time')]
                    ),


            /**
             *  Regular_customer
             */
            array(
                'regular_customer',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_REGULAR_CUSTOMER"),
                false,
                ["selectbox", $arDealFields['enumeration']],
            ),
                    array(
                        'regular_customer_more_than_twice',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_REGULAR_CUSTOMER_MORE_THAN_TWICE"),
                        false,
                        ["selectbox", self::getFieldEnums('regular_customer', 'regular_customer_more_than_twice')]
                    ),
                    array(
                        'regular_customer_re-appeal',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_REGULAR_CUSTOMER_RE-APPEAL"),
                        false,
                        ["selectbox", self::getFieldEnums('regular_customer', 'regular_customer_re-appeal')]
                    ),
                    array(
                        'regular_customer_no',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_REGULAR_CUSTOMER_NO"),
                        false,
                        ["selectbox", self::getFieldEnums('regular_customer', 'regular_customer_no')]
                    ),
            /**
             *  Existing_contracts_for_other_types
             */
            array(
                'existing_contracts_for_other_types',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_EXISTING_CONTRACTS_FOR_OTHER_TYPES"),
                false,
                ["selectbox", $arDealFields['enumeration']],
            ),
                    array(
                        'existing_contracts_for_other_types_by_one_type',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_EXISTING_CONTRACTS_FOR_OTHER_TYPES_BY_ONE_TYPE"),
                        false,
                        ["selectbox", self::getFieldEnums('existing_contracts_for_other_types', 'existing_contracts_for_other_types_by_one_type')]
                    ),
                    array(
                        'existing_contracts_for_other_types_by_two_types',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_EXISTING_CONTRACTS_FOR_OTHER_TYPES_BY_TWO_TYPES"),
                        false,
                        ["selectbox", self::getFieldEnums('existing_contracts_for_other_types', 'existing_contracts_for_other_types_by_two_types')]
                    ),
                    array(
                        'existing_contracts_for_other_types_three_or_more_types',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_EXISTING_CONTRACTS_FOR_OTHER_TYPES_THREE_OR_MORE_TYPES"),
                        false,
                        ["selectbox", self::getFieldEnums('existing_contracts_for_other_types', 'existing_contracts_for_other_types_three_or_more_types')]
                    ),
                    array(
                        'existing_contracts_for_other_types_no',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_EXISTING_CONTRACTS_FOR_OTHER_TYPES_NO"),
                        false,
                        ["selectbox", self::getFieldEnums('existing_contracts_for_other_types', 'existing_contracts_for_other_types_no')]
                    ),

            /**
             *  Franchise
             */
            array(
                'franchise',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_FRANCHISE"),
                false,
                ["selectbox", $arDealFields['enumeration']],
            ),
                    array(
                        'franchise_unconditional',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_FRANCHISE_UNCONDITIONAL"),
                        false,
                        ["selectbox", self::getFieldEnums('franchise', 'franchise_unconditional')]
                    ),
                    array(
                        'franchise_no_franchise',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_FRANCHISE_NO_FRANCHISE"),
                        false,
                        ["selectbox", self::getFieldEnums('franchise', 'franchise_no_franchise')]
                    ),
            /**
             *  Insurance_object_type
             */
            array(
                'insurance_object_type',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_INSURANCE_OBJECT_TYPE"),
                false,
                ["selectbox", $arDealFields['enumeration']],
            ),
                    array(
                        'insurance_object_type_individual',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_INSURANCE_OBJECT_TYPE_INDIVIDUAL"),
                        false,
                        ["selectbox", self::getFieldEnums('insurance_object_type', 'insurance_object_type_individual')]
                    ),
            /**
             *  Sum_insured
             */
            array(
                'sum_insured',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_SUM_INSURED"),
                false,
                ["selectbox", $arDealFields['enumeration']],
            ),
                    array(
                        'sum_insured_30001-30_000',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_SUM_INSURED_30001-30_000"),
                        false,
                        ["selectbox", self::getFieldEnums('sum_insured', 'sum_insured_30001-30_000')]
                    ),
                    array(
                        'sum_insured_60000-60_000',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_SUM_INSURED_60000-60_000"),
                        false,
                        ["selectbox", self::getFieldEnums('sum_insured', 'sum_insured_60000-60_000')]
                    ),
                    array(
                        'sum_insured_100000-100_000',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_SUM_INSURED_100000-100_000"),
                        false,
                        ["selectbox", self::getFieldEnums('sum_insured', 'sum_insured_100000-100_000')]
                    ),
            /**
             *  Active_rest
             */
            array(
                'active_rest',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_ACTIVE_REST"),
                false,
                ["selectbox", $arDealFields['enumeration']],
            ),
                    array(
                        'active_rest_yes',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_ACTIVE_REST_YES"),
                        false,
                        ["selectbox", self::getFieldEnums('active_rest', 'active_rest_yes')]
                    ),
                    array(
                        'active_rest_no_increased_risk',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_ACTIVE_REST_NO_INCREASED_RISK"),
                        false,
                        ["selectbox", self::getFieldEnums('active_rest', 'active_rest_no_increased_risk')]
                    ),
                    array(
                        'active_rest_high_risk',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_ACTIVE_REST_HIGH_RISK"),
                        false,
                        ["selectbox", self::getFieldEnums('active_rest', 'active_rest_high_risk')]
                    ),
            /**
             *
             */
            array(
                'employment',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_EMPLOYMENT"),
                false,
                ["selectbox", $arDealFields['enumeration']],
            ),
                    array(
                        'employment_office_workers',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_EMPLOYMENT_OFFICE_WORKERS"),
                        false,
                        ["selectbox", self::getFieldEnums('employment', 'employment_office_workers')]
                    ),
                    array(
                        'employment_knowledge_workers',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_EMPLOYMENT_KNOWLEDGE_WORKERS"),
                        false,
                        ["selectbox", self::getFieldEnums('employment', 'employment_knowledge_workers')]
                    ),
                    array(
                        'employment_workers_at_increased_risk_to_life_and_health',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_EMPLOYMENT_WORKERS_AT_INCREASED_RISK_TO_LIFE_AND_HEALTH"),
                        false,
                        ["selectbox", self::getFieldEnums('employment', 'employment_workers_at_increased_risk_to_life_and_health')]
                    ),
                    array(
                        'employment_no',
                        Loc::getMessage("ICODES_AISINTEGR_OPTION_EMPLOYMENT_NO"),
                        false,
                        ["selectbox", self::getFieldEnums('employment', 'employment_no')]
                    ),


//
////...................................................ENUMERATION
//
////...................................................BOOLEAN
            /**
             *  The_insured
             */

            array(
                'the_insured',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_INSURED"),
                false,
                ["selectbox",  $arDealFields['boolean']],
            ),

            /**
             *  Persons_leaving_for_sports_bool
             */

            array(
                'persons_leaving_for_sports',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_PERSONS_LEAVING_FOR_SPORTS"),
                false,
                ["selectbox", $arDealFields['boolean']],
            ),

            /**
             *  Conclusion_of_insurance_contracts_Belarus
             */
            array(
                'conclusion_of_insurance_contracts_Belarus',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_CONCLUSION_OF_INSURANCE_CONTRACTS_BELARUS"),
                false,
                ["selectbox", $arDealFields['boolean']],
            ),

            /**
             *  Conclusion_general_agreement
             */
            array(
                'conclusion_general_agreement',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_CONCLUSION_GENERAL_AGREEMENT"),
                false,
                ["selectbox", $arDealFields['boolean']],
            ),

            /**
             *  Premium_payment_cards
             */
            array(
                'premium_payment_cards',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_PREMIUM_PAYMENT_CARDS"),
                false,
                ["selectbox", $arDealFields['boolean']],
            ),

            /**
             *  One_off_insurance
             */
            array(
                'one_off_insurance',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_ONE_OFF_INSURANCE"),
                false,
                ["selectbox", $arDealFields['boolean']],
            ),

            /**
             *  Employee_insurance
             */
            array(
                'employee_insurance',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_EMPLOYEE_INSURANCE"),
                false,
                ["selectbox", $arDealFields['boolean']],
            ),

            /**
             *  Self_appeal_of_the_insured
             */
            array(
                'self_appeal_of_the_insured',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_SELF_APPEAL_OF_THE_INSURED"),
                false,
                ["selectbox", $arDealFields['boolean']],
            ),

            /**
             *  Persons_traveling_to_close_relatives
             */
            array(
                'persons_traveling_to_close_relatives',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_PERSONS_TRAVELING_TO_CLOSE_RELATIVES"),
                false,
                ["selectbox", $arDealFields['boolean']],
            ),

            /**
             *  International_transport_drivers
             */
            array(
                'international_transport_drivers',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_INTERNATIONAL_TRANSPORT_DRIVERS"),
                false,
                ["selectbox", $arDealFields['boolean']],
            ),

            /**
             *  Students_studying_abroad_for_30_days
             */
            array(
                'students_studying_abroad_for_30_days',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_STUDENTS_STUDYING_ABROAD_FOR_30_DAYS"),
                false,
                ["selectbox", $arDealFields['boolean']],
            ),

////...................................................BOOLEAN

////...................................................TEXT

            /**
             *  Other_conditions_of_the_insurance_contract
             */
            array(
                'other_conditions_of_the_insurance_contract',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_OTHER_CONDITIONS_OF_THE_INSURANCE_CONTRACT"),
                false,
                ["text", $arDealFields['string']],
            ),
            /**
             *  Face_id
             */
            array(
                'face_id',
                Loc::getMessage("ICODES_AISINTEGR_OPTION_FACE_ID"),
                false,
                ["text", $arDealFields['string']],
            ),

        );
        //echo '<pre>'.print_r(self::$enumReference, true).'</pre>';
        return $options;
    }
}