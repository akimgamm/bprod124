<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
\Bitrix\Main\UI\Extension::load("ui.buttons"); 
use Bitrix\Main\Page\Asset;
$APPLICATION->SetTitle("Канбан для проектов");
 
print_r($arParams);
print_r($arResult);
?>
<!DOCTYPE html>
<html lang="en">

<head>



  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://dstural124.ru/local/components/dstapps/ownkanban/css/style.css">
  <title>Document</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

  
  <? $k = CUser::GetID();
echo "<pre>";
//print_r($result);
//print_r($k);
//global $USER;
//echo "[".$USER->GetID()."] (".$USER->GetLogin().") ".$USER->GetFullName()

echo "</pre>";


echo "<script> var userIDauth=".$k."</script>";
echo "<script>// let d = document.getElementsByClassName('bx-layout-inner-left')[0]; console.log(d);console.log(d);console.log(d);console.log(d);console.log(d);console.log(d); d.style.display =" . "'none'</script>";


?>


 

  <script src="https://dstural124.ru/local/components/dstapps/ownkanban/js/jq.js"></script>
  <script src="https://dstural124.ru/local/components/dstapps/ownkanban/js/jq-ui.js"></script>



  <!-- старые пути -->
  <!-- <script src="https://dstinfo.ru/breaks/bitrixApps/Kanban/js/main.js"></script>
  <script src="https://dstinfo.ru/breaks/bitrixApps/Kanban/js/init.js"></script>
  <script src="https://dstinfo.ru/breaks/bitrixApps/Kanban/js/sortable.js"></script> -->





<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
<title>Тестовая задача 2</title>
</head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Roboto:wght@300;400&display=swap');
</style>






<body>
 

  <div id="main"></div>

  <script src="https://dstural124.ru/local/components/dstapps/ownkanban/js/main.js"></script>
  <script src="https://dstural124.ru/local/components/dstapps/ownkanban/js/init.js"></script>
  <script src="https://dstural124.ru/local/components/dstapps/ownkanban/js/sortable.js"></script>
 
  





</body>

</html>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>