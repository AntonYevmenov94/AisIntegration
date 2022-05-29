<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application,
	Bitrix\Main\IO\Directory,
	Bitrix\Main\Config\Option;

IncludeModuleLangFile(__FILE__);

Class icodes_aisintegration extends CModule
{
	const NAMESPACE = 'ICodes\AISIntegration';
	var $MODULE_ID  = 'icodes.aisintegration';
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
		$this->MODULE_NAME = Loc::getMessage("ICODES_AISINTEGR_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("ICODES_AISINTEGR_MODULE_DESC");
		$this->PARTNER_NAME = Loc::getMessage("ICODES_AISINTEGR_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("ICODES_AISINTEGR_PARTNER_URI");
	}


	public function installEvents()
	{
		EventManager::getInstance()->registerEventHandlerCompatible(
			'crm',
			'onEntityDetailsTabsInitialized',
			$this->MODULE_ID,
			'ICodes\AISIntegration\Helpers\FrontInterface',
			'addTabInsurance'
		);
	}

	public function unInstallEvents()
	{
		EventManager::getInstance()->unRegisterEventHandler(
			'crm',
			'onEntityDetailsTabsInitialized',
			$this->MODULE_ID,
			'ICodes\AISIntegration\Helpers\FrontInterface',
			'addTabInsurance'
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
			$APPLICATION->ThrowException(Loc::getMessage("ICODES_AISINTEGR_INSTALL_ERROR_VERSION"));
		}
	}

	function DoUninstall()
	{
		global $APPLICATION;
		$FORM_RIGHT = $APPLICATION->GetGroupRight($this->MODULE_ID);
		if ($FORM_RIGHT=="W") {
			$this->UnInstallEvents();
			//$this->unInstallOptions();
			ModuleManager::unRegisterModule($this->MODULE_ID);
		}
	}
}
?>
