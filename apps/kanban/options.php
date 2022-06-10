<? 
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");



if (CModule::IncludeModule("tasks"))
{
    $res = CTasks::GetList(
        Array("TITLE" => "ASC"), 
        Array("RESPONSIBLE_ID" => "830")
    );


	$items = [];
    while ($arTask = $res->GetNext())
    {
		// echo "Task name: ".$arTask["TITLE"]."<br>";
$items[] = $arTask;
    }
}

header("Content-type: application/json; charset=utf-8");
echo json_encode($items);

?>