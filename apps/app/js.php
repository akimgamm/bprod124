<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");

   CJSCore::RegisterExt("db_js_demo", Array(
	   "js" =>    "/local/apps/app/db_js_demo.js",
	   //"lang" =>   "/lang_js.php",
	   //"rel" =>   array('jquery')
   ));
   CJSCore::Init(array("db_js_demo"))



	   // $asset = Asset::getInstance();
//$asset->AddString('<script>BX.ready(function(){ console.log("all loaded");});</script>');

?>




<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>