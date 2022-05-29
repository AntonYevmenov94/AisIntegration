<?php
namespace ICodes\AISIntegration\Helpers;

use Bitrix\Main\Localization\Loc;


Loc::loadMessages(__FILE__);

/**
 * Inserting in CRM interface
 */

class FrontInterface
{
    public static function addTabInsurance($entityID, $entityTypeID, $guid, $tabs)
    {

        \Bitrix\Main\Loader::includeModule ('sale');
        $dealEntityID = \Bitrix\Sale\Exchange\Integration\CRM\EntityType::DEAL;

        $assetManager = \Bitrix\Main\Page\Asset::getInstance();
        // Подключаем js файл
        $assetManager->addJs('/bitrix/tools/icodes_aisintegration/js/insurance.js');


        if($entityTypeID === $dealEntityID) {
            $tabs[] = [
                'id' => 'tab_insurance',  // ID вкладки
                'name' => Loc::getMessage('ICODES_AISINTEGR_FRONT_TAB_TITLE'), // Наименование вкладки
                'html' => '<div style="color: green">'.Loc::getMessage('ICODES_AISINTEGR_FRONT_TAB_LOADING').'</div>',
                'loader' => [
                    'serviceUrl' => '/bitrix/tools/icodes_aisintegration/ajax/tab_content.php', // Адрес на который будет делаться запрос при первом показе вкладки
                    // Параметры которые будут отправлены в ajax запросе, параметры передаются в массиве PARAMS
                    'componentData' => [
                        'id' => $entityID,
                        'btn-text' => Loc::getMessage('ICODES_AISINTEGR_FRONT_TAB_BTN'),
                    ],
                ]
            ];
        }
        return new \Bitrix\Main\EventResult(\Bitrix\Main\EventResult::SUCCESS, [
            'tabs' => $tabs,
        ]);

    }

}