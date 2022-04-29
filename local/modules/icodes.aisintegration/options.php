<?
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\HttpApplication,
    Bitrix\Main\Application,
    Bitrix\Main\Loader,
    Bitrix\Main\Config\Option,
    ICodes\AISIntegration\AISOptions;



global $APPLICATION;

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);

$LOG_ELEMUPD_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($LOG_ELEMUPD_RIGHT>="R") :

    Loc::loadMessages(__FILE__);
    Loader::includeModule($module_id);


    $arFields = array('');

    $properties['enum_options'] = array();




    $optionTab = new AISOptions\OptionsTab();

    $generalOptions = new AISOptions\General();
    $generalTab = $optionTab::getTab($generalOptions);
    $properties['enum_options'] = array_merge($properties['enum_options'], $generalTab["ENUM_LIST"]);

    $mainMappingOptions = new AISOptions\MainMapping();
    $mainMapping = $optionTab::getTab($mainMappingOptions);
    $properties['enum_options'] = array_merge($properties['enum_options'], $mainMapping["ENUM_LIST"]);
    $arPropertiesJS = CUtil::PhpToJSObject($properties);

    $aTabs = array(
        $generalTab,
        $mainMapping
    );


    $path = str_replace($_SERVER['DOCUMENT_ROOT'], '',  __DIR__);
    $arJsConfig = array(
        'ais_insurance_options' => array(
            'js' =>$path . '/js/options.js',
            'rel' => array(),
        )
    );
    foreach ($arJsConfig as $ext => $arExt) {
        \CJSCore::RegisterExt($ext, $arExt);
    }
    CJSCore::Init(array("ais_insurance_options"));

// сохранение параметров
    if($request->isPost() && check_bitrix_sessid()){

        Option::delete($module_id);

        foreach($aTabs as $aTab){

            foreach($aTab["OPTIONS"] as $arOption){

                if(!is_array($arOption)){

                    continue;
                }

                if($arOption["note"]){

                    continue;
                }

                if($request["apply"]){

                    $optionValue = $request->getPost($arOption[0]);
                    if (!$optionValue) continue;


                    Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
                }elseif($request["default"]){

                    Option::set($module_id, $arOption[0], $arOption[2]);
                }
            }
        }

        LocalRedirect($APPLICATION->GetCurPage()."?mid=".$module_id."&lang=".LANG);
    }


    $tabControl = new CAdminTabControl(
        "tabControl",
        $aTabs
    );

    $tabControl->Begin();

    ?>

    <form action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($module_id); ?>&lang=<? echo(LANG); ?>" method="post">


        <?
        foreach($aTabs as $aTab){

            if($aTab["OPTIONS"]){

                $tabControl->BeginNextTab();

                __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
            }

        }

        $tabControl->Buttons();
        ?>

        <input type="submit" name="apply" value="<? echo(Loc::GetMessage("ICODES_AISINTEGR_OPTION_APPLY")); ?>" class="adm-btn-save" />
        <?
        echo(bitrix_sessid_post());
        ?>
    </form>

    <?
    $tabControl->End();
    ?>

    <script type="text/javascript">
        new AisIntegrationOption(<?=$arPropertiesJS?>);
    </script>

<?endif;?>