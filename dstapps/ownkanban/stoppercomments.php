<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->IncludeComponent(
  "dstapps:stoppercomments",
  "",
  Array(
    "X"=>"123",
  )
);