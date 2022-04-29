<?php
namespace ICodes\AISIntegration\Helpers;

use \Bitrix\Main\Loader,
    \Bitrix\Tasks\Util,
    \ICodes\InsuranceExchange\Api;
Loader::includeModule('tasks');

/** Session handler
 * Class Session
 * @package IICodes\InsuranceExchange\Helpers\Session
 * @copyright Intellect Codes
 */
class Session
{

    public function getSession()
    {
        $MessageID  =  Util::generateUUID(false);
        $Identifier =  Util::generateUUID(false);
        $apiSession = new Api\Session();
        $SequenceIdentifier = $apiSession->CreateSequence($MessageID, $Identifier);
        echo '<pre>'.print_r($SequenceIdentifier, true).'</pre>';
        $sessionIdentifier = $apiSession->OpenSession($MessageID, $SequenceIdentifier);
        $sessionIdentifier['bPasswordChallengeMD5'] = md5('12345'.$sessionIdentifier['bPasswordChallenge']);

        return $sessionIdentifier;
    }
}