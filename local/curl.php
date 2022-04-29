<?php
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
$curl = curl_init();
//return "gfgf";
//$data = http_build_query($postParams);
curl_setopt($curl, CURLOPT_URL, "http://localhost:54332/api/products");
curl_setopt($curl, CURLOPT_POST, true);
//curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_exec($curl);
curl_close($curl);
//return $data;
?>