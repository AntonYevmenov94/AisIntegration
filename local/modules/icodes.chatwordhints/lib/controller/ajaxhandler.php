<?
namespace ICodes\ChatWordHints\Controller;

use Bitrix\Main\Engine\Controller,
    Bitrix\Main\Engine\ActionFilter,
    Bitrix\Main\Loader,
    ICodes\ChatWordHints\Config;

class AjaxHandler extends Controller
{
    public function configureActions()
    {
        return [
            'getWords' => [
                'prefilters' => [
				    new ActionFilter\Authentication(),
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
				]
            ]
        ];
    }
	
	public static function getWordsAction($word)
	{

		if(Loader::includeModule("iblock"))
		{

			$iblockID = Config::getHintsIblockID();
			$res = \CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>$iblockID,"=NAME"=>$word), false, false, Array("PROPERTY_DEFINITION"));
			if(!$iblockID) {
				return "Info block with hints not selected!";
			} elseif($ob = $res->GetNextElement()){
				$arData = $ob->GetFields();
				return $arData["PROPERTY_DEFINITION_VALUE"]["TEXT"];
			}
			else{
				return "Not Found!";
			}
		}
	}
}

