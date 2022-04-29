<?
namespace Icodes\TaskSyncro\Api;

use \Bitrix\Main,
    \Bitrix\Main\Application,
    \Icodes\TaskSyncro\Helpers\RequestToServer,
    \Icodes\TaskSyncro\Config;

class Task{


    /** Функция Обработчика события добавления задачи
     * @param $arFields
     * @throws \Exception
     */
    public function eventTaskAdd($id,$arFields){
        $workConfig = new Config();
        if($workConfig::getMainServerBool()=="Y") {
            $result = array(
                "TITLE" => $arFields["TITLE"],
                "DESCRIPTION" => $arFields["DESCRIPTION"],
                "DEADLINE" => $arFields["DEADLINE"],
                "START_DATE_PLAN" => $arFields["START_DATE_PLAN"],
                "END_DATE_PLAN" => $arFields["END_DATE_PLAN"],
                "PRIORITY" => $arFields["PRIORITY"],
                "ALLOW_CHANGE_DEADLINE" => $arFields["ALLOW_CHANGE_DEADLINE"],
                "TASK_CONTROL" => $arFields["TASK_CONTROL"],
                "TIME_ESTIMATE" => $arFields["TIME_ESTIMATE"],
                "DECLINE_REASON" => $arFields["DECLINE_REASON"],
                "STATUS" => $arFields["STATUS"],
                "DURATION_TYPE" => $arFields["DURATION_TYPE"],
                "DURATION_PLAN" => $arFields["DURATION_PLAN"],
                "MARK" => $arFields["MARK"],
                "ALLOW_TIME_TRACKING" => $arFields["ALLOW_TIME_TRACKING"],
                "ADD_IN_REPORT" => $arFields["ADD_IN_REPORT"],
                "MATCH_WORK_TIME" => $arFields["MATCH_WORK_TIME"],
                "CREATED_DATE" => $arFields["CREATED_DATE"],
                "ACTIVITY_DATE" => $arFields["ACTIVITY_DATE"],
                "CHANGED_DATE" => $arFields["CHANGED_DATE"],
                "STATUS_CHANGED_DATE" => $arFields["STATUS_CHANGED_DATE"]
            );
            $arQuery = array();

            foreach ($arFields["ACCOMPLICES"] as $userId) {
                $rsUser = \Bitrix\Main\UserTable::GetList(array(
                    "filter" => array("ID" => $userId),
                    "select" => array("UF_DEPARTMENT","UF_ALTERNATIVE_ID")
                ));

                if ($arUser = $rsUser->Fetch()) {
                    $departmentId = $arUser["UF_DEPARTMENT"][0];
                    $portalUserId = $arUser["UF_ALTERNATIVE_ID"];
                }
                if ($departmentId && \Bitrix\Main\Loader::IncludeModule("iblock")) {
                    $departmentBlockId = \Bitrix\Iblock\SectionTable::GetList(array("filter" => array('ID' => $departmentId), "select" => array("IBLOCK_ID")))->Fetch()["IBLOCK_ID"];
                    $WebHookURL = \CIBlockSection::GetList(array(), array('ID' => $departmentId, "IBLOCK_ID" => $departmentBlockId), true, array("UF_WEB_HOOK"), false)->GetNext()["UF_WEB_HOOK"];
                    if ($WebHookURL) {
                        $result["RESPONSIBLE_ID"] = $portalUserId;
                        $result["CREATED_BY"] = $portalUserId;
                        $arQuery["FIELDS"] = $result;
                        $arQuery["TYPE_OF_QUERY"] = "ADD_TASK";
                        $arQuery["MAIN_ID"] = $id;
                        $server = RequestToServer::send($WebHookURL, $arQuery);
                    } else
                        continue;
                } else
                    continue;
            }
        }
    }

    /** Функция Обработчика события удаления задачи
     * @param $arFields
     * @throws \Exception
     */
    public function eventTaskDelete($ID,$arFields){
        $workConfig = new Config();
        if($workConfig::getMainServerBool()=="Y") {

            foreach ($arFields["ACCOMPLICES"] as $userId) {
                $rsUser = \Bitrix\Main\UserTable::GetList(array(
                    "filter" => array("ID" => $userId),
                    "select" => array("UF_DEPARTMENT")
                ));

                if ($arUser = $rsUser->Fetch())
                    $departmentId = $arUser["UF_DEPARTMENT"][0];
                if ($departmentId && \Bitrix\Main\Loader::IncludeModule("iblock")) {
                    $departmentBlockId = \Bitrix\Iblock\SectionTable::GetList(array("filter" => array('ID' => $departmentId), "select" => array("IBLOCK_ID")))->Fetch()["IBLOCK_ID"];
                    $WebHookURL = \CIBlockSection::GetList(array(), array('ID' => $departmentId, "IBLOCK_ID" => $departmentBlockId), true, array("UF_WEB_HOOK"), false)->GetNext()["UF_WEB_HOOK"];
                    if ($WebHookURL) {
                        $arQuery =  Array();
                        $arQuery["MAIN_ID"] = $ID;
                        $arQuery["TYPE_OF_QUERY"] = "DELETE_TASK";
                        $server = RequestToServer::send($WebHookURL, $arQuery);
                    } else
                        continue;
                } else
                    continue;
            }
        }
    }



        /** Функция Обработчика события обновления задачи
     * @param $arFields
     * @throws \Exception
     */
    public function eventTaskUpdate($ID,$arFields){

        if(!$arFields["FROM_MAIN"]) {
            $workConfig = new Config();
            $result = array(
                "TITLE" => $arFields["TITLE"],
                "DESCRIPTION" => $arFields["DESCRIPTION"],
                "DEADLINE" => $arFields["DEADLINE"],
                "START_DATE_PLAN" => $arFields["START_DATE_PLAN"],
                "END_DATE_PLAN" => $arFields["END_DATE_PLAN"],
                "PRIORITY" => $arFields["PRIORITY"],
                "ALLOW_CHANGE_DEADLINE" => $arFields["ALLOW_CHANGE_DEADLINE"],
                "TASK_CONTROL" => $arFields["TASK_CONTROL"],
                "TIME_ESTIMATE" => $arFields["TIME_ESTIMATE"],
                "DECLINE_REASON" => $arFields["DECLINE_REASON"],
                "STATUS" => $arFields["STATUS"],
                "DURATION_TYPE" => $arFields["DURATION_TYPE"],
                "DURATION_PLAN" => $arFields["DURATION_PLAN "],
                "MARK" => $arFields["MARK"],
                "ALLOW_TIME_TRACKING" => $arFields["ALLOW_TIME_TRACKING"],
                "ADD_IN_REPORT" => $arFields["ADD_IN_REPORT"],
                "MATCH_WORK_TIME" => $arFields["MATCH_WORK_TIME"],
                "CREATED_DATE" => $arFields["CREATED_DATE"],
                "ACTIVITY_DATE" => $arFields["ACTIVITY_DATE"],
                "CHANGED_DATE" => $arFields["CHANGED_DATE"],
                "STATUS_CHANGED_DATE" => $arFields["STATUS_CHANGED_DATE"]
            );
            foreach ($result as $key => $res) {
                if (!$result[$key])
                    unset($result[$key]);
            }
            $arQuery = array();


            if($workConfig::getMainServerBool()=="Y") {
                $obTask = new \CTasks;
                if(!$arFields["ACCOMPLICES"])
                    $arFields["ACCOMPLICES"] = $obTask::GetByID($ID)->GetNext()["ACCOMPLICES"];



                foreach ($arFields["ACCOMPLICES"] as $userId) {
                    $rsUser = \Bitrix\Main\UserTable::GetList(array(
                        "filter" => array("ID" => $userId),
                        "select" => array("UF_DEPARTMENT","UF_ALTERNATIVE_ID")
                    ));

                    if ($arUser = $rsUser->Fetch()) {
                        $departmentId = $arUser["UF_DEPARTMENT"][0];
                        $portalUserId = $arUser["UF_ALTERNATIVE_ID"];
                    }
                    if ($departmentId && \Bitrix\Main\Loader::IncludeModule("iblock")) {
                        $departmentBlockId = \Bitrix\Iblock\SectionTable::GetList(array("filter" => array('ID' => $departmentId), "select" => array("IBLOCK_ID")))->Fetch()["IBLOCK_ID"];

                        $departmentInfo = \CIBlockSection::GetList(array(), array('ID' => $departmentId, "IBLOCK_ID" => $departmentBlockId), true, array("UF_WEB_HOOK","UF_SERVER_COMPANY_HOST"), false)->GetNext();
                        $WebHookURL = $departmentInfo["UF_WEB_HOOK"];
                        $departmentHOST = $departmentInfo["UF_SERVER_COMPANY_HOST"];

                        if ($WebHookURL && $departmentHOST) {
                            if($arFields["FROM_SERVER"] && $arFields["FROM_SERVER"]==$departmentHOST)
                                continue;
                            $result["RESPONSIBLE_ID"] = $portalUserId;
                            $result["FROM_MAIN"] = "Y";
                            $arQuery["FIELDS"] = $result;
                            $arQuery["TYPE_OF_QUERY"] = "UPDATE_TASK";
                            $arQuery["MAIN_ID"] = $ID;
                            $server = RequestToServer::send($WebHookURL, $arQuery);
                        } else
                            continue;
                    } else
                        continue;
                }
            }
            else{
                if($arFields["RESPONSIBLE_ID"] && self::CheckForDelegation($ID,$arFields))
                {
                    $mainIdField = Config::getMainIdField();
                    $obTask = new \CTasks;
                    $MainTaskId = $obTask::GetList(array(), array("ID" => $ID), array($mainIdField))->GetNext()[$mainIdField];
                    $MainWebHook = $workConfig::getMainServerWebHook();
                    if ($MainWebHook && $MainTaskId) {
                        $result["FROM_SERVER"] = $_SERVER["HTTP_HOST"];
                        $arQuery["NEW_RESPONSIBLE"] = $arFields["RESPONSIBLE_ID"];
                        $arQuery["OLD_RESPONSIBLE"] = $obTask::GetList(Array(),Array("ID"=>$ID),Array("RESPONSIBLE_ID"))->GetNext()["RESPONSIBLE_ID"];
                        $arQuery["MAIN_ID"] = $MainTaskId;
                        $arQuery["TYPE_OF_QUERY"] = "DELEGATE_TASK";
                        $server = RequestToServer::send($MainWebHook, $arQuery);
                    }
                }
                else {
                    $mainIdField = Config::getMainIdField();
                    $obTask = new \CTasks;
                    $MainTaskId = $obTask::GetList(array(), array("ID" => $ID), array($mainIdField))->GetNext()[$mainIdField];
                    $MainWebHook = $workConfig::getMainServerWebHook();

                    if ($MainWebHook && $MainTaskId) {
                        $result["FROM_SERVER"] = $_SERVER["HTTP_HOST"];
                        $arQuery["FIELDS"] = $result;
                        $arQuery["MAIN_ID"] = $MainTaskId;
                        $arQuery["TYPE_OF_QUERY"] = "UPDATE_TASK";
                        $server = RequestToServer::send($MainWebHook, $arQuery);
                    }
                }

            }
        }
    }

    public function eventTaskTimeAddMain($ID,$arFields){
        if(!$arFields["FROM_MAIN"]) {
            $workConfig = new Config();
            $obTask = new \CTasks;
            if($workConfig::getMainServerBool()=="Y") {
                $accomplices = $obTask::GetByID($arFields["TASK_ID"])->GetNext()["ACCOMPLICES"];
                foreach ($accomplices as $userId) {
                    $rsUser = \Bitrix\Main\UserTable::GetList(array(
                        "filter" => array("ID" => $userId),
                        "select" => array("UF_DEPARTMENT","UF_ALTERNATIVE_ID")
                    ));

                    if ($arUser = $rsUser->Fetch()) {
                        $departmentId = $arUser["UF_DEPARTMENT"][0];
                        $portalUserId = $arUser["UF_ALTERNATIVE_ID"];
                    }
                    if ($departmentId && \Bitrix\Main\Loader::IncludeModule("iblock") && $portalUserId) {
                        $departmentBlockId = \Bitrix\Iblock\SectionTable::GetList(array("filter" => array('ID' => $departmentId), "select" => array("IBLOCK_ID")))->Fetch()["IBLOCK_ID"];

                        $departmentInfo = \CIBlockSection::GetList(array(), array('ID' => $departmentId, "IBLOCK_ID" => $departmentBlockId), true, array("UF_WEB_HOOK","UF_SERVER_COMPANY_HOST"), false)->GetNext();
                        $WebHookURL = $departmentInfo["UF_WEB_HOOK"];
                        $departmentHOST = $departmentInfo["UF_SERVER_COMPANY_HOST"];

                        if ($WebHookURL && $departmentHOST) {
                            if($arFields["FROM_SERVER"] && $arFields["FROM_SERVER"]==$departmentHOST)
                                continue;
                            $arFields["USER_ID"] = $portalUserId;
                            $arFields["FROM_MAIN"] = "Y";
                            unset($arFields["MINUTES"]);
                            $arQuery = Array(
                                "FIELDS"        => $arFields,
                                "MAIN_ID"       => $ID,
                                "TYPE_OF_QUERY" => "ADD_TASK_TIME"
                            );
                            $server = RequestToServer::send($WebHookURL, $arQuery);
                        } else
                            continue;
                    } else
                        continue;
                }
            }
        }
    }

    public function eventTaskTimeAdd(&$arFields){
        if(!$arFields["FROM_MAIN"]) {
            $workConfig = new Config();
            $obTask = new \CTasks;
            if($workConfig::getMainServerBool()!="Y") {
                $mainIdField = Config::getMainIdField();
                $obTask = new \CTasks;
                $MainTaskId = $obTask::GetList(array(), array("ID" => $arFields["TASK_ID"]), array($mainIdField))->GetNext()[$mainIdField];
                $MainWebHook = $workConfig::getMainServerWebHook();
                $arFields["FROM_SERVER"] = $_SERVER["HTTP_HOST"];
                $arQuery = Array(
                    "FIELDS"        =>  $arFields,
                    "TYPE_OF_QUERY" => "ADD_TASK_TIME",
                    "MAIN_ID"       => $MainTaskId
                );
                unset($arQuery["FIELDS"]["MINUTES"]);

                $server = RequestToServer::send($MainWebHook, $arQuery);
                $arFields["COMMENT_TEXT"] .= "{{".$server["result"]."}}";
            }
        }
    }

    public function eventTaskTimeUpdate($ID,$arFields,&$newInfo){

        if(!$newInfo["FROM_MAIN"]) {
            $workConfig = new Config();
            $obTask = new \CTasks;
            if($workConfig::getMainServerBool()=="Y") {
                $accomplices = $obTask::GetByID($arFields["TASK_ID"])->GetNext()["ACCOMPLICES"];
                foreach ($accomplices as $userId) {
                    $rsUser = \Bitrix\Main\UserTable::GetList(array(
                        "filter" => array("ID" => $userId),
                        "select" => array("UF_DEPARTMENT","UF_ALTERNATIVE_ID")
                    ));

                    if ($arUser = $rsUser->Fetch()) {
                        $departmentId = $arUser["UF_DEPARTMENT"][0];
                        $portalUserId = $arUser["UF_ALTERNATIVE_ID"];
                    }
                    if ($departmentId && \Bitrix\Main\Loader::IncludeModule("iblock")) {
                        $departmentBlockId = \Bitrix\Iblock\SectionTable::GetList(array("filter" => array('ID' => $departmentId), "select" => array("IBLOCK_ID")))->Fetch()["IBLOCK_ID"];

                        $departmentInfo = \CIBlockSection::GetList(array(), array('ID' => $departmentId, "IBLOCK_ID" => $departmentBlockId), true, array("UF_WEB_HOOK","UF_SERVER_COMPANY_HOST"), false)->GetNext();
                        $WebHookURL = $departmentInfo["UF_WEB_HOOK"];
                        $departmentHOST = $departmentInfo["UF_SERVER_COMPANY_HOST"];

                        if ($WebHookURL && $departmentHOST) {
                            if($arFields["FROM_SERVER"] && $newInfo["FROM_SERVER"]==$departmentHOST)
                                continue;
                            $arQuery = Array(
                                "FIELDS"        => $newInfo,
                                "MAIN_ID"       => $ID,
                                "TYPE_OF_QUERY" => "UPDATE_TASK_TIME",
                                "TASK_ID"       => $arFields["TASK_ID"]
                            );
                            $arQuery["FIELDS"]["FROM_MAIN"] = "Y";
                            unset($arQuery["FIELDS"]["MINUTES"]);
                            $server = RequestToServer::send($WebHookURL, $arQuery);
                        } else
                            continue;
                    } else
                        continue;
                }
            }
            else{
                preg_match('/\{{\d+}}/', $arFields["COMMENT_TEXT"],$match);
                preg_match("/\d+/", $match[0], $matches);
                $mainTaskTimeId = $matches[0];
                $mainIdField = Config::getMainIdField();
                $obTask = new \CTasks;
                $MainTaskId = $obTask::GetList(array(), array("ID" => $arFields["TASK_ID"]), array($mainIdField))->GetNext()[$mainIdField];
                $MainWebHook = $workConfig::getMainServerWebHook();
                if($mainTaskTimeId && $MainWebHook){
                    $arQuery = Array(
                        "MAIN_ID"       => $mainTaskTimeId,
                        "TYPE_OF_QUERY" => "UPDATE_TASK_TIME",
                        "TASK_ID"       => $MainTaskId,
                        "FIELDS"        => $newInfo
                    );
                    $arQuery["FIELDS"]["FROM_SERVER"] =  $_SERVER["HTTP_HOST"];
                    unset($arQuery["FIELDS"]["MINUTES"]);
                    if($newInfo["COMMENT_TEXT"])
                        $arQuery["FIELDS"]["COMMENT_TEXT"] = preg_split('/\{{\d+}}/', $newInfo["COMMENT_TEXT"])[0];
                    $server = RequestToServer::send($MainWebHook, $arQuery);
                    if($newInfo["COMMENT_TEXT"]){
                        preg_match('/\{{\d+}}/', $arFields["COMMENT_TEXT"],$match);
                        preg_match("/\d+/", $match[0], $matches);
                        if($matches[0]){
                            $newInfo["COMMENT_TEXT"] = preg_split('/\{{\d+}}/', $newInfo["COMMENT_TEXT"])[0];
                            $newInfo["COMMENT_TEXT"] .= '{{'.$mainTaskTimeId.'}}';
                        }
                        else{
                            $newInfo["COMMENT_TEXT"] .= '{{'.$mainTaskTimeId.'}}';
                        }
                    }
                }
            }
        }
        else{
            preg_match('/\{{\d+}}/', $arFields["COMMENT_TEXT"],$match);
            preg_match("/\d+/", $match[0], $matches);
            $mainTaskTimeId = $matches[0];
            $newInfo["COMMENT_TEXT"] .= '{{'.$mainTaskTimeId.'}}';
        }
    }


    public function eventTaskTimeDeleteMain($ID,$arFields){
        if(!$arFields["FROM_MAIN"]) {
            $workConfig = new Config();
            $obTask = new \CTasks;
            if($workConfig::getMainServerBool()=="Y") {
                $accomplices = $obTask::GetByID($arFields["TASK_ID"])->GetNext()["ACCOMPLICES"];
                foreach ($accomplices as $userId) {
                    $rsUser = \Bitrix\Main\UserTable::GetList(array(
                        "filter" => array("ID" => $userId),
                        "select" => array("UF_DEPARTMENT")
                    ));

                    if ($arUser = $rsUser->Fetch())
                        $departmentId = $arUser["UF_DEPARTMENT"][0];
                    if ($departmentId && \Bitrix\Main\Loader::IncludeModule("iblock")) {
                        $departmentBlockId = \Bitrix\Iblock\SectionTable::GetList(array("filter" => array('ID' => $departmentId), "select" => array("IBLOCK_ID")))->Fetch()["IBLOCK_ID"];

                        $departmentInfo = \CIBlockSection::GetList(array(), array('ID' => $departmentId, "IBLOCK_ID" => $departmentBlockId), true, array("UF_WEB_HOOK","UF_SERVER_COMPANY_HOST"), false)->GetNext();
                        $WebHookURL = $departmentInfo["UF_WEB_HOOK"];
                        $departmentHOST = $departmentInfo["UF_SERVER_COMPANY_HOST"];

                        if ($WebHookURL && $departmentHOST) {
                            if($arFields["FROM_SERVER"] && $arFields["FROM_SERVER"]==$departmentHOST)
                                continue;
                            $arFields["USER_ID"] = $userId;
                            $arFields["FROM_MAIN"] = "Y";
                            $arQuery = Array(
                                "FIELDS"        => $arFields,
                                "MAIN_ID"       => $ID,
                                "TYPE_OF_QUERY" => "DELETE_TASK_TIME"
                            );
                            $server = RequestToServer::send($WebHookURL, $arQuery);
                        } else
                            continue;
                    } else
                        continue;
                }
            }
            else{
                preg_match('/\{{\d+}}/', $arFields["COMMENT_TEXT"],$match);
                preg_match("/\d+/", $match[0], $matches);
                $mainTaskTimeId = $matches[0];
                $mainIdField = Config::getMainIdField();
                $obTask = new \CTasks;
                $MainTaskId = $obTask::GetList(array(), array("ID" => $arFields["TASK_ID"]), array($mainIdField))->GetNext()[$mainIdField];
                $MainWebHook = $workConfig::getMainServerWebHook();
                if($mainTaskTimeId && $MainWebHook){
                    $arQuery = Array(
                        "MAIN_ID"       => $mainTaskTimeId,
                        "TYPE_OF_QUERY" => "DELETE_TASK_TIME",
                        "TASK_ID"       => $MainTaskId
                    );

                    $server = RequestToServer::send($MainWebHook, $arQuery);
                }
            }
        }
    }

    public function CommentAdd($ID,&$arFields){
        if(!$arFields["FROM_MAIN"]) {
            $workConfig = new Config();
            $obTask = new \CTasks;
            if($workConfig::getMainServerBool()=="Y") {
                $accomplices = $obTask::GetByID($arFields["TASK_ID"])->GetNext()["ACCOMPLICES"];

                foreach ($accomplices as $userId) {
                    $rsUser = \Bitrix\Main\UserTable::GetList(array(
                        "filter" => array("ID" => $userId),
                        "select" => array("UF_DEPARTMENT","UF_ALTERNATIVE_ID")
                    ));

                    if ($arUser = $rsUser->Fetch()) {
                        $departmentId = $arUser["UF_DEPARTMENT"][0];
                        $portalUserId = $arUser["UF_ALTERNATIVE_ID"];
                    }
                    if ($departmentId && \Bitrix\Main\Loader::IncludeModule("iblock") && $portalUserId) {
                        $departmentBlockId = \Bitrix\Iblock\SectionTable::GetList(array("filter" => array('ID' => $departmentId), "select" => array("IBLOCK_ID")))->Fetch()["IBLOCK_ID"];

                        $departmentInfo = \CIBlockSection::GetList(array(), array('ID' => $departmentId, "IBLOCK_ID" => $departmentBlockId), true, array("UF_WEB_HOOK","UF_SERVER_COMPANY_HOST"), false)->GetNext();
                        $WebHookURL = $departmentInfo["UF_WEB_HOOK"];
                        $departmentHOST = $departmentInfo["UF_SERVER_COMPANY_HOST"];

                        if ($WebHookURL && $departmentHOST) {
                            $connection = \Bitrix\Main\Application::getConnection();
                            $sql = "SELECT * FROM b_forum_message WHERE ID=".$ID;
                            $recordSet = $connection->query($sql);
                            if($record = $recordSet->fetch()) {
                                $rsUser = \Bitrix\Main\UserTable::GetList(array(
                                    "filter" => array("ID" => $record["AUTHOR_ID"]),
                                    "select" => array("UF_DEPARTMENT","UF_ALTERNATIVE_ID")
                                ));
                                if ($arUser = $rsUser->Fetch()) {
                                    $departmentcreateById = $arUser["UF_DEPARTMENT"][0];
                                    $createById = $arUser["UF_ALTERNATIVE_ID"];
                                }
                                if($departmentcreateById == $departmentId && $createById)
                                    $portalUserId = $createById;
                                $serviceData = $record["SERVICE_DATA"];
                                $serviceId   = $record["SERVICE_TYPE"];
                            }
                            $arQuery = Array(
                                "FIELDS" => Array(
                                    "AUTHOR_ID"      =>  $portalUserId,
                                    "POST_MESSAGE"   =>  $arFields["COMMENT_TEXT"]."{{".$ID."}}",
                                    "USE_SMILES"     =>  "N",
                                    "FILES"          =>  Array(),
                                    "AUX"            =>  "N"
                                    ),
                                "TYPE_OF_QUERY" => "ADD_COMMENT",
                                "MAIN_ID"       => $arFields["TASK_ID"],
                                "ALT_MESSAGE"   => $arFields["COMMENT_TEXT"]
                            );

                            if(!($serviceData || $serviceId))
                                $server = RequestToServer::send($WebHookURL, $arQuery);

                        } else
                            continue;
                    } else
                        continue;
                }
            }else{
                if(!preg_match('/\{{\d+}}/',$arFields["COMMENT_TEXT"]))
                {
                    $MainWebHook = $workConfig::getMainServerWebHook();
                    $mainIdField = Config::getMainIdField();
                    $MainTaskId = $obTask::GetList(array(), array("ID" => $arFields["TASK_ID"]), array($mainIdField))->GetNext()[$mainIdField];
                    if($MainTaskId && $MainWebHook){
                        $connection = \Bitrix\Main\Application::getConnection();
                        $sql = "SELECT * FROM b_forum_message WHERE ID=".$ID;
                        $recordSet = $connection->query($sql);
                        if($record = $recordSet->fetch()) {
                            $userId      = $record["AUTHOR_ID"];
                            $serviceData = $record["SERVICE_DATA"];
                            $serviceId   = $record["SERVICE_TYPE"];
                        }

                        $arQuery = Array(
                            "FIELDS" => Array(
                                "AUTHOR_ID"      =>  $userId,
                                "POST_MESSAGE"   =>  $arFields["COMMENT_TEXT"],
                                "USE_SMILES"     =>  "N",
                                "FILES"          =>  Array(),
                                "AUX"            =>  "N"
                            ),
                            "TYPE_OF_QUERY" => "ADD_COMMENT",
                            "TASK_ID"       => $MainTaskId
                        );
                        if(!($serviceData || $serviceId)) {
                            $server = RequestToServer::send($MainWebHook, $arQuery);
                            if($server) {
                                $task = \CTaskItem::getInstance($arFields["TASK_ID"], $userId);
                                $comment = new \CTaskCommentItem($task, $ID);
                                $comment->update(Array("POST_MESSAGE"=>$arFields["COMMENT_TEXT"]."{{".$server["result"]."}}"));
                            }
                        }
                    }

                }
            }
        }
    }

    public function CommentUpdate($ID,&$arFields){
        $workConfig = new Config();
        $obTask = new \CTasks;
        if($workConfig::getMainServerBool()=="Y") {
            $accomplices = $obTask::GetByID($arFields["TASK_ID"])->GetNext()["ACCOMPLICES"];

            foreach ($accomplices as $userId) {
                $rsUser = \Bitrix\Main\UserTable::GetList(array(
                    "filter" => array("ID" => $userId),
                    "select" => array("UF_DEPARTMENT","UF_ALTERNATIVE_ID")
                ));

                if ($arUser = $rsUser->Fetch()) {
                    $departmentId = $arUser["UF_DEPARTMENT"][0];
                    $portalUserId = $arUser["UF_ALTERNATIVE_ID"];
                }
                if ($departmentId && \Bitrix\Main\Loader::IncludeModule("iblock") && $portalUserId) {
                    $departmentBlockId = \Bitrix\Iblock\SectionTable::GetList(array("filter" => array('ID' => $departmentId), "select" => array("IBLOCK_ID")))->Fetch()["IBLOCK_ID"];

                    $departmentInfo = \CIBlockSection::GetList(array(), array('ID' => $departmentId, "IBLOCK_ID" => $departmentBlockId), true, array("UF_WEB_HOOK","UF_SERVER_COMPANY_HOST"), false)->GetNext();
                    $WebHookURL = $departmentInfo["UF_WEB_HOOK"];
                    $departmentHOST = $departmentInfo["UF_SERVER_COMPANY_HOST"];

                    if ($WebHookURL && $departmentHOST) {
                        $connection = \Bitrix\Main\Application::getConnection();
                        $sql = "SELECT * FROM b_forum_message WHERE ID=".$ID;
                        $recordSet = $connection->query($sql);
                        if($record = $recordSet->fetch()) {
                            $rsUser = \Bitrix\Main\UserTable::GetList(array(
                                "filter" => array("ID" => $record["AUTHOR_ID"]),
                                "select" => array("UF_DEPARTMENT","UF_ALTERNATIVE_ID")
                            ));
                            if ($arUser = $rsUser->Fetch()) {
                                $departmentcreateById = $arUser["UF_DEPARTMENT"][0];
                                $createById = $arUser["UF_ALTERNATIVE_ID"];
                            }
                            if($departmentcreateById == $departmentId && $createById)
                                $portalUserId = $createById;
                            $serviceData = $record["SERVICE_DATA"];
                            $serviceId   = $record["SERVICE_TYPE"];
                            $message = $record["POST_MESSAGE"]."{{".$record["ID"]."}}";
                        }
                        $arQuery = Array(
                            "AUTHOR_ID"      =>  $portalUserId,
                            "TYPE_OF_QUERY" => "UPDATE_COMMENT",
                            "MAIN_ID"       => $arFields["TASK_ID"],
                            "COMMENT_ID"    => $ID,
                            "FIELDS"        => Array(
                                "POST_MESSAGE" => $message
                            )
                        );
                        if(!($serviceData || $serviceId))
                            $server = RequestToServer::send($WebHookURL, $arQuery);
                    } else
                        continue;
                } else
                    continue;
            }
        }else{
            if(preg_match('/\{{\d+}}/',$arFields["COMMENT_TEXT"])){
                $connection = \Bitrix\Main\Application::getConnection();
                $sql = "SELECT * FROM b_forum_message WHERE ID=".$ID;
                $recordSet = $connection->query($sql);
                if($record = $recordSet->fetch()) {
                    if($record["POST_MESSAGE"]!=$arFields["COMMENT_TEXT"]){
                        preg_match('/\{{\d+}}/', $arFields["COMMENT_TEXT"],$match);
                        preg_match("/\d+/", $match[0], $matches);
                        $mainTaskCommentId = $matches[0];
                        $MainWebHook = $workConfig::getMainServerWebHook();
                        $mainIdField = Config::getMainIdField();
                        $MainTaskId = $obTask::GetList(array(), array("ID" => $arFields["TASK_ID"]), array($mainIdField))->GetNext()[$mainIdField];
                        $message = preg_split('/\{{\d+}}/', $record["POST_MESSAGE"])[0];
                        $arQuery = Array(
                            "AUTHOR_ID"     =>  $record["AUTHOR_ID"],
                            "TYPE_OF_QUERY" => "UPDATE_COMMENT",
                            "MAIN_ID"       => $MainTaskId,
                            "COMMENT_ID"    => $mainTaskCommentId,
                            "FIELDS"        => Array(
                                "POST_MESSAGE" => $message
                            )
                        );
                        if($MainWebHook && $MainTaskId && $mainTaskCommentId)
                            $server = RequestToServer::send($MainWebHook, $arQuery);
                    }
                }
            }
        }
    }

    public function CommentDelete($ID,&$arFields){
        $workConfig = new Config();
        $obTask = new \CTasks;
        if($workConfig::getMainServerBool()=="Y") {
            $accomplices = $obTask::GetByID($arFields["TASK_ID"])->GetNext()["ACCOMPLICES"];

            foreach ($accomplices as $userId) {
                $rsUser = \Bitrix\Main\UserTable::GetList(array(
                    "filter" => array("ID" => $userId),
                    "select" => array("UF_DEPARTMENT","UF_ALTERNATIVE_ID")
                ));

                if ($arUser = $rsUser->Fetch()) {
                    $departmentId = $arUser["UF_DEPARTMENT"][0];
                    $portalUserId = $arUser["UF_ALTERNATIVE_ID"];
                }
                if ($departmentId && \Bitrix\Main\Loader::IncludeModule("iblock") && $portalUserId) {
                    $departmentBlockId = \Bitrix\Iblock\SectionTable::GetList(array("filter" => array('ID' => $departmentId), "select" => array("IBLOCK_ID")))->Fetch()["IBLOCK_ID"];

                    $departmentInfo = \CIBlockSection::GetList(array(), array('ID' => $departmentId, "IBLOCK_ID" => $departmentBlockId), true, array("UF_WEB_HOOK","UF_SERVER_COMPANY_HOST"), false)->GetNext();
                    $WebHookURL = $departmentInfo["UF_WEB_HOOK"];
                    $departmentHOST = $departmentInfo["UF_SERVER_COMPANY_HOST"];

                    if ($WebHookURL && $departmentHOST) {

                        $arQuery = Array(
                            "AUTHOR_ID"      =>  $portalUserId,
                            "TYPE_OF_QUERY" => "DELETE_COMMENT",
                            "MAIN_ID"       => $arFields["TASK_ID"],
                            "COMMENT_ID"    => $ID
                        );

                        $server = RequestToServer::send($WebHookURL, $arQuery);
                    } else
                         continue;
                } else
                    continue;
            }
        }else{
            if(preg_match('/\{{\d+}}/',$arFields["COMMENT_TEXT"])) {
                preg_match('/\{{\d+}}/', $arFields["COMMENT_TEXT"],$match);
                preg_match("/\d+/", $match[0], $matches);
                $mainTaskCommentId = $matches[0];
                $mainIdField = Config::getMainIdField();
                $obTask = new \CTasks;
                $MainTaskId = $obTask::GetList(array(), array("ID" => $arFields["TASK_ID"]), array($mainIdField))->GetNext()[$mainIdField];
                $ResponsibleId = $obTask::GetList(array(), array("ID" => $arFields["TASK_ID"]), array("RESPONSIBLE_ID"))->GetNext()["RESPONSIBLE_ID"];
                $MainWebHook = $workConfig::getMainServerWebHook();
                if($mainTaskCommentId && $MainWebHook){
                    $arQuery = Array(
                        "MAIN_ID"       => $mainTaskCommentId,
                        "TYPE_OF_QUERY" => "DELETE_COMMENT",
                        "TASK_ID"       => $MainTaskId,
                        "AUTHOR_ID"     => $ResponsibleId
                    );

                    $server = RequestToServer::send($MainWebHook, $arQuery);
                }
            }
        }
    }

    public function CheckForDelegation($ID,$arFields){
        $obTask = new \CTasks;
        $prevResponsibleId = $obTask::GetList(Array(),Array("ID"=>$ID),Array("RESPONSIBLE_ID"))->GetNext()["RESPONSIBLE_ID"];
        $realResponsibleId = $arFields["RESPONSIBLE_ID"];
        if($prevResponsibleId!=$realResponsibleId)
            return true;
        else
            return false;
    }

}