<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader;

use Bitrix\Main\Engine\Contract\Controllerable;


//Класс работы с Задачами. CRUD для таблицы дополнительных свойств задачи













class OwnKanbanTasks {
  public static function getAllTasks($params)
  {

    global $DB;

    $finalConnected = join(',',$params['finalConnected']);
    $strSql = "SELECT * FROM `b_tasks` WHERE GROUP_ID='$finalConnected' ";
    // return $strSql;
    $query = $DB->Query($strSql, false);

    while ($item = $query->NavNext()) {
      $tasks[] = $item;
    }

 
    return $tasks;
    // $query->registerRuntimeField(
    //   $cityTableName,
    //   [
    //     'data_type' => CityTable::getEntity(),
    //     'reference' => [
    //       // '=this.city_id' => 'ref.id',
    //     ],
    //     'join_type' => "LEFT"
    //   ]
    // );
  }

  public function createTable(){
    global $DB;

    $strSql = "
    1CREATE TABLE `my_own_kanban_users_settings` (
      `id` int NOT NULL,
      `connected_groups_ids` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8_general_ci DEFAULT NULL,
      `role` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8_general_ci DEFAULT NULL
    )";

    $res = $DB->Query($strSql, false);
    return $res;
}

  public static function getTasks($params)
  {
    global $DB;
    // return $params
    $tasklistIds = $params['tasklistIds'];

    $strSql = "SELECT * FROM `my_own_kanban_tasks` WHERE id IN ('$tasklistIds;')";
    $query= $DB->Query($strSql, false);

    $tasks = [];
   
    while( $item = $query->NavNext()){
      $tasks[] = $item;
    }

    // print_r("SELECT * FROM `kanban_tasks` WHERE id IN ('$taskIds')");
    // print_r($tasks);
    return $tasks;

    // $stmt = $db->query("UPDATE main_options SET connected_groups_ids = '$newGroupsIds'");
    // return $stmt->execute();;
  }

  public static function setTask($params)
  {
    global $DB;

    $taskId = $params['taskId'];
    $strSql = "SELECT * FROM `my_own_kanban_tasks` WHERE id ='$taskId'";

    $query = $DB->Query($strSql, false);
    $task = [];
   
    while( $item = $query->NavNext()){
      $task[] = $item;
    }

    // print_r($task);

    if (count($task) != 0) { //Если задача записана
      return $task;
    } else { //Иначе записываем
      $strSql ="INSERT INTO my_own_kanban_tasks (id) VALUES ('$taskId');";
      $query = $DB->Query($strSql, false);
      $strSql ="SELECT * FROM `my_own_kanban_tasks` WHERE id ='$taskId'";
      $task = $DB->Query($strSql, false);

      // return ("SELECT * FROM `kanban_tasks` WHERE id ='$taskId'");
      return $task;
    }


  }

  public static function setTasksPriority($params)
  {
    global $DB;
    $task = (new OwnKanbanTasks())->setTask($params);

    $taskPriority = isset($params['taskPriority']) ? $params['taskPriority'] : null;

    if($taskPriority!=null) {
      $taskId=$params['taskId'];
      $taskPriority=$params['taskPriority'];
      $strSql = "UPDATE `my_own_kanban_tasks` SET priority ='$taskPriority' WHERE id='$taskId'";
      $DB->Query($strSql, false);
    }


    return $task;

    

    // $stmt = $db->query("SELECT * FROM `users` WHERE id ='$userId'");
    // $user =  $stmt->fetchAll(PDO::FETCH_ASSOC);
   
    // $stmt = $db->query("UPDATE main_options SET connected_groups_ids = '$newGroupsIds'");
    // return $stmt->execute();;
  }

  public static function getTasksStopperComments() {
    global $DB;
    $strSql = "SELECT * FROM `my_own_kanban_tasks_comments`";;

    $res = $DB->Query($strSql, false);
    $res = $res->Fetch();
    return $res;
  }

  public static function setTasksStopperComments() {
    global $DB;
    $strSql = "INSERT INTO `my_own_kanban_tasks_comments`
    VALUES (value1, value2, value3, 1212);";

    $res = $DB->Query($strSql, false);
    $res = $res->Fetch();
    return $res;
  }



  public function turnedInfo() {
    return "Stroka result";
  }




}

//Класс для работы с пользовательскими настройками
class OwnKanbanUsersSettings
{
  public static function setUsersGroupsIds($params)
  {
    // return $params;
    global $DB;
    $usersConnectedGroupsIds = $params['usersConnectedGroupsIds'];
    $userId = $params['userId'];
    $strSql = "UPDATE `my_own_kanban_users_settings` SET connected_groups_ids ='$usersConnectedGroupsIds' WHERE id='$userId'";
    $query = $DB->Query($strSql, false);
    return $strSql;


  }

  public static function getUser($params)
  {
    global $DB;
    // return $params['userId'];
    $userId = $params['userId'];
    $strSql = "SELECT * FROM `my_own_kanban_users_settings` WHERE id ='$userId'";
    // $strSql = "SELECT * FROM `b_file` LIMIT 5";

    $query = $DB->Query($strSql, false);
    $user= [];
   
    while( $item = $query->NavNext()){

      $user[] = $item;
  }
    // return $user;


    if (count($user) != 0) { //Если юзер записан
      return $user;
    } else { //Иначе записываем


      $strSql = "INSERT INTO `my_own_kanban_users_settings` VALUES ('$userId',NULL,NULL);";
      $user = $DB->Query($strSql, false);

      $strSql = "SELECT * FROM `my_own_kanban_users_settings` WHERE id ='$userId'";
    //   // $strSql = "SELECT * FROM `b_file` LIMIT 5";
  
      $query = $DB->Query($strSql, false);
      $user= [];
     
      while( $item = $query->NavNext()){
        $user[] = $item;
    }
      return $user;
    }

  }

  public function createTable(){
    global $DB;
    $strSql = "
    CREATE TABLE `my_own_kanban_users_settings` (
      `id` int NOT NULL,
      `connected_groups_ids` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8_general_ci DEFAULT NULL,
      `role` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8_general_ci DEFAULT NULL
    )";

    $res = $DB->Query($strSql, false);
    return $res;
}
// Добавляем данные в таблицу


}


class OwnKanbanStoppers extends \CBitrixComponent implements Controllerable
{   



    //Родительский метод проходит по всем параметрам переданным в $APPLICATION->IncludeComponent
    //и применяет к ним функцию htmlspecialcharsex. В данном случае такая обработка избыточна.
    //Переопределяем.
    public function onPrepareComponentParams($arParams)
    {
        \Bitrix\Main\UI\Extension::load(['sidepanel']);


        $addTaskButton = new \Bitrix\UI\Buttons\Button([
          "link" => "/company/personal/user/830/tasks/task/edit/0/",
          "text" => "Добавить задачу",
        "color"=> \Bitrix\UI\Buttons\Color::SUCCESS,
      ], $location = "after_title");
      
      $optionsButton = new \Bitrix\UI\Buttons\Button([
      
          "text" => "Настройки",
        "color"=> \Bitrix\UI\Buttons\Color::SUCCESS,
      ], $location = "after_title");
      
      $optionsButton->addClass('set-connected-groups-button');
      $optionsButton->addClass('ui-btn ui-btn-icon-setting');
      
      
      \Bitrix\UI\Toolbar\Facade\Toolbar::addButton($addTaskButton);
      \Bitrix\UI\Toolbar\Facade\Toolbar::addButton($optionsButton);

      $result = array(
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
        "X" => intval($arParams["X"]),
    );
    return $result;
      
    }

    protected function checkModules()
    {
        if (!Loader::includeModule('iblock'))
            throw new SystemException(Loc::getMessage('CPS_MODULE_NOT_INSTALLED', array('#NAME#' => 'iblock')));
    }

    public function hand(){
      $eventManager = \Bitrix\Main\EventManager::getInstance();

    $eventManager->addEventHandlerCompatible(
      'tasks',
      'OnTaskUpdate',
      function (&$arFields){
        print_r("asdsdsdsdsdsddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd");
      }
    );
    }




    public function executeComponent()
    {
        //if($this->startResultCache())//startResultCache используется не для кеширования html, а для кеширования arResult
            CModule::IncludeModule("tasks");
            $this->arResult["Y"] = $this->sqr($this->arParams["X"]);
           $this->checkModules(); //ы проверяем подключен ли модуль Инфоблоков Битрикс.
            $this->getResult();
            $this->hand();
            $this->includeComponentTemplate();
       
        // return $this->arResult["Y"];
    }

    public function configureActions()
    {
       // Сбрасываем фильтры по-умолчанию (ActionFilter\Authentication и ActionFilter\HttpMethod)
       // Предустановленные фильтры находятся в папке /bitrix/modules/main/lib/engine/actionfilter/
        return [
            'greet' => [ // Ajax-метод
                'prefilters' => [],
            ],
        ];
    }

    protected function getResult()
    {

    }


    //Дальше уже сами методы класса
    public function sqr($x)
    {
        return $x * $x;
    }

    public function greetAction($person = 'guest')
    {
        return "Hi {$person}!";
    }

    public function testinfoAction($className=null,$methodName=null,$params=null)
    {   
      // // (new OwnKanbanTasks())->createTable($params);
      // // (new OwnKanbanUsersSettings())->createTable($params);

      // return $params;

      $class = new $className();
      return $class->$methodName($params);


    return $res;
    }
}


class UserModel
{



  /**
   * Get all the users as an associative array
   *
   * @return array
   */



  public static function setUsersGroupsIds($userId,$usersConnectedGroupsIds)
  {
    $db = static::getDB();
    $db->query("UPDATE `users` SET connected_groups_ids ='$usersConnectedGroupsIds' WHERE id='$userId'");

  }

  public static function setTask($taskId)
  {
    $db = static::getDB();
    $stmt = $db->query("SELECT * FROM `kanban_tasks` WHERE id ='$taskId'");
    $task =  $stmt->fetchAll(PDO::FETCH_ASSOC);

    // print_r($task);

    if (count($task) != 0) { //Если задача записана
      return $task;
    } else { //Иначе записываем
      $db->query("INSERT INTO kanban_tasks (id) VALUES ('$taskId');");
      $db = static::getDB();
      $stmt = $db->query("SELECT * FROM `kanban_tasks` WHERE id ='$taskId'");
      $task =  $stmt->fetchAll(PDO::FETCH_ASSOC);


      return $task;
    }

  }

  public static function getTasks($taskIds)
  {
    $db = static::getDB();
    $stmt = $db->query("SELECT * FROM `kanban_tasks` WHERE id IN ('$taskIds')");
    $tasks =  $stmt->fetchAll(PDO::FETCH_ASSOC);


    return $tasks;


  }


  public static function setTasksPriority($taskId,$taskPriority)
  {
    $db = static::getDB();
    $db->query("UPDATE `kanban_tasks` SET priority ='$taskPriority' WHERE id='$taskId'");

    return ("UPDATE `kanban_tasks` SET priority ='$taskPriority' WHERE id='$taskId'");
  }



  // public static function setTasksColor($taskId,$color)
  // {
  //   $db = static::getDB();
  //   $db->query("UPDATE `kanban_tasks` SET color ='$color' WHERE id='$taskId'");

  //   return ("UPDATE `kanban_tasks` SET color ='$color' WHERE id='$taskId'");

    

  //   // $stmt = $db->query("SELECT * FROM `users` WHERE id ='$userId'");
  //   // $user =  $stmt->fetchAll(PDO::FETCH_ASSOC);
   
  //   // $stmt = $db->query("UPDATE main_options SET connected_groups_ids = '$newGroupsIds'");
  //   // return $stmt->execute();;
  // }
}
