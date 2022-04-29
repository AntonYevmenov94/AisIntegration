<?
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\HttpApplication,
    Bitrix\Main\Application,
    Bitrix\Main\Loader,
    Bitrix\Main\Config\Option;
global $APPLICATION;

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);

$LOG_ELEMUPD_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($LOG_ELEMUPD_RIGHT>="R") :

    Loc::loadMessages(__FILE__);
    Loader::includeModule($module_id);


    $arTaskFields = Array();
    $dbUserFields = \Bitrix\Main\UserFieldTable::getList(array(
        'filter' => array('ENTITY_ID' => 'TASKS_TASK'),
        'select' => array('ID')
    ));
    while ($arUserField = $dbUserFields->fetch()) {
        $arUserField = \CUserTypeEntity::GetByID($arUserField['ID']); // В этом методе есть запрос lang файлов
        $arTaskFields[$arUserField['FIELD_NAME']] = $arUserField["EDIT_FORM_LABEL"]["ru"];
    }

    $arIblocks = Array();
    if(\Bitrix\Main\Loader::IncludeModule("iblock")){
        $db_list = CIBlock::GetList(Array(), Array(), true);
        while($ar_result = $db_list->GetNext()){
            $arIblocks[$ar_result["ID"]] = $ar_result["NAME"];
        }
    }


    $aTabs = array(
        array(
            "DIV" => "auth",
            "TAB" => GetMessage("ICODES_TASK_SYNCRO_TAB_AUTH"),
            "TITLE" => GetMessage("ICODES_SYNCRO_TAB_TITLE_AUTH"),
            "OPTIONS" => array(
                array(
                    "MAIN_TASK_ID",
                    GetMessage("ICODES_TASK_SYNCRO_FIELD_TASK_ID"),
                    false,
                    ["selectbox",$arTaskFields]
                ),
                array(
                    "MAIN_SERVER_BOOL",
                    GetMessage("ICODES_TASK_SYNCRO_MAIN_SERVER_BOOL"),
                    false,
                    ["checkbox","N"]
                ),
                array(
                    "MAIN_SERVER_WEB_HOOK",
                    GetMessage("ICODES_TASK_SYNCRO_MAIN_SERVER_WEB_HOOK"),
                    false,
                    ["text"]
                ),
                array(
                    "STRUCTURE_IBLOCK",
                    GetMessage("ICODES_TASK_SYNCRO_STRUCTURE_IBLOCK"),
                    false,
                    ["selectbox",$arIblocks]
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

        <input type="submit" name="apply" value="<? echo(Loc::GetMessage("ICODES_TASK_SYNCRO_SAVE_BUTTON")); ?>" class="adm-btn-save" />
        <?
        echo(bitrix_sessid_post());
        ?>

    </form>
    <input type="button" onclick="syncroUsers()" value="<? echo(Loc::GetMessage("ICODES_TASK_SYNCRO_USER_BUTTON")); ?>" />
    <?
    $tabControl->End();
    ?>
    <script>
        function syncroUsers() {
            BX.ajax.runAction('icodes:tasksyncro.controller.ajax.handler', {}).then(
                function (response) {
                    alert("Готово!")
                },
                function (response) {
                console.log(response);
            });
        }

    </script>
<?endif;?>