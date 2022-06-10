12345
<? 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//require("https://dstinfo.ru/breaks/bitrixApps/Kanban/index2.php");
function bitrixHook($hookParams, $hook)
  {
    // $queryUrl = "https://akimgamm.bitrix24.ru/rest/1/dp2kh5h9icf317bj/". $method ;

    // $data = [
    //   "IBLOCK_TYPE_ID" => "lists",
    //   "IBLOCK_ID" => 72,
    //   "FILTER" => array(
    //     // "PROPERTY_249" => 2332
    //   )
    // ];


    $queryData = http_build_query($hookParams);

    $queryUrl = $hook;
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_SSL_VERIFYPEER => 0,
       CURLOPT_GET => 1,
		CURLOPT_HEADER => 0,
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => $queryUrl,
      CURLOPT_POSTFIELDS => $queryData,
    ));

    $result = curl_exec($curl);
    curl_close($curl);
	  //return json_decode($result, 1);
	  return $result;
  }


$inf = file_get_contents('https://ru.wikipedia.org/');
//$inf = bitrixHook([],"https://ru.wikipedia.org/wiki/%D0%92%D0%B8%D0%BA%D0%B8");
//echo json_encode($inf);

print_r($inf);
file_put_contents('file.php', $inf);


$homepage = file_get_contents('http://www.example.com/');
$homepage = 2222;
echo $homepage;

?>

<?

$APPLICATION->IncludeFile($APPLICATION->GetCurDir()."file.php", Array(), Array(
	//"MODE" => "php",                                          

    ));


?>







<? //require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");
//require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/footer.php"
?>