<?
namespace Icodes\TaskSyncro\Api;

use \Bitrix\Main,
    \Bitrix\Main\Application,
    \Icodes\TaskSyncro\Helpers\RequestToServer,
    \Icodes\TaskSyncro\Config;

class User{

    public function UserSyncro(){
        $workConfig = new Config();
        if($workConfig::getMainServerBool()!="Y") {
            $counter = 0;
            $info = array();
            $MainWebHook = $workConfig::getMainServerWebHook();

            if($MainWebHook) {
                $rsUser = \Bitrix\Main\UserTable::GetList(array(
                    "filter" => array(),
                    "select" => array("LOGIN", "NAME", "SECOND_NAME", "LAST_NAME","EMAIL", "ID")
                ));
                $arQuery = array(
                    "FROM_SERVER"   => $_SERVER["HTTP_HOST"],
                    "TYPE_OF_QUERY" => "SYNCRO_USERS"
                );

                while ($arUser = $rsUser->Fetch()) {
                    if ($counter <= 100) {
                        $arUser["NEW_LOGIN"] = $arQuery["FROM_SERVER"] . "_" . $arUser["ID"];
                        array_push($info, $arUser);
                        $counter += 1;
                    } else {
                        $arQuery["USERS"] = $info;
                        $info = array();
                        $counter = 0;
                        $server = RequestToServer::send($MainWebHook, $arQuery);
                    }
                }

                if(count($info)){
                    $arQuery["USERS"] = $info;
                    $server = RequestToServer::send($MainWebHook, $arQuery);

                    if($server){
                        return true;
                    }
                    else{
                        return false;
                    }
                }
                else{
                    return true;
                }
            }
        }
        else
            return true;
    }

    public function AddUser($arFields){
        $workConfig = new Config();
        if($workConfig::getMainServerBool()!="Y") {
            $arQuery = array(
                "FROM_SERVER" => $_SERVER["HTTP_HOST"],
                "TYPE_OF_QUERY" => "ADD_USER",
                "NEW_LOGIN" => $_SERVER["HTTP_HOST"] . "_" . $arFields["ID"],
                "LOGIN" => $arFields["LOGIN"],
                "NAME" => $arFields["NAME"],
                "SECOND_NAME" => $arFields["SECOND_NAME"],
                "LAST_NAME" => $arFields["LAST_NAME"],
                "EMAIL" => $arFields["EMAIL"],
                "ID" => $arFields["ID"]
            );
            $MainWebHook = $workConfig::getMainServerWebHook();
            $server = RequestToServer::send($MainWebHook, $arQuery);
        }
    }

    public function UpdateUser($arFields){
        $workConfig = new Config();
        if($workConfig::getMainServerBool()!="Y") {
            $arQuery = array(
                "FROM_SERVER" => $_SERVER["HTTP_HOST"],
                "TYPE_OF_QUERY" => "UPDATE_USER",
                "NAME" => $arFields["NAME"],
                "SECOND_NAME" => $arFields["SECOND_NAME"],
                "LAST_NAME" => $arFields["LAST_NAME"],
                "ID" => $arFields["ID"]
            );
            $MainWebHook = $workConfig::getMainServerWebHook();
            if($MainWebHook)
                $server = RequestToServer::send($MainWebHook, $arQuery);
        }
    }

    public function DeleteUser($ID){
        $workConfig = new Config();
        if($workConfig::getMainServerBool()!="Y") {
            $arQuery = array(
                "FROM_SERVER" => $_SERVER["HTTP_HOST"],
                "TYPE_OF_QUERY" => "DELETE_USER",
                "ID" => $ID
            );
            $MainWebHook = $workConfig::getMainServerWebHook();
            if($MainWebHook)
                $server = RequestToServer::send($MainWebHook, $arQuery);
        }
    }
}