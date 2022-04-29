<?php
namespace Icodes\TaskSyncro\Controller;

use Bitrix\Main\Engine\Controller,
    \ICodes\TaskSyncro\Api\User;

class Ajax extends Controller
{
    /**
     * @return array
     */
    public function configureActions()
    {
        return [
            'handler' => [
                'prefilters' => []
            ]
        ];
    }


    public static function handlerAction()
    {
        $user = new User();
        return  $user->UserSyncro();;
    }
}