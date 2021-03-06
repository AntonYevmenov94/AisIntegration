<?php


namespace ICodes\AISIntegration\Helpers;

use Bitrix\Main\Config\Option;

class extendedoptions
{
    const MODULE_ID = 'icodes.aisintegration';

    public static function GetCrmOptions()
    {
        $options = array();
        $key = Option::get(self::MODULE_ID, "conclusion_date_contract");
        $options[$key]='conclusion_date_contract';

        $key = Option::get(self::MODULE_ID, "entering_length_of_stay");
        $options[$key] = 'entering_length_of_stay';

        $key = Option::get(self::MODULE_ID, "insured_person_type");
        $options[$key][$key] = 'insured_person_type';
        $options[$key][Option::get(self::MODULE_ID, "insured_person_type_individual")] = 'insured_person_type_individual';
        $options[$key][Option::get(self::MODULE_ID, "insured_person_type_individual_entrepreneur")] = 'insured_person_type_individual_entrepreneur';
        $options[$key][Option::get(self::MODULE_ID, "insured_person_type_enterprise")] = 'insured_person_type_enterprise';

        $key = Option::get(self::MODULE_ID, "assistance");
        $options[$key][$key] = 'assistance';
        $options[$key][Option::get(self::MODULE_ID, "insured_person_type_individual")] = 'assistance_balt';
        $options[$key][Option::get(self::MODULE_ID, "insured_person_type_individual_entrepreneur")] = 'assistance_kaliptus';
        $options[$key][Option::get(self::MODULE_ID, "insured_person_type_enterprise")] = 'assistance_test';

        $key = Option::get(self::MODULE_ID, "insurance_currency");
        $options[$key][$key] = 'insurance_currency';
        $options[$key][Option::get(self::MODULE_ID, "insurance_currency_eur")] = 'insurance_currency_eur';
        $options[$key][Option::get(self::MODULE_ID, "insurance_currency_usd")] = 'insurance_currency_usd';

        $key = Option::get(self::MODULE_ID, "contribution_payment_currency");
        $options[$key][$key] = 'contribution_payment_currency';
        $options[$key][Option::get(self::MODULE_ID, "contribution_payment_currency_eur")] = 'contribution_payment_currency_eur';
        $options[$key][Option::get(self::MODULE_ID, "contribution_payment_currency_usd")] = 'contribution_payment_currency_usd';
        $options[$key][Option::get(self::MODULE_ID, "contribution_payment_currency_rub")] = 'contribution_payment_currency_rub';
        $options[$key][Option::get(self::MODULE_ID, "contribution_payment_currency_byr")] = 'contribution_payment_currency_byr';

        $key = Option::get(self::MODULE_ID, "territory_of_action_by_country");
        $options[$key][$key] = 'territory_of_action_by_country';
        $options[$key][Option::get(self::MODULE_ID, "territory_of_action_by_country_schengen")] = 'territory_of_action_by_country_schengen';
        $options[$key][Option::get(self::MODULE_ID, "territory_of_action_by_country_all")] = 'territory_of_action_by_country_all';
        $options[$key][Option::get(self::MODULE_ID, "territory_of_action_by_country_except_set_1")] = 'territory_of_action_by_country_except_set_1';

        $key = Option::get(self::MODULE_ID, "contribution_payment_mode");
        $options[$key][$key] = 'contribution_payment_mode';
        $options[$key][Option::get(self::MODULE_ID, "contribution_payment_mode_annually")] = 'contribution_payment_mode_annually';
        $options[$key][Option::get(self::MODULE_ID, "contribution_payment_mode_at_a_time")] = 'contribution_payment_mode_at_a_time';

        $key = Option::get(self::MODULE_ID, "regular_customer");
        $options[$key][$key] = 'regular_customer';
        $options[$key][Option::get(self::MODULE_ID, "regular_customer_more_than_twice")] = 'regular_customer_more_than_twice';
        $options[$key][Option::get(self::MODULE_ID, "regular_customer_re")] = 'regular_customer_re';
        $options[$key][Option::get(self::MODULE_ID, "regular_customer_no")] = 'regular_customer_no';

        $key = Option::get(self::MODULE_ID, "existing_contracts_for_other_types");
        $options[$key][$key] = 'existing_contracts_for_other_types';
        $options[$key][Option::get(self::MODULE_ID, "existing_contracts_for_other_types_by_one_type")] = 'existing_contracts_for_other_types_by_one_type';
        $options[$key][Option::get(self::MODULE_ID, "existing_contracts_for_other_types_by_two_types")] = 'existing_contracts_for_other_types_by_two_types';
        $options[$key][Option::get(self::MODULE_ID, "existing_contracts_for_other_types_three_or_more_types")] = 'existing_contracts_for_other_types_three_or_more_types';
        $options[$key][Option::get(self::MODULE_ID, "existing_contracts_for_other_types_no")] = 'existing_contracts_for_other_types_no';

        $key = Option::get(self::MODULE_ID, "franchise");
        $options[$key][$key] = 'franchise';
        $options[$key][Option::get(self::MODULE_ID, "franchise_unconditional")] = 'franchise_unconditional';
        $options[$key][Option::get(self::MODULE_ID, "franchise_no_franchise")] = 'franchise_no_franchise';

        $key = Option::get(self::MODULE_ID, "insurance_object_type");
        $options[$key][$key] = 'insurance_object_type';
        $options[$key][Option::get(self::MODULE_ID, "insurance_object_type_individual")] = 'insurance_object_type_individual';

        $key = Option::get(self::MODULE_ID, "sum_insured");
        $options[$key][$key] = 'sum_insured';
        $options[$key][Option::get(self::MODULE_ID, "sum_insured_30001-30_000")] = 'sum_insured_30001-30_000';
        $options[$key][Option::get(self::MODULE_ID, "sum_insured_60000-60_000")] = 'sum_insured_60000-60_000';
        $options[$key][Option::get(self::MODULE_ID, "sum_insured_100000-100_000")] = 'sum_insured_100000-100_000';

        $key = Option::get(self::MODULE_ID, "active_rest");
        $options[$key][$key] = 'active_rest';
        $options[$key][Option::get(self::MODULE_ID, "active_rest_yes")] = 'active_rest_yes';
        $options[$key][Option::get(self::MODULE_ID, "active_rest_no_increased_risk")] = 'active_rest_no_increased_risk';
        $options[$key][Option::get(self::MODULE_ID, "active_rest_high_risk")] = 'active_rest_high_risk';

        $key = Option::get(self::MODULE_ID, "employment");
        $options[$key][$key] = 'employment';
        $options[$key][Option::get(self::MODULE_ID, "employment_office_workers")] = 'employment_office_workers';
        $options[$key][Option::get(self::MODULE_ID, "employment_knowledge_workers")] = 'employment_knowledge_workers';
        $options[$key][Option::get(self::MODULE_ID, "employment_workers_at_increased_risk_to_life_and_health")] = 'employment_workers_at_increased_risk_to_life_and_health';
        $options[$key][Option::get(self::MODULE_ID, "employment_no")] = 'employment_no';

        $options[Option::get(self::MODULE_ID, "the_insured")] = 'the_insured';

        $options[Option::get(self::MODULE_ID, "the_insured")] = 'the_insured';

        $options[Option::get(self::MODULE_ID, "persons_leaving_for_sports")] = 'persons_leaving_for_sports';

        $options[Option::get(self::MODULE_ID, "conclusion_of_insurance_contracts_Belarus")] = 'conclusion_of_insurance_contracts_Belarus';

        $options[Option::get(self::MODULE_ID, "conclusion_general_agreement")] = 'conclusion_general_agreement';

        $options[Option::get(self::MODULE_ID, "premium_payment_cards")] = 'premium_payment_cards';

        $options[Option::get(self::MODULE_ID, "one_off_insurance")] = 'one_off_insurance';

        $options[Option::get(self::MODULE_ID, "employee_insurance")] = 'employee_insurance';

        $options[Option::get(self::MODULE_ID, "self_appeal_of_the_insured")] = 'self_appeal_of_the_insured';

        $options[Option::get(self::MODULE_ID, "persons_traveling_to_close_relatives")] = 'persons_traveling_to_close_relatives';

        $options[Option::get(self::MODULE_ID, "international_transport_drivers")] = 'international_transport_drivers';

        $options[Option::get(self::MODULE_ID, "students_studying_abroad_for_30_days")] = 'students_studying_abroad_for_30_days';

        $options["UF_CRM_1642688108"] = 'other_conditions_of_the_insurance_contract';

        $options["UF_CRM_1642688138"] = 'face_id';

        return $options;
    }

    public static function GetAISInsuranceOptions()
    {
        $options = array();

        //???????? ???????????????????? ????????????????
        $options["conclusion_date"] = '100001';

        //???????? ???????????? ???????????????? ????????????????
        $options["start_date"] = '100002';

        //?????? ???????? ???????????????????????? (1-???????????????????? ????????, 2-???????????????????????????? ??????????????????????????????, 3-?????????????????????? ????????)
        $options["insured_person_type"]["insured_person_type"] = "110001";
        $options["insured_person_type"]["insured_person_type_individual"] = "1";
        $options["insured_person_type"]["insured_person_type_individual_entrepreneur"] = "2";
        $options["insured_person_type"]["insured_person_type_enterprise"] = "3";

        //***?????????????????????????? ???????? ????????????????????????
        $options["person_insured_id"] = "110002";

        //??????????????????(100008-BALT ASSISTANCE LTD, 100010-Kaliptus Assistance, 100013-????????)
        $options['assistance']['assistance'] = "7703103411932";
        $options['assistance']['assistance_balt'] = "100008";
        $options['assistance']['assistance_kaliptus'] = "100010";
        $options['assistance']['assistance_test'] = "100013";

        //???????????????? ???????? ????????????????????
        $options['entering_length_of_stay'] = "7703091661236";

        //???????????????????????????? ?????????????????????? ???????????? ?????? ?? ?????????????? ??????????????????(1-????, 0-??????)
        $options['one_off_insurance'] = "981150001415584572";

        //?????????????????????? ?????????????????????? ?????????????? ????????, ??????????????????, ?????????? ????????????????, ?????????????????????? ????????????????????????????(1-????, 0-??????)
        $options['employee_insurance'] = "981150001415584575";

        //???????????? ??????????????????????(978-EUR, 840-USD)
        $options['insurance_currency']["insurance_currency"] = "7701470357763";
        $options['insurance_currency']["insurance_currency_eur"] = "978";
        $options['insurance_currency']["insurance_currency_usd"] = "840";

        //???????????? ???????????? ????????????(810-RUB, 978-EUR, 840-USD, 933-BYN)
        $options['contribution_payment_currency']["contribution_payment_currency"] = "7701470358108";
        $options['contribution_payment_currency']["contribution_payment_currency_rub"] = "810";
        $options['contribution_payment_currency']["contribution_payment_currency_eur"] = "978";
        $options['contribution_payment_currency']["contribution_payment_currency_usd"] = "840";
        $options['contribution_payment_currency']["contribution_payment_currency_byr"] = "933";

        //???????????????????? ???????????????? (???????????? ??????????) ???????????????? ??????????????????????(6-????????????, 8-?????? ???????????? ????????, 7-?????? ????????????, ???? ?????????????????????? ??????, ????????????, ??????????????????, ??????????????)
        $options['territory_of_action_by_country']["territory_of_action_by_country"] = "98115000136064698";
        $options['territory_of_action_by_country']["territory_of_action_by_country_schengen"] = "6";
        $options['territory_of_action_by_country']["territory_of_action_by_country_except_set_1"] = "7";
        $options['territory_of_action_by_country']["territory_of_action_by_country_all"] = "8";

        //?????????? ???????????? ???????????? (???????????? ??????????) ???????????????? ??????????????????????(101-????????????????, 1-??????????????????????????)
        $options['contribution_payment_mode']["contribution_payment_mode"] = "7701456400852";
        $options['contribution_payment_mode']["contribution_payment_mode_annually"] = "101";
        $options['contribution_payment_mode']["contribution_payment_mode_at_a_time"] = "1";

        //???????????? ?????????????????????? ???????????????????? ?????????????????? ???????????????? ?????????????? ???????????? (1-????, 0-??????)
        $options['premium_payment_cards'] = "981150001415584578";

        //???????????????????? ???????????? (30-?????????? 2-?? ??????, 20-?????????????????? ??????????????????, 10-??????)
        $options['regular_customer']["regular_customer"] = "98115000136064712";
        $options['regular_customer']["regular_customer_more_than_twice"] = "30";
        $options['regular_customer']["regular_customer_re-appeal"] = "20";
        $options['regular_customer']["regular_customer_no"] = "10";

        //???????????????????????? - ?????????????????? ????-???????????????????????? ?????? ???? ?????????????? (1-????, 0-??????)
        $options['the_insured'] = "981150001415585008";

        //?? ???????????????????????? ???????? ?????????????????????? ???????????????? ???? ???????????? ?????????? ?? ???????? ?????????????????? (5-???? 1 ????????, 6-???? 2 ??????????, 7-???? 3 ?? ?????????? ??????????, 1-??????)
        $options['existing_contracts_for_other_types']["existing_contracts_for_other_types"] = "981150001415584590";
        $options['existing_contracts_for_other_types']["existing_contracts_for_other_types_by_one_type"] = "5";
        $options['existing_contracts_for_other_types']["existing_contracts_for_other_types_by_two_types"] = "6";
        $options['existing_contracts_for_other_types']["existing_contracts_for_other_types_three_or_more_types"] = "7";
        $options['existing_contracts_for_other_types']["existing_contracts_for_other_types_no"] = "1";

        //???????????????????? ?????????????????? ?????????????????????? ???? ?????????? 1-5 ???????? ???? ???????????????????? ?????????????? ?????????????? ?? ???????????????????? ???????????????? (1-????, 0-??????)
        $options['conclusion_of_insurance_contracts_Belarus'] = "981150001415584593";

        //???????????????????? ???????????????????????? ???????????????????? (1-????, 0-??????)
        $options['conclusion_general_agreement'] = "981150001415584596";

        //?????????????????????????????? (?????? ????????????????????) ?????????????????? ???????????????????????? ?????? ???????????????????? ???????????????? ?????????????????????? (1-????, 0-??????)
        $options['self_appeal_of_the_insured'] = "98115000135723927";

        //???????????????? (2-??????????????????????, 10-?????? ????????????????)
        $options['franchise']["franchise"] = "98115000137072886";
        $options['franchise']["franchise_unconditional"] = "2";
        $options['franchise']["franchise_no_franchise"] = "10";

        //???????? ?????????????? ???????????????? ??????????????????????
        $options['other_conditions_of_the_insurance_contract'] = "7702247554158";

        //?????? ?????????????? ??????????????????????(7702828465963-???????????????????? ????????)
        $options['insurance_object_type']["insurance_object_type"] = "200000";
        $options['insurance_object_type']["insurance_object_type_individual"] = "7702828465963";

        //?????????????????????????? ????????
        $options['face_id'] = "98110468156157200";

        //?????????????????? ?????????? (?? ???????????? ??????????????????????) (30001-30 000, 60000-60 000, 100000-100 000)
        $options['sum_insured']["sum_insured"] = "98115000136089401";
        $options['sum_insured']["sum_insured_30001-30_000"] = "30001";
        $options['sum_insured']["sum_insured_60000-60_000"] = "60000";
        $options['sum_insured']["sum_insured_100000-100_000"] = "100000";

        //????????, ???????????????????? ?????? ?????????????? ?????????????? ???? ???????????????????????????????? ???????????? (1-????, 0-??????)
        $options['persons_leaving_for_sports'] = "981150001415584605";

        //?????????????? ???????????????? ?????????????? (?? ?????? ?????????? ?????????????????????????? ??????????) (1-????, 3-?????? ?????????????????????? ??????????, 4-?? ???????????????????? ????????????)
        $options['active_rest']["active_rest"] = "981150001415584608";
        $options['active_rest']["active_rest_yes"] = "1";
        $options['active_rest']["active_rest_no_increased_risk"] = "3";
        $options['active_rest']["active_rest_high_risk"] = "4";

        //????????, ???????????????????????????? ?? ?????????????? ?????????????????????????? (1-????, 0-??????)
        $options['persons_traveling_to_close_relatives'] = "981150001415584611";

        //???????????? ???? ?????????? (?????????? ?????????????????? ??????????????????????????????) (10-?????????????? ??????????????????, ?????????????????? ?????????????????????????????????? ??????????, 12-??????, 11-??????????????????, ?????????????????? ?????????????? ?????????????????? ?? ?????????????????????? ??????????????????, ???????????????????? ???????????? ?????? ?????????? ?? ???????????????? (?????????? ??????????????????, ???????????????????????????? ?????????????????????????? ??????????????????))
        $options['employment']["employment"] = "981150001415585284";
        $options['employment']["employment_office_workers"] = "10";
        $options['employment']["employment_no"] = "12";
        $options['employment']["employment_workers_at_increased_risk_to_life_and_health"] = "11";

        //???????????????????????????????? ????????????????, ?????????????????????????? ?????????????????? (1-????, 0-??????)
        $options['international_transport_drivers'] = "98115000136082788";

        //????????????????, ???????????????????? ???????????????? ???? ??????????????, ?????? ???????????????????? ???????????????? ?????????????????????? ?? ???????????????????????????????????? ?????????????? 30 ???????? ?? ?????????? (1-????, 0-??????)
        $options['students_studying_abroad_for_30_days'] = "981150001415584617";

        return $options;
    }
}