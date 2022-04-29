<?
namespace Icodes\TaskSyncro;
use \Bitrix\Main,
    \Bitrix\Main\Config\Option;

class Config{
    const MODULE_ID  = 'icodes.tasksyncro';


    public function getMainIdField(){
        $fieldID = Option::get(Config::MODULE_ID, "MAIN_TASK_ID");
        return $fieldID;
    }

    public function getMainServerBool(){
        $fieldID = Option::get(Config::MODULE_ID, "MAIN_SERVER_BOOL");
        return $fieldID;
    }

    public function getMainServerWebHook(){
        $fieldID = Option::get(Config::MODULE_ID, "MAIN_SERVER_WEB_HOOK");
        return $fieldID;
    }

    public function getRealId($ID){
        $rsUser = \Bitrix\Main\UserTable::GetList(array(
            "filter" => array("UF_ALTERNATIVE_ID" => $ID),
            "select" => array("ID")
        ));
        return $rsUser->Fetch()["ID"];
    }

    public function getStructureIBlock(){
        return Option::get(Config::MODULE_ID, "STRUCTURE_IBLOCK");
    }
}
