<?php
namespace ICodes\ChatWordHints;

use Bitrix\Main\Config\Option;


class Config
{
    const MODULE_ID = 'icodes.chatwordhints';


    public function getHintsIblockID()
    {
        $hintsIblockID = Option::get(self::MODULE_ID, 'iblock_element_hint');
        return $hintsIblockID;
    }

}