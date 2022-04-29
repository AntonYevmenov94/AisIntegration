<?php
namespace ICodes\AISIntegration\Helpers;

use \Bitrix\Main\Web\HttpClient,
    \SimpleXMLElement;

/** Https data exchange
 * Class Client
 * @package ICodes\InsuranceExchange\Helpers
 * @copyright Intellect Codes
 */

class Client
{
    private $httpClient;
    private $url;

    /**
     * Client constructor.
     * @param array $headers
     */
    function __construct($headers) {
        $this->url = 'http://minsk.byte-protect.com/VPSService/AISIntegrationService.svc?singleWsdl';
        $this->httpClient = new HttpClient();
        foreach ($headers as $header => $value) {
            $this->httpClient->setHeader($header, $value, true);
        }
   }

    /** Https request
     * @param string $httpMethod
     * @param string $query
     * @return string
     */

    public function request($httpMethod, $query)
    {
        $this->httpClient->query($httpMethod, $this->url, $query, false);
        $httpResult = $this->httpClient->getResult();
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $httpResult);
        $objXML = new SimpleXMLElement($response);
        return $objXML;
    }

}