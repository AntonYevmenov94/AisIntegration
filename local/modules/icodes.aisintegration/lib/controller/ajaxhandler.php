<?php

namespace ICodes\AISIntegration\Controller;

use Bitrix\Main\Engine\Controller,
    Bitrix\Main\Loader,
    Bitrix\Main\Engine\ActionFilter,
    ICodes\AISIntegration\Helpers\OptionFields;
use ICodes\AISIntegration\Helpers\extendedoptions;

class AjaxHandler extends Controller
{
    /**
     * @return array
     */
    public function configureActions()
    {
        return [
            'getEnumList' => [
                'prefilters' => [
                    new ActionFilter\Authentication(),
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ]
            ]
        ];
    }

    /** Get property enum list
     * @param $propName
     * @return string
     */
    public static function getEnumListAction($propName)
    {
        if(Loader::includeModule('crm')) {
            $out = new OptionFields();
            $result = $out->getFieldEnums($propName);
            $result_vision ='<option selected></option>';
            foreach ($result as $key => $val){
                $result_vision .= "<option value='$key' >".$val."</option>";
            }
            return  $result_vision;
        }
    }

    /** Get property enum list
     * @param $dealId
     * @return string
     */
    public static function insuranceApplyAction($dealId)
    {
        if(Loader::includeModule('crm'))
        {
            $arr = array();
            $deal = \CAllCrmDeal::GetList(
                $arOrder = Array('DATE_CREATE' => 'DESC'),
                $arFilter = Array("ID" => $dealId)
            )->Fetch();
            $arr["deal"] = $deal;
            $crmOptions = extendedoptions::GetCrmOptions();
            $arr["crm"] = $crmOptions;
            $aisOptions = extendedoptions::GetAISInsuranceOptions();
            $arr["ais"] = $aisOptions;

            $postParams = array();
            foreach ($crmOptions as $key => $value)
            {
                if(is_array($value))
                {
                    $aisName = $value[$key];
                    if(is_array($aisOptions[$aisName]))
                    {
                        $paramName = $aisOptions[$aisName][$aisName];
                        $paramValue = $aisOptions[$aisName][$value[$deal[$key]]];
                    }
                    else
                    {
                        $paramName = $aisOptions[$aisName];
                        $paramValue = $deal[$key];
                    }
                }
                else
                {
                    $aisName = $value;
                    if(is_array($aisOptions[$aisName]))
                    {
                        $paramName = $aisOptions[$aisName][$aisName];
                        $paramValue = $aisOptions[$aisName][$value[$deal[$key]]];
                    }
                    else
                    {
                        $paramName = $aisOptions[$aisName];
                        $paramValue = $deal[$key];
                    }
                }
                $postParams[$paramName] = $paramValue;
            }
            $postParams["100001"] = $deal["BEGINDATE"];
            $postParams["100002"] = $deal["BEGINDATE"];
            $postParams["100003"] = $deal["CLOSEDATE"];

            //Вызов API
            $client = new HttpClient();
            $url = "http://localhost:54332/api/products";
            $postData = http_build_query($postParams);
            $respons = $client->query("POST", $url);

            return $client->getError();
//            $curl = curl_init();
//            return "gfgf";
//            $data = http_build_query($postParams);
//            curl_setopt($curl, CURLOPT_URL, "http://localhost:54332/api/products");
//            curl_setopt($curl, CURLOPT_POST, true);
//            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//            curl_exec($curl);
//            curl_close($curl);
//            return $data;
        }
    }
}

