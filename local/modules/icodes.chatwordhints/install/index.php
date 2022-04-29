<?
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ModuleManager,
	Bitrix\Main\EventManager,
	Bitrix\Main\IO\Directory,
	Bitrix\Main\Config\Option;

IncludeModuleLangFile(__FILE__);

Class icodes_chatwordhints extends CModule
{
	const NAMESPACE = 'ICodes\ChatWordHints';
	var $MODULE_ID  = 'icodes.chatwordhints';
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
		$this->MODULE_NAME = Loc::getMessage("ICODES_CHATWH_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("ICODES_CHATWH_MODULE_DESC");
		$this->PARTNER_NAME = Loc::getMessage("ICODES_CHATWH_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("ICODES_CHATWH_PARTNER_URI");
	}


	public function installFiles()
	{

		$path = str_replace($_SERVER["DOCUMENT_ROOT"], "", __DIR__);
        CopyDirFiles(
			__DIR__."/js",
			$_SERVER["DOCUMENT_ROOT"]."/bitrix/js/icodes.chatwordhints",
			true,
			true
		);
		CopyDirFiles(
			__DIR__."/css",
			$_SERVER["DOCUMENT_ROOT"]."/bitrix/css/icodes.chatwordhints",
			true,
			true
		);
	}
	
	public function unInstallFiles()
	{
        if (is_dir($_SERVER["DOCUMENT_ROOT"]."/bitrix/js/icodes.chatwordhints")) {
            Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . "/bitrix/js/icodes.chatwordhints");
        }
        if (is_dir($_SERVER["DOCUMENT_ROOT"]."/bitrix/css/icodes.chatwordhints")) {
            Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . "/bitrix/css/icodes.chatwordhints");
        }
	}

	public function installEvents()
	{
		EventManager::getInstance()->registerEventHandlerCompatible(
			'main',
			'OnEpilog',
			$this->MODULE_ID,
			'ICodes\ChatWordHints\Handler',
			'init'
		);		
		EventManager::getInstance()->registerEventHandlerCompatible(
			'im',
			'OnBeforeMessageNotifyAdd',
			$this->MODULE_ID,
			'ICodes\ChatWordHints\Handler',
			'wordHighlighter'
		);
	}

	public function unInstallEvents()
	{
		EventManager::getInstance()->unRegisterEventHandler(
			'main',
			'OnBeforeMessageNotifyAdd',
			$this->MODULE_ID,
			'ICodes\ChatWordHints\Handler',
			'wordHighlighter'
		);		
		EventManager::getInstance()->unRegisterEventHandler(
			'im',
			'OnBeforeMessageNotifyAdd',
			$this->MODULE_ID,
			'ICodes\ChatWordHints\Handler',
			'wordHighlighter'
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
			$this->InstallFiles();
			$this->InstallEvents();
		}else{
			$APPLICATION->ThrowException(Loc::getMessage("ICODES_CHATWH_INSTALL_ERROR_VERSION"));
		}
	}

	function DoUninstall()
	{
		global $APPLICATION;
		$FORM_RIGHT = $APPLICATION->GetGroupRight($this->MODULE_ID);
		if ($FORM_RIGHT=="W") {
			$this->unInstallFiles();
			$this->UnInstallEvents();
			//$this->unInstallOptions();
			ModuleManager::unRegisterModule($this->MODULE_ID);
		}
	}
}
?>
