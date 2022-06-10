<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");







?>




<?$APPLICATION->IncludeComponent("bitrix:intranet.structure","",Array(
        "SHOW_FROM_ROOT" => "N",
        "MAX_DEPTH" => "2",
        "MAX_DEPTH_FIRST" => "0",
        "COLUMNS" => "2",
        "COLUMNS_FIRST" => "2",
        "SHOW_SECTION_INFO" => "Y",
        "USER_PROPERTY" => Array("EMAIL", "PERSONAL_ICQ", "PERSONAL_PHONE", "PERSONAL_MOBILE", "UF_PHONE_INNER"),
        "AJAX_MODE" => "Y",
        "SEARCH_URL" => "search.php",
        "PM_URL" => "/messages/form/#USER_ID#/",
        "PATH_TO_CONPANY_DEPARTMENT" => "/company/structure.php?set_filter_structure=Y&structure_UF_DEPARTMENT=#ID#",
        "FILTER_1C_USERS" => "Y",
        "FILTER_NAME" => "users",
        "USERS_PER_PAGE" => "10",
        "FILTER_SECTION_CURONLY" => "Y",
        "NAME_TEMPLATE" => "#NOBR##LAST_NAME# #NAME##/NOBR#",
        "SHOW_LOGIN" => "Y",
        "SHOW_ERROR_ON_NULL" => "Y",
        "NAV_TITLE" => "Сотрудники",
        "SHOW_NAV_TOP" => "Y",
        "SHOW_NAV_BOTTOM" => "Y",
        "SHOW_UNFILTERED_LIST" => "N",
        "DATE_FORMAT" => "d-m-Y",
        "DATE_FORMAT_NO_YEAR" => "d.m",
        "DATE_TIME_FORMAT" => "d.m.Y H:i:s",
        "SHOW_YEAR" => "Y",		
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N"
    ),
);?>

<?

$st = CIntranetUtils::GetDeparmentsTree($section_id = 0, $bFlat = false);
$names = 	CIntranetUtils::getSubDepartments($departmentId = 1);

echo "<pre>";
print_r($st);
//print_r($names);
echo "</pre>";

?>














<? //require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
