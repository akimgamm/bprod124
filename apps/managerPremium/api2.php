<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");



ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// define('CONF', true);
// require 'conf.php';


// header("Access-Control-Allow-Origin: https://dstural24.ru");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



$DB_DSN = "mysql:host=localhost; dbname=dstinfonew; charset=utf8;";
$DB_DSN2 = "mysql:host=localhost; dbname=error_db; charset=utf8;";

$DB_USERNAME = "user";
$DB_PASSWORD = "YjnhLfv2021";

$conn = new PDO($DB_DSN, $DB_USERNAME, $DB_PASSWORD);
$conn2 = new PDO($DB_DSN2, $DB_USERNAME, $DB_PASSWORD);

$db = ["dstinfonew" => $conn, "error_db" => $conn2];

class BitrixAppsApi
{
  public function bitrixHook($data,$hook)
  {
    // $queryUrl = "https://akimgamm.bitrix24.ru/rest/1/dp2kh5h9icf317bj/". $method ;

    // $data = [
    //   "IBLOCK_TYPE_ID" => "lists",
    //   "IBLOCK_ID" => 72,
    //   "FILTER" => array(
    //     // "PROPERTY_249" => 2332
    //   )
    // ];


    $queryData = http_build_query($data);

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

  public function callB24Method(array $auth, $method, $params)
  {
    $c = curl_init('https://' . $auth['domain'] . '/rest/' . $method . '.json');
    $params["auth"] = $auth["access_token"];
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($params));
    $response = curl_exec($c);
    $response = json_decode($response, true);
    return $response['result']['TITLE'];
    // return $response;
  }

  public function getParsedMashineFaults($query, $db)
  {
    $result = [];
    foreach ($query as $value) {
      $code = strval($value['code']);

      $codeArr = explode('.', $code);


      // print_r($value['user_add']);
      // echo "<pre>";
      //   print_r($value);  
      // echo "</pre>";


      $sql = "SELECT category FROM dstinfonew.faults_deal_list WHERE deal = '{$value['deal']}'";
      $st = $db['dstinfonew']->prepare($sql);
      $st->execute();
      $query = $st->fetchAll(PDO::FETCH_ASSOC);


      // $result['category'] = $query[0]['category'];
      $dealCategoryId = $query[0]['category'];

      $sql = "SELECT `title` FROM dstinfonew.faults_deal_category WHERE dstinfonew.faults_deal_category.id = " . $query[0]['category'];
      $st = $db['dstinfonew']->prepare($sql);
      $st->execute();
      $query = $st->fetchAll(PDO::FETCH_ASSOC);

      $dealCategory = $query['0']['title'];


      $sql = "SELECT child.title as childTitle, parent.title as parentTitle
                    FROM dstinfonew.faults_type_list AS parent
                    LEFT JOIN dstinfonew.faults_type_list AS child ON child.parent = parent.id
                    WHERE parent.code = " . $codeArr[0] . " AND child.code = " . $codeArr[1];

      $st = $db['dstinfonew']->prepare($sql);
      $st->execute();
      $titles = $st->fetchAll(PDO::FETCH_ASSOC);

      $sql = "SELECT * FROM dstinfonew.faults_culprit WHERE id = '" . $value['culprit'] . "'";
      $st = $db['dstinfonew']->prepare($sql);
      $st->execute();
      $culprit = $st->fetchAll(PDO::FETCH_ASSOC);


      // print_r($culprit);

      $sql = "SELECT `model` FROM `machines` WHERE `nomer` = " . $value['nomer'];
      $st = $db['dstinfonew']->prepare($sql);
      $st->execute();
      $model = $st->fetchAll(PDO::FETCH_ASSOC);



      $result[] = [
        'deal' => $value['deal'],
        'nomer' => $value['nomer'],
        'model' => (!empty($model)) ? $model['0']['model'] : "-",
        'machine_status' => $value['machine_status'],
        'work_time' => $value['work_time'],
        'code' => $code,
        'fault' => isset($titles[0]['childTitle']) ? $titles[0]['childTitle'] : '-',
        'fault_category' => isset($titles[0]['parentTitle']) ? $titles[0]['parentTitle'] : '-',
        'culprit' => isset($culprit[0]['title']) ? $culprit[0]['title'] : "-",
        'comments' => isset($value['comments']) ? $value['comments'] : "-",
        'date' => date('d.m.Y', strtotime($value['date'])),
        'deal_category' => isset($dealCategory) ? $dealCategory : "-",
        'deal_category_id' => isset($dealCategoryId) ? $dealCategoryId : "-"
      ];
    }

    return $result;
  }




  public function parseBitrixFaults($bitrixFaults)
  {
    $parsedBitrixFaults = [];


    foreach ($bitrixFaults as $k => $v) {

      // return $bitrixFaults[$k];

      $parsedBitrixFaults[$k]['NMASHINE'] = array_values($bitrixFaults[$k]['PROPERTY_249'])[0];
      $parsedBitrixFaults[$k]['DEAL'] = array_values($bitrixFaults[$k]['PROPERTY_230'])[0];
      $parsedBitrixFaults[$k]['DATEADD'] = $bitrixFaults[$k]['DATE_CREATE'];
      $parsedBitrixFaults[$k]['NARAB'] = array_values($bitrixFaults[$k]['PROPERTY_226'])[0];
      $parsedBitrixFaults[$k]['CATEGORY'] = array_values($bitrixFaults[$k]['PROPERTY_231'])[0];

      switch (array_values($bitrixFaults[$k]['PROPERTY_229'])[0]) { //Замена для поля виновника
        case '135':
          $parsedBitrixFaults[$k]['WHO'] = 'Поставщик';
          break;
        case '136':
          $parsedBitrixFaults[$k]['WHO'] = 'Производственный дефект';
          break;
        case '137':
          $parsedBitrixFaults[$k]['WHO'] = 'Конструкторская ошибка';
          break;
        case '138':
          $parsedBitrixFaults[$k]['WHO'] = 'Эксплуатирующая организация';
          break;
        case '139':
          $parsedBitrixFaults[$k]['WHO'] = "Испытания";
          break;
      }


      switch (array_values($bitrixFaults[$k]['PROPERTY_231'])[0]) { //Замена для поля виновника
        case '2':
          $parsedBitrixFaults[$k]['CATEGORY'] = "Гарантийное обслуживание";
          break;
        case '8':
          $parsedBitrixFaults[$k]['CATEGORY'] = "Опции и обкатка";
          break;
        case '7':
          $parsedBitrixFaults[$k]['CATEGORY'] = "ЧЗРМ - Сервис";
          break;
        case '9':
          $parsedBitrixFaults[$k]['CATEGORY'] = "Армия - Сервис";
          break;
        case '17':
          $parsedBitrixFaults[$k]['CATEGORY'] = "Тестовая сделка";
          break;
      }

      switch (array_values($bitrixFaults[$k]['PROPERTY_227'])[0]) { //Замена для поля категории неисправностей
        case '141':
          $parsedBitrixFaults[$k]['CATOFMAL'] = "Электрика";
          break;
        case '142':
          $parsedBitrixFaults[$k]['CATOFMAL'] = "Навесное оборудование";
          break;
        case '143':
          $parsedBitrixFaults[$k]['CATOFMAL'] = "Облицовка";
          break;
        case '144':
          $parsedBitrixFaults[$k]['CATOFMAL'] = "Кабина";
          break;
        case '145':
          $parsedBitrixFaults[$k]['CATOFMAL'] = "Рама";
          break;
        case '146':
          $parsedBitrixFaults[$k]['CATOFMAL'] = "Гидравлика";
          break;
        case '147':
          $parsedBitrixFaults[$k]['CATOFMAL'] = "Ходовая группа";
          break;
        case '148':
          $parsedBitrixFaults[$k]['CATOFMAL'] = "ДВС";
          break;
        case '149':
          $parsedBitrixFaults[$k]['CATOFMAL'] = "Техническое обслуживание";
          break;
        case '150':
          $parsedBitrixFaults[$k]['CATOFMAL'] = "Сборочные дефекты (Испытания)";

          break;
      }

      $faultId = array_values($bitrixFaults[$k]['PROPERTY_240'])[0];

      // return $faultId;

      $data = [
        "IBLOCK_TYPE_ID" => "lists",
        "IBLOCK_ID" => 74,
        "ELEMENT_ID" => $faultId
      ];

      $hook = "https://dstural24.ru/rest/830/a31yf1u7rzxrr5jz/lists.element.get.json";

      $bitrixFaultName = $this->bitrixHook($data,$hook);

      $parsedBitrixFaults[$k]['IDMAL'] = explode(" ",$bitrixFaultName['result']['0']['NAME'])[1];
    }

    // return $bitrixFaults;

    return $parsedBitrixFaults;
  }

  public function getMashineFaults($values, $db)
  {
    $mashineNum = $values['params']['mashineNumber'];
    $limit = $values['params']['limit'];


    $sql = "SELECT * FROM listofmachines WHERE NMACHINE='$mashineNum'";


    $st = $db['error_db']->prepare($sql);
    $st->execute();
    $query = $st->fetchAll(PDO::FETCH_ASSOC);

    $data = [
      "IBLOCK_TYPE_ID" => "lists",
      "IBLOCK_ID" => 72,
      "FILTER" => array(
        "PROPERTY_249" => "$mashineNum"
      )

    ];

    $hook = "https://dstural24.ru/rest/830/a31yf1u7rzxrr5jz/lists.element.get.json";


    $bitrixFaults = $this->bitrixHook($data,$hook);


    $parsedBitrixFaults = $this->parseBitrixFaults($bitrixFaults['result']);

    $result = array_merge($parsedBitrixFaults, $query);

    if (isset($limit)) {
      $result = array_splice($result, 0, $limit);
    }


    return $result;
  }



  public function getDealsStatsCategories($values, $db)
  {


    if ($values['params']['forbidden'] == 'false') {

      $formatedValues = [];
      $typeClassCode = [];

      switch ($values['params']['faultCategory']) { //Замена для поля категории неисправностей
        case '141':
          $faultCategory = 1;
          break;
        case '142':
          $faultCategory = 2;
          break;
        case '143':
          $faultCategory = 3;
          break;
        case '144':
          $faultCategory = 4;
          break;
        case '145':
          $faultCategory = 5;
          break;
        case '146':
          $faultCategory = 6;
          break;
        case '147':
          $faultCategory = 7;
          break;
        case '148':
          $faultCategory = 8;
          break;
        case '149':
          $faultCategory = 9;
          break;
        case '150':
          $faultCategory = 10;
          break;
      }

      switch ($values['params']['culprit']) { //Замена для поля виновника
        case '135':
          $culprit = 1;
          break;
        case '136':
          $culprit = 8;
          break;
        case '137':
          $culprit = 13;
          break;
        case '138':
          $culprit = 14;
          break;
        case '139':
          $culprit = 15;
          break;
      }

      switch ($values['params']['dealCategory']) { //Замена для поля виновника
        case '2':
          $dealCategory = 4;
          break;
        case '8':
          $dealCategory = 2;
          break;
        case '7':
          $dealCategory = 3;
          break;
        case '9':
          $dealCategory = 1;
          break;
      }

      foreach ($values['params']['typeClass'] as $k => $v) {
        switch ($values['params']['typeClass'][$k]) {
          case '582':
            $typeClassCode[] = "1.9";
            break;
          case '579':
            $typeClassCode[] = "1.10";
            break;
          case '583':
            $typeClassCode[] = "1.11";
            break;
          case '581':
            $typeClassCode[] = "1.12";
            break;
          case '580':
            $typeClassCode[] = "1.13";
            break;
          case '587':
            $typeClassCode[] = "1.57";
            break;
          case '588':
            $typeClassCode[] = "2.14";
            break;
          case '589':
            $typeClassCode[] = "2.15";
            break;
          case '591':
            $typeClassCode[] = "2.16";
            break;
          case '590':
            $typeClassCode[] = "2.17";
            break;
          case '586':
            $typeClassCode[] = "kvg280";
            break;
        }
      }

      $formatedValues['fault_category'] = "$faultCategory";
      $formatedValues['culprit'] = "$culprit";
      $formatedValues['deal_category'] = "$dealCategory";
      $formatedValues['dateFrom'] = $values['params']['dateFrom'];
      $formatedValues['dateTo'] = $values['params']['dateTo'];
      $formatedValues['typeClass'] = $typeClassCode;

      $formatedValues['worktimeFrom'] = $values['params']['worktimeFrom']; //Доделать
      $formatedValues['worktimeTo'] = $values['params']['worktimeTo'];

      if ($values['params']['faultName'] != "") {

        $sql = "SELECT code FROM `faults_type_list` WHERE title=" . "'" . $values['params']['faultName'][0] . "'";
        $st = $db['dstinfonew']->prepare($sql);
        $st->execute();
        $faultCodeQuery = $st->fetchAll(PDO::FETCH_ASSOC);

        // if(!empty($faultCodeQuery[0])) {
        $code = $faultCategory . "." . $faultCodeQuery['0']['code'];
        $formatedValues['code'] = $code;
        // }
      }

      $and = false;
      $sql = "SELECT code, count(code) FROM `faults_list` WHERE ";

      $queryString = "";
      foreach ($formatedValues as $k => $v) {

        if (!empty($v) && $v != 'Все') {

          if ($and == false) {
            $and = true;
          } else {
            $queryString .= ' AND ';
          }

          switch ($k) {
            case 'dateFrom':
              $queryString .= 'date' . ">='" . $v . "'";
              break;
            case 'dateTo':
              $queryString .= 'date' . "<='" . $v . "'";
              break;
            case 'deal_category':
              $categoriesSql = "SELECT * FROM faults_deal_list WHERE category=" . $formatedValues['deal_category'];


              $st = $db['datinfonew']->prepare($categoriesSql);
              $st->execute();
              $categories = $st->fetchAll(PDO::FETCH_ASSOC);

              $queryString .= "deal IN (";

              foreach ($categories as $categoryRow) {
                $queryString .= "'" . $categoryRow['deal'] . "', ";
              }

              $queryString = substr($queryString, 0, -2);
              $queryString .= ")";

              break;
            case 'typeClass':

              $queryString .= "nomer IN (";

              // return [$formatedValues['typeClass']];

              foreach ($formatedValues['typeClass'] as $typeClass) {
                $typeClassValues = explode(".", $typeClass);

                $typeClassSql = "SELECT nomer FROM machines WHERE type=" . $typeClassValues['0'] . " AND class=" . $typeClassValues['1'];
                $st = $db['dstinfonew']->prepare($typeClassSql);
                $st->execute();
                $typeClassQuery = $st->fetchAll(PDO::FETCH_ASSOC);

                // return [!empty($typeClassQuery)];
                if ($typeClass == "kvg280") {
                  $typeClassQuery[0] = ['nomer' => '1488'];
                }
                // return [$typeClassQuery];


                foreach ($typeClassQuery as $typeClassRow) {
                  $queryString .= "'" . $typeClassRow['nomer'] . "', ";
                }
              }

              $queryString = substr($queryString, 0, -2);
              $queryString .= ")";



              break;
            case 'worktimeFrom':
              $queryString .= 'work_time' . ">='" . $v . "'";
              break;
            case 'worktimeTo':
              $queryString .= 'work_time' . "<='" . $v . "'";
              break;
            default:
              $queryString .= $k . "='" . $v . "'";
          }
        } //if

      } //foreach

      $sql .= $queryString;
      $sql .= " GROUP BY code";

      $st = $db['dstinfonew']->prepare($sql);
      $st->execute();
      $dealsQuery = $st->fetchAll(PDO::FETCH_ASSOC);

      // return [$sql];
      // return [$and];

    } else { //Если get пустой, то добавляем в result все сделки (без фильтра!) и выводим на странице

      $sql = "SELECT code, count(code) FROM faults_list GROUP BY code";

      $st = $db['dstinfonew']->prepare($sql);
      $st->execute();
      $dealsQuery = $st->fetchAll(PDO::FETCH_ASSOC);

      // return $dealsQuery;
    }

    $catsQuery = [];
    foreach ($dealsQuery as $k => $v) {
      // return $dealsQuery[$k];

      $code = strval($dealsQuery[$k]['code']);

      $codeArr = explode('.', $code);
      $sql = "SELECT child.title as childTitle, parent.title as parentTitle FROM faults_type_list AS parent LEFT JOIN faults_type_list AS child ON child.parent = parent.id WHERE parent.code = " . $codeArr[0] . " AND child.code = " . $codeArr[1];


      $st = $db['dstinfonew']->prepare($sql);
      $st->execute();
      $childParentTitles = $st->fetchAll(PDO::FETCH_ASSOC);
      $catName = "[" . $childParentTitles[0]['parentTitle'] . "] " . $childParentTitles[0]['childTitle'];


      $catsQuery[$catName] = $dealsQuery[$k]['count(code)'];
    }

    return $catsQuery; //ВСЯ ФИНАЛЬНАЯ
    // return $dealsQuery; //ВСЯ ФИНАЛЬНАЯ

  }

  public function sendBitrix($values, $db)
  {
    $arr = [];
    $sql = "SELECT * FROM `malfunctions` WHERE PARENT IS NOT NULL";
    $st = $db['error_db']->prepare($sql);
    $st->execute();

    $result = $st->fetchAll(PDO::FETCH_ASSOC);

    // return $result;

    


    for ($i = 0; $i < count($result); $i++) {
      switch ($result[$i]['PARENT']) {
        case 18:
          $catID = 9417;
          $catName = "[Электрика]";
          break;
        case 21:
          $catID = 9423;
          $catName = "[Навесное оборудование]";
          break;
        case 23:
          $catID = 9424;
          $catName = "[Облицовка]";
          break;
        case 27:
          $catID = 9425;
          $catName = "[Кабина]";
          break;
        case 36:
          $catID = 9426;
          $catName = "[Рама]";
          break;
        case 37:
          $catID = 9427;
          $catName = "[Гидравлика]";
          break;
        case 38:
          $catID = 9428;
          $catName = "[Ходовая группа]";
          break;
        case 39:
          $catID = 9429;
          $catName = "[ДВС]";
          break;
        case 179:
          $catID = 9430;
          $catName = "[Техническое обслуживание]";
          break;
        case 196:
          $catID = 9431;
          $catName = "[Сборочные дефекты (Испытания)]";
          break;
        case 288:
          $catID = 9432;
          $catName = "[Система охлаждения]";
          break;
        case 302:
          $catID = 9433;
          $catName = "[Кондиционер]";
          break;
      }

      $name = $catName . " " . $result[$i]['NAME'];
      $arr[$i]=[
        "name"=>$name,
        "catID"=>$catID
      ];
      // echo "<pre>";

      // print_r($result);
      // echo $name;


      // echo "</pre>";
      // sendDataToBitrix($name,$catID);
    }

    return $arr;
  }

  public function getDBMalfunctions($values, $db)
  {

    if ($values['params']['forbidden'] == 'false') {

      $formatedValues = [];
      $typeClassCode = [];

      foreach ($values['params']['typeClass'] as $k => $v) {
        switch ($values['params']['typeClass'][$k]) {
          case '582':
            $typeClassCode[] = "1.9";
            break;
          case '579':
            $typeClassCode[] = "1.10";
            break;
          case '583':
            $typeClassCode[] = "1.11";
            break;
          case '581':
            $typeClassCode[] = "1.12";
            break;
          case '580':
            $typeClassCode[] = "1.13";
            break;
          case '587':
            $typeClassCode[] = "1.57";
            break;
          case '588':
            $typeClassCode[] = "2.14";
            break;
          case '589':
            $typeClassCode[] = "2.15";
            break;
          case '591':
            $typeClassCode[] = "2.16";
            break;
          case '590':
            $typeClassCode[] = "2.17";
            break;
          case '586':
            $typeClassCode[] = "kvg280";
            break;
        }
      }



      $formatedValues['CATOFMAL'] = $values['params']['faultCategory'];
      $formatedValues['WHO'] = $values['params']['culprit'];
      $formatedValues['CATEGORY'] = $values['params']['dealCategory'];
      $formatedValues['dateFrom'] = $values['params']['dateFrom'];
      $formatedValues['dateTo'] = $values['params']['dateTo'];
      $formatedValues['typeClass'] = $typeClassCode;

      $formatedValues['worktimeFrom'] = $values['params']['worktimeFrom']; //Доделать
      $formatedValues['worktimeTo'] = $values['params']['worktimeTo'];

      $formatedValues['IDMAL'] = $values['params']['faultName'][0];

      // if ($values['params']['faultName'] != "") {

      //   $sql = "SELECT code FROM `malfunctions` WHERE NAME=" . "'" . $values['params']['faultName'][0] . "'";
      //   $st = $db['error_db']->prepare($sql);
      //   $st->execute();
      //   $faultCodeQuery = $st->fetchAll(PDO::FETCH_ASSOC);


      //   if(!empty($faultCodeQuery[0])) {
      //   $code = $formatedValues['CATEGORY'] . "." . $faultCodeQuery['0']['code'];
      //   $formatedValues['code'] = $code;

      //   return [$code];

      //   }
      // }

      $and = false;
      $sql = "SELECT code, count(code) FROM `listofmachines` WHERE ";

      $queryString = "";
      foreach ($formatedValues as $k => $v) {

        if (!empty($v) && $v != 'Все') {

          if ($and == false) {
            $and = true;
          } else {
            $queryString .= ' AND ';
          }

          switch ($k) {
            case 'dateFrom':
              $queryString .= 'DATEADD' . ">='" . $v . "'";
              break;
            case 'dateTo':
              $queryString .= 'DATEADD' . "<='" . $v . "'";
              break;
            case 'typeClass':

              $queryString .= "NMACHINE IN (";

              // return [$formatedValues['typeClass']];

              foreach ($formatedValues['typeClass'] as $typeClass) {
                $typeClassValues = explode(".", $typeClass);

                $typeClassSql = "SELECT nomer FROM machines WHERE type=" . $typeClassValues['0'] . " AND class=" . $typeClassValues['1'];
                $st = $db['dstinfonew']->prepare($typeClassSql);
                $st->execute();
                $typeClassQuery = $st->fetchAll(PDO::FETCH_ASSOC);

                // return [!empty($typeClassQuery)];
                if ($typeClass == "kvg280") {
                  $typeClassQuery[0] = ['nomer' => '1488'];
                }
                // return [$typeClassQuery];


                foreach ($typeClassQuery as $typeClassRow) {
                  $queryString .= "'" . $typeClassRow['nomer'] . "', ";
                }
              }

              $queryString = substr($queryString, 0, -2);
              $queryString .= ")";



              break;
            case 'worktimeFrom':
              $queryString .= 'NARAB' . ">='" . $v . "'";
              break;
            case 'worktimeTo':
              $queryString .= 'NARAB' . "<='" . $v . "'";
              break;
            default:
              $queryString .= $k . "='" . $v . "'";
          }
        } //if

      } //foreach

      $sql .= $queryString;
      $sql .= " GROUP BY code";

      $st = $db['error_db']->prepare($sql);
      $st->execute();
      $dealsQuery = $st->fetchAll(PDO::FETCH_ASSOC);



      // return [$sql];
      // return [$and];

    } else { //Если get пустой, то добавляем в result все сделки (без фильтра!) и выводим на странице

      $sql = "SELECT code, count(code) FROM listofmachines GROUP BY code";

      $st = $db['error_db']->prepare($sql);
      $st->execute();
      $dealsQuery = $st->fetchAll(PDO::FETCH_ASSOC);

      // return [$sql];


      // return $dealsQuery;
    }

    $catsQuery = [];
    foreach ($dealsQuery as $k => $v) {
      // return $dealsQuery[$k];

      $code = strval($dealsQuery[$k]['code']);

      $codeArr = explode('.', $code);
      $sql = "SELECT child.title as childTitle, parent.title as parentTitle FROM faults_type_list AS parent LEFT JOIN faults_type_list AS child ON child.parent = parent.id WHERE parent.code = " . $codeArr[0] . " AND child.code = " . $codeArr[1];


      $st = $db['dstinfonew']->prepare($sql);
      $st->execute();
      $childParentTitles = $st->fetchAll(PDO::FETCH_ASSOC);
      // $catName = "[" . $childParentTitles[0]['parentTitle'] . "] " . $childParentTitles[0]['childTitle'];
      $catName = $childParentTitles[0]['childTitle'];

      $catsQuery[$catName] = $dealsQuery[$k]['count(code)'];
    }

    return $catsQuery; //ВСЯ ФИНАЛЬНАЯ
    // return $dealsQuery; //ВСЯ ФИНАЛЬНАЯ

  }

  public function addTaskByEmail($values, $db) {

    $em = $values['params']['email'];

  //   Необходим get запрос в виде json следующего вида:
  //   {  "function": "addTaskByEmail",
  //    "params": {
  //      "email": <Здесь указать email ответственного по задаче пользователя>,
  //      "title": <Здесь название задачи>,
  //      "description": <Здесь описание задачи>,
  //      "deadline: <Здесь крайний срок исполнения задачи>,
  //     }
  //   }
  //  ﻿
  //  ﻿В случае, если есть ещё данные, которые я не учёл, дописать их в передаваемый объект.
   
  //  Адрес отправки запроса для тестирования: https://dstinfo.ru/breaks/bitrAPI.php
  //  ﻿Так как сервера переезжают, адрес будет изменен. Этот момент нужно учесть.
   
   
   
   

    $data = [
      'FILTER'=>[
        "email" => "demin@tm10.ru"
      ]
    ];

    $hook = "https://dstural24.ru/rest/830/a31yf1u7rzxrr5jz/user.get.json";
    $user = $this->bitrixHook($data,$hook);

    $userID = $user['result']['0']['ID'];

    //Ставлю задачу

    $data = [
      'FIELDS'=>[
        'TITLE'=>'task for test',
        'DESCRIPTION'=>$em,
        'RESPONSIBLE_ID'=>'830',
        'DEADLINE'=>"05.10.2021 19:00"
      ]
    ];

    $hook = "https://dstural24.ru/rest/830/a31yf1u7rzxrr5jz/task.item.add.json";
    $this->bitrixHook($data,$hook);



    return $userID;

    
  }


}


// $dealsStatsCategories = (new BitrixAppsApi())->getDealsStatsCategories($_REQUEST, $db);

$function_name = $_REQUEST['function'];


// $params = [
//   'function' => 'getMashineFaults',
//  'params' => [
//      'mashineNumber' => 1403
//  ]
// ];

$result = ["result" => (new BitrixAppsApi())->$function_name($_REQUEST, $db), "request" => $_REQUEST];
// $result = ["result" => (new BitrixAppsApi())->getMashineFaults(['params' => ['mashineNumber' => 1403]], $db)];
// $result = ["result" => (new BitrixAppsApi())->getMashineFaults($params, $conn2)];
// $log = date('Y-m-d H:i:s') . ' Запись в лог';
// file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);
// file_put_contents(__DIR__ . '/log.txt', 'testing string');



// $mashineID = (new BitrixAppsApi())->bitrixHook();

// print_r($_POST);

// function getMashineFaults($conn2) {
//   $sql = "SELECT * FROM listofmachines WHERE NMACHINE='1423'";


//   $st = $conn2->prepare($sql);
//   $st->execute();
//   $query = $st->fetchAll(PDO::FETCH_ASSOC);

//   return $query;

// }


// $result = getMashineFaults($conn2);



// $result = ["result" => (new BitrixAppsApi())->addTaskByEmail(['params' => ['mashineNumber' => 1403]], $db)];


// $resul = "123";

echo json_encode($result, JSON_PRETTY_PRINT);

// echo json_encode($result);
// print_r($resul);
// print_r($result);




















?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>