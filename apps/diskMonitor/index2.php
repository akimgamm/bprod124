<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");

use \Bitrix\Main;

try
{
   $connection = Main\Application::getInstance()->getConnection();

   $queryResult = $connection->query("SELECT * FROM b_disk_object WHERE `SIZE`>'209715200' ORDER BY SIZE DESC");
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

<table class="table">
<thead>
	<th>Название файла</th>
	<th>Размер</th>

	<th>Кому принадлежит</th>
	<th>Удалить?</th>
</thead>
<tbody>

<?
while ( $data = $queryResult->fetch() )
{
?>


<?


$uHref = "https://dstural24.ru/company/personal/user/".$data['CREATED_BY']."/";

echo "<tr>";
		//print_r($res_arr);
		//echo "<hr>";

		//echo "<br>";
echo "<td>";
		echo $data['NAME'];
echo "</td>";

echo "<td>";
	echo round($data['SIZE']/1024/1024) . " МБ";
echo "</td>";

echo "<td><a href='$uHref'>Ссылка на владельца</a></td>";
echo "<td><a onclick='deleteElement({$data['ID']})'>Удалить</a></td>";
	echo "</tr>";
}
}
catch( Main\DB\SqlException $e )
{
   var_dump($e->getMessage());


};














