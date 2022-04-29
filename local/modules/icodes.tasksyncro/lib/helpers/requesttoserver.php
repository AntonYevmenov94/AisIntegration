<?
namespace Icodes\TaskSyncro\Helpers;

use \Bitrix\Main\Web\HttpClient;


class RequestToServer{

    public function send($url,$data){

        $queryUrl  = $url;
        $queryData = http_build_query($data);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $queryUrl,
            CURLOPT_POSTFIELDS => $queryData,
        ));

        $result = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($result, 1);

        return $response;
    }


}