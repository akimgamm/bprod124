<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<br />

<?
$today=ConvertTimeStamp(time());

//Фильтр по-умолчанию

if (!$_GET['set_filter']){
    $_GET['set_filter'] ='today';
    $_GET['arrFilter_DATE_ACTIVE_FROM_2'] =$today;
    $_GET['arrFilter_DATE_ACTIVE_TO_1'] =$today;
	
	$_REQUEST['set_filter'] ='today';
    $_REQUEST['arrFilter_DATE_ACTIVE_FROM_2'] =$today;
    $_REQUEST['arrFilter_DATE_ACTIVE_TO_1'] =$today;
	}
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.filter",
	"vkladki",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"FILTER_NAME" => 'arrFilter',
		"FIELD_CODE" => array(
				0 => "DATE_ACTIVE_FROM",
				1 => "DATE_ACTIVE_TO",
			),
		"PROPERTY_CODE" => array(),
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => "Y",
	),
	$component
);
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"",
	Array(
		"IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
		"IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
		"NEWS_COUNT"	=>	100,
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"FIELD_CODE"	=>	array(
			0 => "NAME",
			1 => "DETAIL_PICTURE",
			2 => "DATE_ACTIVE_FROM",
			3 => "DATE_ACTIVE_TO",
			4 => "",
		),
		"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["film"],
		"IBLOCK_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["aphisha"],
		"SET_TITLE"	=>	"Y",
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN"	=>	$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
		"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
		"CACHE_FILTER"	=>	"Y",
		"CACHE_GROUPS" => "Y",
		"DISPLAY_TOP_PAGER"	=>	"N",
		"DISPLAY_BOTTOM_PAGER"	=>	"N",
		"DISPLAY_DATE"	=>	"N",
		"DISPLAY_NAME"	=>	"Y",
		"DISPLAY_PICTURE"	=>	"Y",
		"DISPLAY_PREVIEW_TEXT"	=>	"N",
		"ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
		"USE_PERMISSIONS"	=>	"N",
		"GROUP_PERMISSIONS"	=>	"N",
		"HIDE_LINK_WHEN_NO_DETAIL"	=>"N",
		"CHECK_DATES"	=>	"N",
		"FILTER_NAME" => 'arrFilter',
	),
$component
);?>
<br />
<?
//Проверим, имеет ли пользователь право добавлять фильм. Если имеет - покажем ему ссылку на добавление фильма
global $USER;
$arGroups = $USER->GetUserGroupArray();
$arIntersectGroup=array_intersect($arParams["ADD_GROUP_PERMISSIONS"],$arGroups);
if (count($arIntersectGroup)>0){
?>
<br/><br/>
<div>
	<a href="<?=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["add_film"]?>"  class="btn btn-warning"><?=GetMessage("ADD_FILM")?></a>
</div>
<?}?>