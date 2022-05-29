<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

use \Bitrix\Main\Context,
    \Bitrix\Main\UI\Extension;

Extension::load("ui.buttons");

$request = Context::getCurrent()->getRequest();
$params = $request->get("PARAMS");

?>
<!-- .ui-btn.ui-btn-success-->
<button class="ui-btn ui-btn-success" onclick="AisIntegrationExport(<?=$params['id']?>);"><?=$params['btn-text']?></button>
