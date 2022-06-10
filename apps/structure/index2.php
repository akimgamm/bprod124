<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?>





<?

class Hook {
  public function bitrixHook($hookParams, $hook)
  {
    // $queryUrl = "https://akimgamm.bitrix24.ru/rest/1/dp2kh5h9icf317bj/". $method ;

    // $data = [
    //   "IBLOCK_TYPE_ID" => "lists",
    //   "IBLOCK_ID" => 72,
    //   "FILTER" => array(
    //     // "PROPERTY_249" => 2332
    //   )
    // ];


    $queryData = http_build_query($hookParams);

    $queryUrl = $hook;
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_SSL_VERIFYPEER => 0,
      CURLOPT_POST => 1,
      CURLOPT_HEADER => 0,
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => $queryUrl,
      CURLOPT_POSTFIELDS => $queryData,
    ));

    $result = curl_exec($curl);
    curl_close($curl);
    return json_decode($result, 1);
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  

  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

  <script>

function application() { };
app = new application();

application.prototype.bitrixHook = async function (params, hook) {
  var str = jQuery.param(params);
  console.log(str);

 
 let response = await fetch(hook+"?"+str)
 response = await response.json();

  return response.result;
}

application.prototype.run = async function () {

  // BX24.callMethod(
  //   'tasks.task.list',
  //   { filter: { GROUP_ID: '209' },  },
  //   function (res) { console.log(res.answer.result); }
  // );

  let params = {
    filter: { "GROUP_ID": [209] }, //Массив групп
  }

  arr = await app.bitrixHook(params, "https://dstural24.ru/rest/830/l7bann8u7zjtvy8v/tasks.task.list.json");
  
  console.log(arr.tasks[0].responsible.id);

  params = {"id": arr.tasks[0].responsible.id };

  let dep = await app.bitrixHook(params, "https://dstural24.ru/rest/830/l7bann8u7zjtvy8v/user.get.json")



  params = {"ID": dep[0].UF_DEPARTMENT[0]}


  let depName = await app.bitrixHook(params, "https://dstural24.ru/rest/830/l7bann8u7zjtvy8v/department.get.json")

  console.log(depName[0].NAME, arr);







}

app.run();

</script>
</body>
</html>




















<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>