<?
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog.php");

//require_once(dirname(__FILE__)."/prolog_before.php");
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");

$APPLICATION->SetTitle("Проверка дисков");

$rsUser = CUser::GetByID(830);
$arUser = $rsUser->Fetch();
echo "<pre>"; print_r($arUser); echo "</pre>";

?>
<style>
.table {
	width: 100%;
	border: none;
	margin-bottom: 20px;

}
.table thead th {
	font-weight: bold;
	text-align: left;
	border: none;
	padding: 10px 15px;
	background: #d8d8d8;
	font-size: 14px;
	border-left: 1px solid #ddd;
	border-right: 1px solid #ddd;
}
.table tbody td {
	text-align: left;
	border-left: 1px solid #ddd;
	border-right: 1px solid #ddd;
	padding: 10px 15px;
	font-size: 14px;
	vertical-align: top;
}
.table thead tr th:first-child, .table tbody tr td:first-child {
	border-left: none;
}
.table thead tr th:last-child, .table tbody tr td:last-child {
	border-right: none;
}
.table tbody tr:nth-child(even){
	background: #f3f3f3;
}
</style>
hhhhhh
<?

function getRealFileFromDiskById($diskId) {
	    \Bitrix\Main\Loader::includeModule('disk');
	    $resObjects = \Bitrix\Disk\Internals\ObjectTable::getList([
			//'select' => ['NAME', 'FILE_ID'],
	            'filter' => [
					'=FILE_ID' => $diskId,
	            ]
	    ]);
	    if ($arObject = $resObjects->fetch()) {
		        $arObject['PATH'] = CFile::GetPath($arObject['FILE_ID']);
		        $arObject['FULL_PATH'] = $_SERVER['DOCUMENT_ROOT'].$arObject['PATH'];
		        return $arObject;
		}
	    return false;
	}

$o = getRealFileFromDiskById(4814);

echo "<pre>";

print_r($o);

echo "</pre>";


$g = new CFile;

$k = $g->GetByID(57);
$allModuleRows = $g->GetList(null,array("MODULE_ID"=>"disk"));
//$k = $g->GetList(null,null);

$res = [];
$summSize = 0;
?>


<table class="table">
<thead>
	<th>Название файла</th>
	<th>Размер</th>
	<th>Дата добавления</th>
	<th>Кому принадлежит</th>
	<th>Удалить?</th>
</thead>
<tbody>
<?
//print_r($k);
while($res_arr = $allModuleRows->Fetch()) {
?>

<?
	if($res_arr['FILE_SIZE']>209715200) {
		$summSize+=$res_arr['FILE_SIZE'];

$o = getRealFileFromDiskById($res_arr['ID']);
		$uHref = "https://dstural24.ru/company/personal/user/".$o['CREATED_BY']."/";
echo "<pre>";
		//print_r($z*0.0000000001);
		//print_r($res_arr);
		//print_r($o);

echo "</pre>";

echo "<tr>";
		//print_r($res_arr);
		//echo "<hr>";

		//echo "<br>";
echo "<td>";
		echo $res_arr['ORIGINAL_NAME'];
echo "</td>";

echo "<td>";
		echo round($res_arr['FILE_SIZE']/1024/1024) . " МБ";
echo "</td>";
echo "<td>";
		echo $res_arr['TIMESTAMP_X'];
echo "</td>";
		echo "<td><a href='$uHref'>Ссылка на владельца</a></td>";
		echo "<td><a onclick='deleteElement({$o['ID']})'>Удалить</a></td>";


		//echo "<br>";
echo "</tr>";
	}

	//print_r($o);

}
echo "<pre>";

//print_r($o);

echo "</pre>";

	//echo $res_arr["SUBDIR"]."/".$res_arr["FILE_NAME"]." = ".$res_arr["FILE_SIZE"]."<br>";




?>
</tbody>
</table>







<!-- Общий размер -->

<table>
<thead>
	<th>
	Общий размер
	</th>
</thead>
<tbody>
<tr>
	<td><? echo round($summSize/1024/1024/1024) . " ГБ"; ?></td>
</tr>
</tbody>
</table>


<script>

	function deleteElement(id) {
	confirm("Удалить выбранный файл?");
alert(id);
}

BX.rest.callMethod(
        "disk.file.get",
        {
            id: 4552
        },
        function (result)
        {
            if (result.error())
                console.error(result.error());
            else
                console.dir(result.data());
        }
);





</script>



























hhhhhh
<?


$o = getRealFileFromDiskById(4814);


?>



<?



$g = new CFile;

//$res = CFile::GetList(array("FILE_SIZE"=>"desc"), array("MODULE_ID"=>"disk"));
$res = CFile::GetList(array("FILE_SIZE"=>"desc"), array("MODULE_ID"=>"main"));
$ress = $g->GetList(null,array("MODULE_ID"=>"main")) 
//while($res_arr = $res->GetNext()) echo $res_arr["SUBDIR"]."/".$res_arr["FILE_NAME"]." = ".$res_arr["FILE_SIZE"]."<br>";
?>


































