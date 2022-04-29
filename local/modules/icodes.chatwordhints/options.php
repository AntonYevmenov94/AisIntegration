<?
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\HttpApplication,
    Bitrix\Main\Application,
    Bitrix\Main\Loader,
    Bitrix\Main\Config\Option;

global $APPLICATION;

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);

Loc::loadMessages(__FILE__);
Loader::includeModule($module_id);
Loader::includeModule('iblock');

$LOG_ELEMUPD_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($LOG_ELEMUPD_RIGHT>="R") :


    $arIBlock = array('');
    $iblockFilter = array('IBLOCK_TYPE_ID' => 'lists');

	$iblockResult = \Bitrix\Iblock\IblockTable::getList(array(
		'filter' => array('IBLOCK_TYPE_ID' => 'lists'),
		'order' => array('ID'=>'ASC'),

	));



	while($iblock=$iblockResult->fetch())
	{
        $id = (int)$iblock['ID'];
        $arIBlock[$id] = '['.$id.'] '.$iblock['NAME'];
	}


    $aTabs = array(
        array(
            "DIV" => "options",
            "TAB" => Loc::getMessage("ICODES_CHATWH_TAB_MAIN"),
            "TITLE" => Loc::getMessage("ICODES_CHATWH_TAB_TITLE_MAIN"),
            "OPTIONS" => array(
                Loc::getMessage("ICODES_CHATWH_OPTIONS_TITLE_MAIN_NAME"),
                array(
                    "iblock_element_hint",
                    Loc::getMessage("ICODES_CHATWH_OPTIONS_IBLOCK_ELEMENT_HINT"),
                    "",
                    array("selectbox", $arIBlock)
                ),
            )
        ),

    );

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

        <input type="submit" name="apply" value="<? echo(Loc::GetMessage("ICODES_CHATWH_OPTIONS_INPUT_APPLY")); ?>" class="adm-btn-save" />
        <?
        echo(bitrix_sessid_post());
        ?>

    </form>
    <?
    $tabControl->End();
    ?>
<?endif;?>