<?
namespace ICodes\AISIntegration\Api;

use ICodes\AISIntegration\Helpers\Client;

/** Класс для работы c api методом account
 * Class Balance
 * @package ItUa\Esputnikintegr\Api
 * @copyright ItUa
 */
class Auth
{

    private $httpClientV1;

    function __construct()
    {
        $apiVersion1 = '1';
        $apiMethod = 'account';
        $this->httpClientV1 = new Client($apiVersion1, $apiMethod);
    }

    /** Получение статуса авторизации
     * @return boolean
     */
    public function isAuthorized()
    {
        $httpMethod = 'GET';
        $apiParams  = 'info';
        $data = $this->httpClientV1->request($httpMethod, $apiParams);

        if($data['error'] == 'Unauthorized') {
            return false;
        } else {
            return true;
        }
    }
}
?>