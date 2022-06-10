<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");


$queryData = [
  'function' => 'addTaskByEmail',
  'params' => [
    'email' => 1403
  ]
];

$queryData = http_build_query($queryData);

$queryUrl = "https://dstural24.ru/apps/managerPremium/api2.php";
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
print_r($result);

?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>