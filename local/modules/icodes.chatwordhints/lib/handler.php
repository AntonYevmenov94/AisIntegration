<?
namespace ICodes\ChatWordHints;
use Bitrix\Main\Page\Asset,
	Bitrix\Main\Loader;
class Handler
{
	
	public function init()
	{
        global $USER;
        if (\CModule::IncludeModule('pull') && $USER->IsAuthorized()) {
			$userId = $USER->GetId();
			$subscribers = \CPullWatch::GetUserList('WORD_HL_CHAT');
			if(!isset($subscribers[$userId])) {
				\CPullWatch::Add($USER->GetId(), 'WORD_HL_CHAT');
			}
			Asset::getInstance()->addJs("/bitrix/js/icodes.chatwordhints/script.js");
			Asset::getInstance()->addCss("/bitrix/css/icodes.chatwordhints/style.css");
			\CJSCore::Init("jquery");
        }		
	}

	
	public static function messageRebuilder ($message){
		if(!Loader::includeModule("iblock")) return $message;


		$newmessage ="";

		$message = preg_replace("/([!?.,:';\"])/", " $1 ", $message);
		$message = preg_replace("/\B-+(?=[A-Za-z0-9])/", " - ", $message);
		$message = preg_replace("/\b-+(?![A-Za-z0-9])/", " - ", $message);


		$info = explode(' ',$message);
		$infoClean = $info;
		$result =Array();
		foreach ($infoClean as $key=>$word)
		{
			if(strlen($word) < 2 )
			{
				array_push($result,$info[$key]);
				continue;
			}
			$iblockID = Config::getHintsIblockID();
			$res = \CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=> $iblockID,"NAME"=>"%".$word."%"), false, false, Array("NAME"));
			$variants = [];
			while($ob = $res->GetNextElement()){
				$arData = $ob->GetFields();
				if(stristr(mb_strtolower($message,"UTF-8"), mb_strtolower($arData["NAME"],"UTF-8")))
					array_push($variants,$arData["NAME"]);
			}
			if(count($variants)==0)
				array_push($result,$info[$key]);
			array_multisort(array_map('strlen', $variants),SORT_DESC, $variants);
			foreach($variants as $variant)
			{
				$massive = explode(" ",$variant);
				$counter = 0;
				foreach($massive as $item)
				{
					if (mb_strtolower($item,"UTF-8")==mb_strtolower($infoClean[$key + $counter],"UTF-8"))
						$counter = $counter+1;
					else
						break;
				}
				if($counter == count($massive)){
					array_push($result,'[SPOILER=Заголовок]Текст[/SPOILER]');
					break;
				}
				else
					continue;
			}
		}
		$result = implode(" ", $result);
		$result = preg_replace("/\s([!?.,:';\"])\s/", "$1", $result);
		return $result;
	}
	
	
	function wordHighlighter(&$arFields){
$f = fopen ($_SERVER['DOCUMENT_ROOT']."/arres.log", "a");
fwrite ($f, mydump('start'));
fwrite ($f, mydump($arFields));
fclose($f);
		$chatId = $arFields["TO_CHAT_ID"];
		if(!$chatId)
			$chatId =$arFields['TO_USER_ID'];
		$newMessage = self::messageRebuilder($arFields["MESSAGE"] );
		if(isset($arFields["MESSAGE"]) && $arFields["MESSAGE"] !== $newMessage) {
			$arFields["MESSAGE"] = $newMessage;
			global $USER;
			$userId = $USER->GetID();
			if (\CModule::IncludeModule("pull")) {
				$a = \CPullWatch::AddToStack('WORD_HL_CHAT',
					array(
						'module_id' => 'icodes.chatwordhints',
						'command' => 'hint',
						'params' => array(
							"TIME" => time(),
							"CHAT" => $chatId,
							"USER_ID" => $userId
						)
					)
				);
			}
		}

		return $arFields;
	}
}