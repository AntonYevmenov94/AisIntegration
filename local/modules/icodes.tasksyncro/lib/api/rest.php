<?php
namespace Icodes\TaskSyncro\Api;

use \Bitrix\Main,
    \Icodes\TaskSyncro\Config;

class Rest{
    public static function OnRestServiceBuildDescription()
    {
        return array(
            'icodes' => array(
                'icodes.taskHandler' => array(
                    'callback' => array(__CLASS__, 'taskHandler'),
                    'options' => array(),
                ),
            )
        );
    }

    public function  taskHandler($arFields){

        if ($arFields["TYPE_OF_QUERY"] == "ADD_TASK"){
            return self::AddTask($arFields);
        }
        elseif ($arFields["TYPE_OF_QUERY"] == "DELETE_TASK"){
            return self::DeleteTask($arFields);
        }
        elseif ($arFields["TYPE_OF_QUERY"] == "UPDATE_TASK"){
            return self::UpdateTask($arFields);
        }
        elseif($arFields["TYPE_OF_QUERY"] == "DELEGATE_TASK"){
            return self::DelegateTask($arFields);
        }
        elseif($arFields["TYPE_OF_QUERY"] == "ADD_TASK_TIME"){
            return self::AddTaskTime($arFields);
        }
        elseif($arFields["TYPE_OF_QUERY"] == "DELETE_TASK_TIME"){
            return self::DeleteTaskTime($arFields);
        }
        elseif($arFields["TYPE_OF_QUERY"] == "UPDATE_TASK_TIME"){
            return self::UpdateTaskTime($arFields);
        }
        elseif($arFields["TYPE_OF_QUERY"] == "ADD_USER"){
            return self::AddUser($arFields);
        }
        elseif($arFields["TYPE_OF_QUERY"] == "UPDATE_USER"){
            return self::UpdateUser($arFields);
        }
        elseif($arFields["TYPE_OF_QUERY"] == "DELETE_USER"){
            return self::DeleteUser($arFields);
        }
        elseif($arFields["TYPE_OF_QUERY"] == "SYNCRO_USERS"){
            return self::SyncroUsers($arFields);
        }
        elseif($arFields["TYPE_OF_QUERY"] == "ADD_COMMENT"){
            return self::CommentAdd($arFields);
        }
        elseif($arFields["TYPE_OF_QUERY"] == "DELETE_COMMENT"){
            return self::CommentDelete($arFields);
        }
        elseif($arFields["TYPE_OF_QUERY"] == "UPDATE_COMMENT"){
            return self::CommentUpdate($arFields);
        }
    }


    /**
     * @param $arFields array
     * @return string
     * Функция добавления задачи (Add task function)
     */
    public function AddTask($arFields){

        if (\Bitrix\Main\Loader::includeModule("tasks"))
        {
            global $APPLICATION;
            $obTask = new \CTasks;
            $workConfig = new Config;
            $mainIdField = Config::getMainIdField();
            $arFields["FIELDS"][$mainIdField] = $arFields["MAIN_ID"];
            $ID = $obTask->Add($arFields["FIELDS"]);

            $success = ($ID>0);

            if($success)
            {
                return "Task was added!";
            }
            else
            {
                return $APPLICATION->GetException()->GetString();
            }

        }
    }


    /**
     * @param $arFields array
     * @return string
     * Функция удаления задачи (Delete task function)
     */
    public function DeleteTask($arFields){
        if (\Bitrix\Main\Loader::includeModule("tasks"))
        {
            global $APPLICATION;
            $obTask = new \CTasks;
            $workConfig = new Config;
            if(Config::getMainServerBool()=="Y"){

            }
            else{
                $mainIdField = Config::getMainIdField();
                $res = $obTask::GetList(Array(), Array($mainIdField => $arFields["MAIN_ID"]));

                if ($arTask = $res->GetNext())
                {

                    $success = $obTask::Delete($arTask["ID"]);
                    if($success){
                        return "Task was deleted!";
                    }
                    else{
                        return $APPLICATION->GetException()->GetString();
                    }
                }
            }

        }
    }





    public function UpdateTask($arFields){
        if (\Bitrix\Main\Loader::includeModule("tasks")) {
            $workConfig = new Config;

            global $APPLICATION;
            $obTask = new \CTasks;
            $mainIdField = Config::getMainIdField();
            if (Config::getMainServerBool()=="Y") {
                $res = $obTask::GetList(array(), array("ID" => $arFields["MAIN_ID"]));
            }
            else{
                $res = $obTask::GetList(Array(), Array($mainIdField => $arFields["MAIN_ID"]));
            }

            if ($arTask = $res->GetNext()) {

                $success = $obTask->Update($arTask["ID"], $arFields["FIELDS"]);

                if ($success) {
                    return "Task was updated!";
                } else {
                    return $APPLICATION->GetException()->GetString();
                }
            }
        }
    }




    public function DelegateTask($arFields){
        if (\Bitrix\Main\Loader::includeModule("tasks")) {
            $workConfig = new Config;

            global $APPLICATION;
            $obTask = new \CTasks;
            $oldRealId = $workConfig::getRealId($arFields["OLD_RESPONSIBLE"]);
            $newRealId = $workConfig::getRealId($arFields["NEW_RESPONSIBLE"]);

            $accomplices = $obTask::GetByID($arFields["MAIN_ID"])->GetNext()["ACCOMPLICES"];

            foreach ($accomplices as $key=>$accomply){
                if ($accomply == $oldRealId)
                    $accomplices[$key] = $newRealId;
            }
            $accomplices = Array("ACCOMPLICES"=>$accomplices);

            $success = $obTask->Update($arFields["MAIN_ID"], $accomplices);

            if ($success) {
                return "Task was delegeted!";
            } else {
                return $APPLICATION->GetException()->GetString();
            }
        }
    }

    public function AddTaskTime($arFields){
        if (\Bitrix\Main\Loader::includeModule("tasks")) {
            $workConfig = new Config;
            global $APPLICATION;
            if (Config::getMainServerBool()=="Y") {
                $obElapsed = new \CTaskElapsedTime;
                $arFields["FIELDS"]["TASK_ID"] = $arFields["MAIN_ID"];
                $arFields["FIELDS"]["USER_ID"] = $workConfig::getRealId($arFields["FIELDS"]["USER_ID"]);
                $success = $obElapsed->Add($arFields["FIELDS"]);
                if($success){
                    return $success;
                }
                else{
                    return $APPLICATION->GetException()->GetString();
                }
            }
            else{
                $obTask = new \CTasks;
                $obElapsed = new \CTaskElapsedTime;
                $mainIdField = Config::getMainIdField();

                $taskId = $obTask::GetList(array(), array($mainIdField => $arFields["FIELDS"]["TASK_ID"]),Array("ID"))->GetNext()["ID"];
                $arFields["FIELDS"]["TASK_ID"] = $taskId;
                $arFields["FIELDS"]["COMMENT_TEXT"] .= "{{".$arFields["MAIN_ID"]."}}";
                $success = $obElapsed->Add($arFields["FIELDS"]);

                if($success){
                    return "Time was added!";
                }
                else {
                    return $APPLICATION->GetException()->GetString();
                }
            }
        }
    }

    public function DeleteTaskTime($arFields){
        if (\Bitrix\Main\Loader::includeModule("tasks")) {
            $workConfig = new Config;
            global $APPLICATION;
            if (Config::getMainServerBool()=="Y") {
                $obElapsed = new \CTaskElapsedTime;
                $connection = \Bitrix\Main\Application::getConnection();
                $sql = "SELECT * FROM b_tasks_elapsed_time WHERE TASK_ID=".$arFields["TASK_ID"]." AND ID=".$arFields["MAIN_ID"];

                $recordSet = $connection->query($sql);
                if($id = $recordSet->fetch()) {

                    $success = $obElapsed->Delete($id["ID"]);

                    if ($success) {
                        return "Time was deleted!";
                    } else {
                        return $APPLICATION->GetException()->GetString();
                    }
                }
            }
            else{
                $obTask = new \CTasks;
                $obElapsed = new \CTaskElapsedTime;
                $mainIdField = Config::getMainIdField();

                $taskId = $obTask::GetList(array(), array($mainIdField => $arFields["FIELDS"]["TASK_ID"]),Array("ID"))->GetNext()["ID"];
                $connection = \Bitrix\Main\Application::getConnection();

                $sql = "SELECT * FROM b_tasks_elapsed_time WHERE TASK_ID=".$taskId." AND COMMENT_TEXT LIKE '%{{".$arFields["MAIN_ID"]."}}'";

                $recordSet = $connection->query($sql);
                if($id = $recordSet->fetch()) {

                    $success = $obElapsed->Delete($id["ID"]);

                    if ($success) {
                        return "Time was deleted!";
                    } else {
                        return $APPLICATION->GetException()->GetString();
                    }
                }
            }
        }
    }

    public function UpdateTaskTime($arFields){
        if (\Bitrix\Main\Loader::includeModule("tasks")) {
            $workConfig = new Config;
            global $APPLICATION;
            if (Config::getMainServerBool()=="Y") {

                $obElapsed = new \CTaskElapsedTime;
                $connection = \Bitrix\Main\Application::getConnection();
                $sql = "SELECT * FROM b_tasks_elapsed_time WHERE TASK_ID=".$arFields["TASK_ID"]." AND ID=".$arFields["MAIN_ID"];

                $recordSet = $connection->query($sql);

                if($id = $recordSet->fetch()) {

                    $success = $obElapsed->Update($id["ID"],$arFields["FIELDS"]);

                    if ($success) {
                        return "Time was updated!";
                    } else {
                        return $APPLICATION->GetException()->GetString();
                    }
                }
            }
            else{
                $obTask = new \CTasks;
                $obElapsed = new \CTaskElapsedTime;
                $mainIdField = Config::getMainIdField();

                $taskId = $obTask::GetList(array(), array($mainIdField => $arFields["TASK_ID"]),Array("ID"))->GetNext()["ID"];
                $connection = \Bitrix\Main\Application::getConnection();

                $sql = "SELECT * FROM b_tasks_elapsed_time WHERE TASK_ID=".$taskId." AND COMMENT_TEXT LIKE '%{{".$arFields["MAIN_ID"]."}}'";

                $recordSet = $connection->query($sql);
                if($id = $recordSet->fetch()) {

                    $success = $obElapsed->Update($id["ID"],$arFields["FIELDS"]);

                    if ($success) {
                        return "Time was updated!";
                    } else {
                        return $APPLICATION->GetException()->GetString();
                    }
                }
            }
        }
    }

    public function SyncroUsers($arFields){

        if(\Bitrix\Main\Loader::IncludeModule("iblock") && \Bitrix\Main\Loader::IncludeModule("main") ) {
            self::CreateFields();
            $iblockId = Config::getStructureIBlock();
            $db_list = \CIBlockSection::GetList(array(), array("IBLOCK_ID" => $iblockId, "NAME" => $arFields["FROM_SERVER"]), true);

            if ($ar_result = $db_list->GetNext()) {
                $ID = $ar_result["ID"];
            } else {
                $arSection = array(
                    "ACTIVE" => "Y",
                    "IBLOCK_SECTION_ID" => \CIBlockSection::GetList(array(), array("IBLOCK_ID" => $iblockId, "DEPTH_LEVEL" => 1), true)->GetNext()["ID"],
                    "IBLOCK_ID" => $iblockId,
                    "NAME" => $arFields["FROM_SERVER"]
                );
                $bs = new \CIBlockSection;
                $ID = $bs->Add($arSection);
            }
            $userObj = new \CUser;

            foreach ($arFields["USERS"] as $user) {

                $rsUser = \Bitrix\Main\UserTable::GetList(array(
                    "filter" => array("UF_ALTERNATIVE_ID" => $user["ID"]),
                    "select" => array("ID")
                ));

                if ($arUser = $rsUser->Fetch()) {
                    if (!$user["NAME"] && !$user["SECOND_NAME"] && !$user["LAST_NAME"])
                        $user["NAME"] = $user["LOGIN"];

                    $arInfo = array(
                        "NAME" => $user["NAME"],
                        "SECOND_NAME" => $user["SECOND_NAME"],
                        "LAST_NAME" => $user["LAST_NAME"],
                        "UF_DEPARTMENT" => array($ID)
                    );

                    $result = $userObj->Update($arUser["ID"], $arInfo);

                    if (!$result)
                        return false;
                } else {
                    if (!$user["NAME"] && !$user["SECOND_NAME"] && !$user["LAST_NAME"])
                        $user["NAME"] = $user["LOGIN"];

                    $arInfo = array(
                        "NAME" => $user["NAME"],
                        "SECOND_NAME" => $user["SECOND_NAME"],
                        "LAST_NAME" => $user["LAST_NAME"],
                        "UF_DEPARTMENT" => array($ID),
                        "LOGIN" => $user["NEW_LOGIN"],
                        "PASSWORD" => "bitrix_bitrix_24",
                        "CONFIRM_PASSWORD" => "bitrix_bitrix_24",
                        "UF_ALTERNATIVE_ID" => $user["ID"],
                        "EMAIL" => str_replace(":", "", $user["NEW_LOGIN"]) . "@test.ru"
                    );
                    $result = $userObj->Add($arInfo);
                    if (!$result)
                        return false;
                }
            }
            return true;
        }
        else
            return false;
    }

    public function AddUser($arFields){
        if(\Bitrix\Main\Loader::IncludeModule("iblock") && \Bitrix\Main\Loader::IncludeModule("main") ) {
            self::CreateFields();
            $iblockId = Config::getStructureIBlock();
            $db_list = \CIBlockSection::GetList(array(), array("IBLOCK_ID" => $iblockId, "NAME" => $arFields["FROM_SERVER"]), true);
            if ($ar_result = $db_list->GetNext()) {
                $ID = $ar_result["ID"];
            } else {
                $arSection = array(
                    "ACTIVE" => "Y",
                    "IBLOCK_SECTION_ID" => \CIBlockSection::GetList(array(), array("IBLOCK_ID" => $iblockId, "DEPTH_LEVEL" => 1), true)->GetNext()["ID"],
                    "IBLOCK_ID" => $iblockId,
                    "NAME" => $arFields["FROM_SERVER"]
                );
                $bs = new \CIBlockSection;
                $ID = $bs->Add($arSection);
            }
            $userObj = new \CUser;

            if($ID){
                if (!$arFields["NAME"] && !$arFields["SECOND_NAME"] && !$arFields["LAST_NAME"])
                    $arFields["NAME"] = $arFields["LOGIN"];
                $arInfo = array(
                    "NAME" => $arFields["NAME"],
                    "SECOND_NAME" => $arFields["SECOND_NAME"],
                    "LAST_NAME" => $arFields["LAST_NAME"],
                    "UF_DEPARTMENT" => array($ID),
                    "LOGIN" => $arFields["NEW_LOGIN"],
                    "PASSWORD" => "bitrix_bitrix_24",
                    "CONFIRM_PASSWORD" => "bitrix_bitrix_24",
                    "UF_ALTERNATIVE_ID" => $arFields["ID"],
                    "EMAIL" => str_replace(":", "", $arFields["NEW_LOGIN"]) . "@test.ru"
                );
                $result = $userObj->Add($arInfo);
                if ($result)
                    return true;
                else
                    return $userObj->LAST_ERROR;
            }
            else
                return false;
        }
        else
            return false;
    }

    public function UpdateUser($arFields){
        if(\Bitrix\Main\Loader::IncludeModule("iblock") && \Bitrix\Main\Loader::IncludeModule("main") ) {
            self::CreateFields();
            $iblockId = Config::getStructureIBlock();
            $db_list = \CIBlockSection::GetList(array(), array("IBLOCK_ID" => $iblockId, "NAME" => $arFields["FROM_SERVER"]), true);
            if ($ar_result = $db_list->GetNext()) {
                $ID = $ar_result["ID"];
            } else {
                $arSection = array(
                    "ACTIVE" => "Y",
                    "IBLOCK_SECTION_ID" => \CIBlockSection::GetList(array(), array("IBLOCK_ID" => $iblockId, "DEPTH_LEVEL" => 1), true)->GetNext()["ID"],
                    "IBLOCK_ID" => $iblockId,
                    "NAME" => $arFields["FROM_SERVER"]
                );
                $bs = new \CIBlockSection;
                $ID = $bs->Add($arSection);
            }
            $userObj = new \CUser;
            if($ID){
                $rsUser = \Bitrix\Main\UserTable::GetList(array(
                    "filter" => array("UF_ALTERNATIVE_ID" => $arFields["ID"]),
                    "select" => array("ID")
                ));

                if ($arUser = $rsUser->Fetch()) {
                    if (!$arFields["NAME"] && !$arFields["SECOND_NAME"] && !$arFields["LAST_NAME"])
                        $arFields["NAME"] = $arFields["LOGIN"];

                    $arInfo = array(
                        "NAME" => $arFields["NAME"],
                        "SECOND_NAME" => $arFields["SECOND_NAME"],
                        "LAST_NAME" => $arFields["LAST_NAME"],
                        "UF_DEPARTMENT" => array($ID)
                    );

                    $result = $userObj->Update($arUser["ID"], $arInfo);

                    if ($result)
                        return true;
                    else
                        return $userObj->LAST_ERROR;
                }
                else
                    return false;
            }
            else
                return false;
        }
        else
            return false;
    }

    public function DeleteUser($arFields){
        if( \Bitrix\Main\Loader::IncludeModule("main") ) {
            $rsUser = \Bitrix\Main\UserTable::GetList(array(
                "filter" => array("UF_ALTERNATIVE_ID" => $arFields["ID"]),
                "select" => array("ID")
            ));

            if ($arUser = $rsUser->Fetch()) {
                $userObj = new \CUser;
                $result = $userObj->Delete($arUser["ID"]);
                if($result)
                    return true;
                else
                    return false;
            }
        }
    }

    public function CommentAdd($arFields){
        if (\Bitrix\Main\Loader::includeModule("tasks")) {
            $workConfig = new Config;
            global $APPLICATION;
            if (Config::getMainServerBool()=="Y") {
                $oTaskItem = \CTaskItem::getInstance($arFields["TASK_ID"], $workConfig::getRealId($arFields["FIELDS"]["AUTHOR_ID"]));
                $arFields["FIELDS"]["AUTHOR_ID"] = $workConfig::getRealId($arFields["FIELDS"]["AUTHOR_ID"]);

                $success = \CTaskCommentItem::add($oTaskItem, $arFields["FIELDS"]);

                if($success){
                    return $success;
                }
                else {
                    return $APPLICATION->GetException()->GetString();
                }
            }
            else{
                $obTask = new \CTasks;
                $mainIdField = Config::getMainIdField();

                $taskId = $obTask::GetList(array(), array($mainIdField => $arFields["MAIN_ID"]),Array("ID"))->GetNext()["ID"];
                $connection = \Bitrix\Main\Application::getConnection();

                $sql    = "SELECT * FROM b_forum_message WHERE XML_ID=TASK_".$taskId." AND POST_MESSAGE=".$arFields["FIELDS"]["POST_MESSAGE"];
                $sqlAlt = "SELECT * FROM b_forum_message WHERE XML_ID=TASK_".$taskId." AND POST_MESSAGE=".$arFields["ALT_MESSAGE"];

                $recordSet = $connection->query($sql);
                $AltSet = $connection->query($sql);
                if($recordSet->fetch() || $AltSet->fetch()) {
                    $success = true;
                }else{
                $oTaskItem = \CTaskItem::getInstance($taskId, $arFields["FIELDS"]["AUTHOR_ID"]);

                $success = \CTaskCommentItem::add($oTaskItem, $arFields["FIELDS"]);
                }
                if($success){
                    return "Comment was added!";
                }
                else {
                    return $APPLICATION->GetException()->GetString();
                }
            }
        }
    }

    public function CommentDelete($arFields){
        if (\Bitrix\Main\Loader::includeModule("tasks")) {
            $workConfig = new Config;
            global $APPLICATION;

            if (Config::getMainServerBool()=="Y") {

                $task = \CTaskItem::getInstance($arFields["TASK_ID"], $workConfig::getRealId($arFields["AUTHOR_ID"]));
                $comment = new \CTaskCommentItem($task, $arFields["MAIN_ID"]);

                $success = $comment->delete();

                if($success){
                    return $success;
                }
                else {
                    return $APPLICATION->GetException()->GetString();
                }
            }
            else{
                $obTask = new \CTasks;
                $mainIdField = Config::getMainIdField();

                $taskId = $obTask::GetList(array(), array($mainIdField => $arFields["MAIN_ID"]),Array("ID"))->GetNext()["ID"];

                $task = \CTaskItem::getInstance($taskId, $arFields["AUTHOR_ID"]);
                $connection = \Bitrix\Main\Application::getConnection();

                $sql = "SELECT * FROM b_forum_message WHERE POST_MESSAGE LIKE '%{{".$arFields["COMMENT_ID"]."}}'";

                $recordSet = $connection->query($sql);
                if($id = $recordSet->fetch()) {
                    $comment = new \CTaskCommentItem($task, $id["ID"]);
                    $success = $comment->delete();

                    if ($success) {
                        return "Comment was deleted!";
                    } else {
                        return $APPLICATION->GetException()->GetString();
                    }
                }
            }
        }
    }

    public function CommentUpdate($arFields){
        if (\Bitrix\Main\Loader::includeModule("tasks")) {
            $workConfig = new Config;
            global $APPLICATION;

            if (Config::getMainServerBool()=="Y") {

                $task = \CTaskItem::getInstance($arFields["MAIN_ID"], $workConfig::getRealId($arFields["AUTHOR_ID"]));
                $comment = new \CTaskCommentItem($task, $arFields["COMMENT_ID"]);

                $success = $comment->update($arFields["FIELDS"]);

                if($success){
                    return $success;
                }
                else {
                    return $APPLICATION->GetException()->GetString();
                }
            }
            else{
                $obTask = new \CTasks;
                $mainIdField = Config::getMainIdField();

                $taskId = $obTask::GetList(array(), array($mainIdField => $arFields["MAIN_ID"]),Array("ID"))->GetNext()["ID"];

                $task = \CTaskItem::getInstance($taskId, $arFields["AUTHOR_ID"]);
                $connection = \Bitrix\Main\Application::getConnection();

                $sql = "SELECT * FROM b_forum_message WHERE POST_MESSAGE LIKE '%{{".$arFields["COMMENT_ID"]."}}'";

                $recordSet = $connection->query($sql);
                if($id = $recordSet->fetch()) {
                    $comment = new \CTaskCommentItem($task, $id["ID"]);
                    $success = $comment->update($arFields["FIELDS"]);

                    if ($success) {
                        return "Comment was updated!";
                    } else {
                        return $APPLICATION->GetException()->GetString();
                    }
                }
            }
        }
    }

    public function CreateFields(){
        global $USER_FIELD_MANAGER;
        $iblockId = Config::getStructureIBlock();
        $userFieldsList = $USER_FIELD_MANAGER->getUserFields("USER", 0, LANGUAGE_ID);
        $iblockFieldsList = $USER_FIELD_MANAGER->getUserFields("IBLOCK_".$iblockId."_SECTION", 0, LANGUAGE_ID);

        $oUserTypeEntity = new \CUserTypeEntity();

        if(!$userFieldsList["UF_ALTERNATIVE_ID"]){
            $aUserFields = array(
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => "UF_ALTERNATIVE_ID",
                'USER_TYPE_ID' => 'string',
                'XML_ID' => "UF_ALTERNATIVE_ID",
                'SORT' => 500,
                'EDIT_FORM_LABEL' => array(
                    'ru' => "Альтернативный ID"
                ),
            );
            $oUserTypeEntity->Add($aUserFields);
        }

        if(!$iblockFieldsList["UF_SERVER_COMPANY_HOST"]){
            $aIblockFields = array(
                'ENTITY_ID' => 'IBLOCK_'.$iblockId.'_SECTION',
                'FIELD_NAME' => "UF_SERVER_COMPANY_HOST",
                'USER_TYPE_ID' => 'string',
                'XML_ID' => "UF_SERVER_COMPANY_HOST",
                'SORT' => 500,
                'EDIT_FORM_LABEL' => array(
                    'ru' => "Адрес дочернего портала"
                ),
            );
            $oUserTypeEntity->Add($aIblockFields);
        }

        if(!$iblockFieldsList["UF_WEB_HOOK"]){
            $aIblockFields = array(
                'ENTITY_ID' => 'IBLOCK_'.$iblockId.'_SECTION',
                'FIELD_NAME' => "UF_WEB_HOOK",
                'USER_TYPE_ID' => 'string',
                'XML_ID' => "UF_WEB_HOOK",
                'SORT' => 500,
                'EDIT_FORM_LABEL' => array(
                    'ru' => "WEB_HOOK"
                ),
            );
            $oUserTypeEntity->Add($aIblockFields);
        }
    }

}