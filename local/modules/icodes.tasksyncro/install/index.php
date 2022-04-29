<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application,
	Bitrix\Main\IO\Directory,
	Bitrix\Main\Config\Option;

IncludeModuleLangFile(__FILE__);

Class icodes_tasksyncro extends CModule
{
	private $namespaceClases = [];
	const NAMESPACE = 'ICodes\TaskSyncro';
	var $MODULE_ID  = 'icodes.tasksyncro';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;

	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__) . "/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("ICODES_SYNCRO_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("ICODES_SYNCRO_MODULE_DESC");
		$this->PARTNER_NAME = Loc::getMessage("ICODES_SYNCRO_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("ICODES_SYNCRO_PARTNER_URI");
	}


	public function installEvents()
	{
		EventManager::getInstance()->registerEventHandlerCompatible(
			'tasks',
			'OnTaskAdd',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'eventTaskAdd'
		);
		EventManager::getInstance()->registerEventHandlerCompatible(
			'tasks',
			'OnBeforeTaskUpdate',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'eventTaskUpdate'
		);
		EventManager::getInstance()->registerEventHandlerCompatible(
			'tasks',
			'OnBeforeTaskDelete',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'eventTaskDelete'
		);
		EventManager::getInstance()->registerEventHandlerCompatible(
			'tasks',
			'OnBeforeTaskElapsedTimeAdd',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'eventTaskTimeAdd'
		);
		EventManager::getInstance()->registerEventHandlerCompatible(
			'tasks',
			'OnTaskElapsedTimeAdd',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'eventTaskTimeAddMain'
		);
		EventManager::getInstance()->registerEventHandlerCompatible(
			'tasks',
			'OnBeforeTaskElapsedTimeUpdate',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'eventTaskTimeUpdate'
		);
		EventManager::getInstance()->registerEventHandlerCompatible(
			'tasks',
			'OnTaskElapsedTimeDelete',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'eventTaskTimeDeleteMain'
		);
		EventManager::getInstance()->registerEventHandlerCompatible(
			'main',
			'OnAfterUserAdd',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\User',
			'AddUser'
		);
		EventManager::getInstance()->registerEventHandlerCompatible(
			'main',
			'OnAfterUserUpdate',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\User',
			'UpdateUser'
		);
		EventManager::getInstance()->registerEventHandlerCompatible(
			'main',
			'OnUserDelete',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\User',
			'DeleteUser'
		);
		EventManager::getInstance()->registerEventHandlerCompatible(
			'tasks',
			'OnAfterCommentAdd',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'CommentAdd'
		);
		EventManager::getInstance()->registerEventHandlerCompatible(
			'tasks',
			'OnAfterCommentUpdate',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'CommentUpdate'
		);
		EventManager::getInstance()->registerEventHandlerCompatible(
			'tasks',
			'OnAfterCommentDelete',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'CommentDelete'
		);
		EventManager::getInstance()->registerEventHandlerCompatible(
			'rest',
			'OnRestServiceBuildDescription',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Rest',
			'OnRestServiceBuildDescription'
		);
	}

	public function unInstallEvents()
	{
		EventManager::getInstance()->unRegisterEventHandler(
			'tasks',
			'OnTaskAdd',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'eventTaskAdd'
		);
		EventManager::getInstance()->unRegisterEventHandler(
			'tasks',
			'OnBeforeTaskUpdate',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'eventTaskUpdate'
		);
		EventManager::getInstance()->unRegisterEventHandler(
			'tasks',
			'OnBeforeTaskDelete',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'eventTaskDelete'
		);
		EventManager::getInstance()->unRegisterEventHandler(
			'tasks',
			'OnBeforeTaskElapsedTimeAdd',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'eventTaskTimeAdd'
		);
		EventManager::getInstance()->unRegisterEventHandler(
			'tasks',
			'OnTaskElapsedTimeAdd',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'eventTaskTimeAddMain'
		);
		EventManager::getInstance()->unRegisterEventHandler(
			'tasks',
			'OnBeforeTaskElapsedTimeUpdate',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'eventTaskTimeUpdate'
		);
		EventManager::getInstance()->unRegisterEventHandler(
			'tasks',
			'OnTaskElapsedTimeDelete',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'eventTaskTimeDeleteMain'
		);
		EventManager::getInstance()->unRegisterEventHandler(
			'main',
			'OnAfterUserAdd',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\User',
			'AddUser'
		);
		EventManager::getInstance()->unRegisterEventHandler(
			'main',
			'OnAfterUserUpdate',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\User',
			'UpdateUser'
		);
		EventManager::getInstance()->unRegisterEventHandler(
			'main',
			'OnUserDelete',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\User',
			'DeleteUser'
		);
		EventManager::getInstance()->unRegisterEventHandler(
			'tasks',
			'OnAfterCommentAdd',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'CommentAdd'
		);
		EventManager::getInstance()->unRegisterEventHandler(
			'tasks',
			'OnAfterCommentUpdate',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'CommentUpdate'
		);
		EventManager::getInstance()->unRegisterEventHandler(
			'tasks',
			'OnAfterCommentDelete',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Task',
			'CommentDelete'
		);
		EventManager::getInstance()->unRegisterEventHandler(
			'rest',
			'OnRestServiceBuildDescription',
			$this->MODULE_ID,
			'Icodes\TaskSyncro\Api\Rest',
			'OnRestServiceBuildDescription'
		);
	}

	public function unInstallOptions()
	{
		Option::delete($this->MODULE_ID);
	}


	function DoInstall()
	{
		global $APPLICATION;

		if(CheckVersion(ModuleManager::getVersion("main"), "14.00.15")){
			ModuleManager::registerModule($this->MODULE_ID);
			$this->InstallEvents();
		}else{
			$APPLICATION->ThrowException(Loc::getMessage("ICODES_SYNCRO_INSTALL_ERROR_VERSION"));
		}
	}

	function DoUninstall()
	{
		global $APPLICATION;
		$FORM_RIGHT = $APPLICATION->GetGroupRight($this->MODULE_ID);
		if ($FORM_RIGHT=="W") {
			$this->UnInstallEvents();
			$this->unInstallOptions();
			ModuleManager::unRegisterModule($this->MODULE_ID);
		}
	}
}
?>
