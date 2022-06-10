<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$userID = CUser::GetID();

function curl_load($url){
    curl_setopt($ch=curl_init(), CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

$url = "http://192.168.210.12:8088/bitrix?r=500";
$res = curl_load($url);
echo $res;

echo $userID;
echo $userID;
echo $userID;
//$user = new User;

//echo $user->userID;

















?>