<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

function getSectionList($filter, $select)
{
   $dbSection = CIBlockSection::GetList(
      Array(
               'LEFT_MARGIN' => 'ASC',
      ),
      array_merge( 
          Array(
             'ACTIVE' => 'Y',
             'GLOBAL_ACTIVE' => 'Y'
          ),
          is_array($filter) ? $filter : Array()
      ),
      false,
      array_merge(
          Array(
             'ID',
             'IBLOCK_SECTION_ID'
          ),
         is_array($select) ? $select : Array()
      )
   );

   while( $arSection = $dbSection-> GetNext(true, false) ){

       $SID = $arSection['ID'];
       $PSID = (int) $arSection['IBLOCK_SECTION_ID'];

       $arLincs[$PSID]['CHILDS'][$SID] = $arSection;

       $arLincs[$SID] = &$arLincs[$PSID]['CHILDS'][$SID];
   }

   return array_shift($arLincs);
}


$arSections = getSectionList(
 Array(
    'IBLOCK_ID' => 1
 ),
 Array(
    'NAME',
    'SECTION_PAGE_URL'
 )
);

echo "<pre>";
//var_dump($arSections);
echo json_encode($arSections);
echo "</pre>";  









//print_r($_GET["PID"]);
echo '<pre>';
//print_r($res);
echo '</pre>';
?>
