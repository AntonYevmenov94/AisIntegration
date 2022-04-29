<?
namespace ICodes\AISIntegration\Api;

use ICodes\AISIntegration\Helpers\Client;

/** OpenSession api methods handler
 * Class OpenSession
 * @package IICodes\InsuranceExchange\Api
 * @copyright Intellect Codes
 */
class Session
{
    private $headers;
    private $httpMethod;

    function __construct() {
        $this->httpMethod = 'POST';
        $this->headers = array(
            'Content-Type' => 'application/soap+xml',
            'action' => 'http://byte-protect.com/isiais/IAISIntegrationService/OpenSession'
        );
    }
    /**
     * @param $MessageID
     * @param $Identifier
     * @return string
     */
    public function CreateSequence($MessageID, $Identifier)
    {
        $query ='<?xml version="1.0" encoding="UTF-8"?>
            <soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope">
                <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">
                    <wsa:Action>http://docs.oasis-open.org/ws-rx/wsrm/200702/CreateSequence</wsa:Action>
                    <wsa:ReplyTo>
                        <wsa:Address>http://www.w3.org/2005/08/addressing/anonymous</wsa:Address>
                    </wsa:ReplyTo>
                    <wsa:MessageID>uuid:'.$MessageID.'</wsa:MessageID>
                    <wsa:To>http://minsk.byte-protect.com/VPSService/AISIntegrationService.svc</wsa:To>
                </soap:Header>
                <soap:Body xmlns:wsrm="http://docs.oasis-open.org/ws-rx/wsrm/200702">
                    <wsrm:CreateSequence>
                        <wsrm:AcksTo xmlns:wsa="http://www.w3.org/2005/08/addressing">
                            <wsa:Address>http://www.w3.org/2005/08/addressing/anonymous</wsa:Address>
                        </wsrm:AcksTo>
                        <wsrm:Offer>
                            <wsrm:Identifier>urn:soapui:'.$Identifier.'</wsrm:Identifier>
                            <wsrm:Endpoint>
                                <add:Address xmlns:add="http://www.w3.org/2005/08/addressing">http://www.w3.org/2005/08/addressing/anonymous</add:Address>
                            </wsrm:Endpoint>
                        </wsrm:Offer>
                    </wsrm:CreateSequence>
                </soap:Body>
            </soap:Envelope>';
        $headers = array(
            'Content-Type' => 'application/soap+xml',
            'action' => 'http://byte-protect.com/isiais/IAISIntegrationService/OpenSession'
        );
        $client = new Client($this->headers);
        $objXML = $client->request($this->httpMethod, $query);
        $body = $objXML->xpath('//sBody');
        $SequenceIdentifier = (array) $body[0]->CreateSequenceResponse->Identifier[0];
        return array_shift($SequenceIdentifier);
    }



    public function OpenSession($messageID, $sequenceIdentifier)
    {
        $query = '<?xml version="1.0" encoding="UTF-8"?>
            <soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:isi="http://byte-protect.com/isiais">
                <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing" xmlns:wsrm="http://docs.oasis-open.org/ws-rx/wsrm/200702">
                    <wsrm:Sequence>
                        <wsrm:Identifier>'.$sequenceIdentifier.'</wsrm:Identifier>
                        <wsrm:MessageNumber>1</wsrm:MessageNumber>
                    </wsrm:Sequence>
                    <wsa:Action>http://byte-protect.com/isiais/IAISIntegrationService/OpenSession</wsa:Action>
                    <wsa:MessageID>'.$messageID.'</wsa:MessageID>
                    <wsa:To>http://minsk.byte-protect.com/VPSService/AISIntegrationService.svc</wsa:To>
                </soap:Header>
                <soap:Body>
                    <isi:OpenSession/>
                </soap:Body>
            </soap:Envelope>';

        $client = new Client($this->headers);
        $objXML = $client->request($this->httpMethod, $query);
        $body = $objXML->xpath('//sBody')[0];
        $openSessionResult = (array) $body[0]->OpenSessionResponse->OpenSessionResult;
        return $openSessionResult;
    }
}
?>