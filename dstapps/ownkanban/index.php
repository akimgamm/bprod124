<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");



$APPLICATION->IncludeComponent(
    "dstapps:ownkanban",
    "",
    Array(
      "X"=>"123",
    )
);

// $APPLICATION->IncludeComponent(
//   "dstapps:afisha2",
//   "",
//   Array(
//     "X"=>"123",
//   )
// );





?>