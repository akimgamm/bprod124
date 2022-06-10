<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
// \Bitrix\Main\UI\Extension::load(['sidepanel']);
\Bitrix\Main\UI\Extension::load("ui.buttons");
// if (CModule::IncludeModule("tasks"))
// {
//     $res = CTasks::GetList(
//         Array("TITLE" => "ASC"), 
//         Array("RESPONSIBLE_ID" => "830")
//     );


//     $items = [];
//     while ($arTask = $res->GetNext())
//     {
//         // echo "Task name: ".$arTask["TITLE"]."<br>";
// $items[] = $arTask;
//     }
// }

// header("Content-type: application/json; charset=utf-8");
// echo json_encode($items);

print_r($_REQUEST);


function getTasksStopperComments($taskId)
{
  global $DB;

  $strSql = "SELECT * FROM `my_own_kanban_tasks_comments` WHERE task_id='$taskId' ";

  $query = $DB->Query($strSql, false);

  $tasks = [];

  while ($item = $query->NavNext()) {
    $tasks[] = $item;
  }

  // print_r("SELECT * FROM `kanban_tasks` WHERE id IN ('$taskIds')");
  // print_r($tasks);
  return $tasks;
}



function getTasks($taskId)
{
  global $DB;
  // return $params
  // $taskId = $params['tasklistIds'];

  $strSql = "SELECT * FROM `my_own_kanban_tasks` WHERE id='$taskId'";
  $query = $DB->Query($strSql, false);

  $tasks = [];

  while ($item = $query->NavNext()) {
    $tasks[] = $item;
  }

  // print_r("SELECT * FROM `kanban_tasks` WHERE id IN ('$taskIds')");
  // print_r($tasks);
  return $tasks;

  // $stmt = $db->query("UPDATE main_options SET connected_groups_ids = '$newGroupsIds'");
  // return $stmt->execute();;
}

$tasks = getTasks($_REQUEST['taskId'])[0];

$com = getTasksStopperComments($_REQUEST['taskId']);

if ($tasks['stopper'] == '1') {
}
//Форматируем вывод
for ($i = 0; $i < count($com); $i++) {
  $id = $com[$i]['user'];

  $rsUser = CUser::GetByID($id);
  $arUser = $rsUser->Fetch();
  $photoArr = explode("/upload/", CFile::GetPath($arUser['PERSONAL_PHOTO']));
  $com[$i]['userInfo']['photo'] = $photoArr[1];
  $com[$i]['userInfo']['photo'] = CFile::GetPath($arUser['PERSONAL_PHOTO']);
  $name = $arUser['NAME'] . " " . $arUser['LAST_NAME'];
  // echo "<pre>"; print_r($arUser); echo "</pre>";
  $com[$i]['userInfo']['name'] = $name;
  $com[$i]['userInfo']['id'] = $arUser['ID'];

  

  // $com[$i]['date'] =  date('Y-m-d', '2022-6-9 11:18:19' );

  // $com[$i]['date'] = $ts;
}
print_r($tasks);
echo "<pre>";
// print_r($com);
echo "</pre>";

?>



<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
  die();
}

CJSCore::Init("sidepanel");
?>
<!DOCTYPE html>
<html>

<head>
  <script type="text/javascript">
    // Prevent loading page without header and footer
    if (window == window.top) {
      window.location = "<?= CUtil::JSEscape($APPLICATION->GetCurPageParam('', array('IFRAME'))); ?>";
    }
  </script>
  <? $APPLICATION->ShowHead(); ?>
</head>
<style>
  textarea {
    background: #fce9c0;
    /* Цвет фона */
    border: 2px solid #a9c358;
    /* Параметры рамки */
    padding: 10px;
    /* Поля */
    width: 100%;
    /* Ширина */
    height: 200px;
    /* Высота */
    box-sizing: border-box;
    /* Алгоритм расчёта ширины */
    font-size: 14px;
    /* Размер шрифта */
    resize: none;

    font-family: "Roboto";
  }

  textarea:focus,
  *:focus,
  *:focus-visible {
    outline: none;
  }


  blockquote {
    margin: 0;
    background: white;
    border-top: 5px solid #EAF9F9;
    border-bottom: 5px solid #EAF9F9;
    color: #3A3C55;
    padding: 30px 30px 30px 90px;
    position: relative;
    font-family: 'Lato', sans-serif;
    font-weight: 300;
  }

  blockquote:before {
    content: "\201C";
    font-family: serif;
    position: absolute;
    left: 20px;
    top: 20px;
    color: white;
    background: #FB6652;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    font-size: 50px;
    line-height: 1.35;
    text-align: center;
  }

  blockquote p {
    margin: 0 0 16px;
    font-size: 22px;
    letter-spacing: .05em;
    line-height: 1.4;
  }

  blockquote cite {
    font-style: normal;
  }

  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;

    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
  }

  body {
    background-color: #dee1e3;
    font-family: "Roboto", "Tahoma", "Arial", sans-serif;
    ,
  }

  .text-right {
    text-align: right;
  }

  .comments-app {
    margin: 50px auto;
    max-width: 680px;
    padding: 0 50px;
    width: 100%;
  }

  .comments-app h1 {
    color: #191919;
    margin-bottom: 1.5em;
    text-align: center;
    text-shadow: 0 0 2px rgba(152, 152, 152, 1);
  }

  .comment-form {}

  .comment-form .comment-avatar {}

  .comment-form .form {
    margin-left: 100px;
  }

  .comment-form .form .form-row {
    margin-bottom: 10px;
  }

  .comment-form .form .form-row:last-child {
    margin-bottom: 0;
  }

  .comment-form .form .input {
    background-color: #fcfcfc;
    border: none;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, .15);
    color: #555f77;
    font-family: inherit;
    font-size: 14px;
    padding: 5px 10px;
    outline: none;
    width: 100%;

    -webkit-transition: 350ms box-shadow;
    -moz-transition: 350ms box-shadow;
    -ms-transition: 350ms box-shadow;
    -o-transition: 350ms box-shadow;
    transition: 350ms box-shadow;
  }

  .comment-form .form textarea.input {
    height: 100px;
    padding: 15px;
  }

  .comment-form .form label {
    color: #555f77;
    font-family: inherit;
    font-size: 14px;
  }

  .comment-form .form input[type=submit] {
    background-color: #555f77;
    border: none;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, .15);
    color: #fff;
    cursor: pointer;
    display: block;
    margin-left: auto;
    outline: none;
    padding: 6px 15px;

    -webkit-transition: 350ms box-shadow;
    -moz-transition: 350ms box-shadow;
    -ms-transition: 350ms box-shadow;
    -o-transition: 350ms box-shadow;
    transition: 350ms box-shadow;
  }

  .comment-form .form .input:focus,
  .comment-form .form input[type=submit]:focus,
  .comment-form .form input[type=submit]:hover {
    box-shadow: 0 2px 6px rgba(121, 137, 148, .55);
  }

  .comment-form .form.ng-submitted .input.ng-invalid,
  .comment-form .form .input.ng-dirty.ng-invalid {
    box-shadow: 0 2px 6px rgba(212, 47, 47, .55) !important;
  }

  .comment-form .form .input.disabled {
    background-color: #E8E8E8;
  }


  .comments {}

  .comment-form,
  .comment {
    margin-bottom: 20px;
    position: relative;
    z-index: 0;
  }

  .comment-form .comment-avatar,
  .comment .comment-avatar {
    border: 2px solid #fff;
    border-radius: 50%;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .2);
    height: 80px;
    left: 0;
    overflow: hidden;
    position: absolute;
    top: 0;
    width: 80px;
  }

  .comment-form .comment-avatar img,
  .comment .comment-avatar img {
    display: block;
    height: auto;
    width: 100%;
  }

  .comment .comment-box {
    background-color: #fcfcfc;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, .15);
    margin-left: 100px;
    min-height: 60px;
    position: relative;
    padding: 15px;
  }

  .comment .comment-box:before,
  .comment .comment-box:after {
    border-width: 10px 10px 10px 0;
    border-style: solid;
    border-color: transparent #FCFCFC;
    content: "";
    left: -10px;
    position: absolute;
    top: 20px;
  }

  .comment .comment-box:before {
    border-color: transparent rgba(0, 0, 0, .05);
    top: 22px;
  }

  .comment .comment-text {
    color: #555f77;
    font-size: 15px;
    margin-bottom: 25px;
  }

  .comment .comment-footer {
    color: #acb4c2;
    font-size: 13px;
  }

  .comment .comment-footer:after {
    content: "";
    display: table;
    clear: both;
  }

  .comment .comment-footer a {
    color: #acb4c2;
    text-decoration: none;

    -webkit-transition: 350ms color;
    -moz-transition: 350ms color;
    -ms-transition: 350ms color;
    -o-transition: 350ms color;
    transition: 350ms color;
  }

  .comment .comment-footer a:hover {
    color: #555f77;
    text-decoration: underline;
  }

  .comment .comment-info {
    float: left;
    width: 85%;
  }

  .comment .comment-author {}

  .comment .comment-date {}

  .comment .comment-date:before {
    content: "|";
    margin: 0 10px;
  }

  .comment-actions {
    float: left;
    text-align: right;
    width: 15%;
  }

  .stopper-warning {
    margin-top: 20px;
  }

  .stopper-text {
    margin-top: 20px;
  }

  .stopper-submit-button-set {
    margin-top: 20px;
  }

  .stopper-submit-button-unset {
    margin-top: 20px;
  }

  .stopper-comments-list-warning {
    margin-top: 30px;
    margin-bottom: 40px;
  }

  



</style>

<body class="task-stopper-slider-body">

  <div class="task-stopper-slider-title"><? $APPLICATION->ShowTitle(); ?></div>

  <div class="task-stopper-slider-workarea">
    <div class="task-stopper-slider-sidebar"><? $APPLICATION->ShowViewContent("sidebar"); ?></div>
    <div style="padding: 0 15px 20px 30px;" class="disk-slider-content">
      <?
      if ($_REQUEST['mode'] == "setStopper") {


      ?>
      
      <?//Если стоппер не стоит, то показываем форму постановки стопперов?>
      <? if ($tasks['stopper'] != 1) {?>
        <div class="stopper-warning">
          <p><b>Введите причину постановки стоппера:</b></p>
        </div>

        <div class="stopper-text">
          <p><textarea id='commentsText' name="comment"></textarea></p>
        </div>


        <div class="stopper-submit-button-set">
          <input id="setStopper" type="submit" class="ui-btn ui-btn-success" name="submit" value="Поставить стоппер" title="Преостановить работу над задачей">
        </div>


        <?//При постановке стоппера отражаются комментарии к ранее поставленным стопперам по задаче(Если они есть)?>
          <? if (count($com)>0) {?>
          <div class="stopper-comments-list">

            <div class="stopper-comments-list-warning">
              <p><b>Комментарии к поставленным ранее стопперам:</b></p>
            </div>

            <? foreach ($com as $value) { ?>
            <? //print_r($value); ?>

            <div class="comment">
              <!-- Comment Avatar -->
              <div class="comment-avatar">
                <img src="https://dstural124.ru/<? echo $value['userInfo']['photo'] ?>">
              </div>

              <!-- Comment Box -->
              <div class="comment-box">
                <div class="comment-text"><? echo $value['comment_text']; ?></div>
                <div class="comment-footer">
                  <div class="comment-info">
                    <span class="comment-author">
                      <a href="https://dstural124.ru/company/personal/user/<? echo $value['userInfo']['id']; ?>/"><? echo $value['userInfo']['name']; ?></a>
                    </span>
                    <span class="comment-date"><? echo $value['date']; ?></span>
                  </div>
                </div>
              </div>

            </div>


          <? } //foreach ?>

          </div> <!-- stopper-comments-list -->


          <? } //count($com) ?>


      <? } else { //tasks['stopper'] непонятная ветка развития ?>

        <div class="stopper-warning">
          <p><b>Стоппер уже стоит</b></p>
        </div>


      <? } ?>


       
      

        



      


    <?  } //endif ?>

      <!-- //////////////// -->

      <? //Если показываю дополнительное окно
      if ($_REQUEST['mode'] == "showAdditionalInfo") {
        
      ?>

        <div class="stopper-warning">
          <p><h2>Дополнительнные данные:</h2></p>
        </div>

      <? if ($tasks['stopper'] == 1) {?>
        <div class="stopper-warning">
          <p><b>Статус активности: задача стоит на стоппере(приостановлена)</b></p>
        </div>
        <div class="stopper-submit-button-unset">
          <input id="unsetStopper" type="submit" class="ui-btn ui-btn-success" name="submit" value="Снять стоппер" title="Возобновить работу над задачей">
        </div>

      <? }  else { ?>
        <div class="stopper-warning">
          <p><b>Статус активности: задача в работе(не на стоппере)</b></p>
        </div>
      <? } ?>

      <? if (count($com)>0) { ?>
          <div class="stopper-comments-list">

            <div class="stopper-comments-list-warning">
              <p><b>Комментарии к текущему и поставленным ранее стопперам:</b></p>
            </div>

            <? foreach ($com as $value) { ?>
            <? //print_r($value); ?>

            <div class="comment">
              <!-- Comment Avatar -->
              <div class="comment-avatar">
                <img src="https://dstural124.ru/<? echo $value['userInfo']['photo'] ?>">
              </div>

              <!-- Comment Box -->
              <div class="comment-box">
                <div class="comment-text"><? echo $value['comment_text']; ?></div>
                <div class="comment-footer">
                  <div class="comment-info">
                    <span class="comment-author">
                      <a href="https://dstural124.ru/company/personal/user/<? echo $value['userInfo']['id']; ?>/"><? echo $value['userInfo']['name']; ?></a>
                    </span>
                    <span class="comment-date"><? echo $value['date']; ?></span>
                  </div>
                </div>
              </div>

            </div>


          <? } //foreach ?>

          </div> <!-- stopper-comments-list -->




            <? } //count() ?>


      <? }//endif ?>




    </div>
  </div>
</body>

</html>

<script>
  class Application {
    static async sendAjax(component, action, mode, params) {

      const request = await BX.ajax.runComponentAction(component, action, {
        mode: mode,
        data: params
      });

      return await request.data;
    }
  }
</script>

<script>
  let mode = "<? echo $_REQUEST['mode']; ?>";
  let taskId = "<? echo $_REQUEST['taskId']; ?>";
  let userId = "<? echo $_REQUEST['userId']; ?>";
  let date = new Date().toLocaleString().replace(",","");

  // let commentsText = "<? // echo $_REQUEST['commentsText']; 
                          ?>";

  let setStopperButton = document.getElementById('setStopper');

  if(setStopperButton) {
    setStopperButton.onclick = async function() {
    let commentsText = document.getElementById('commentsText');
    if (commentsText) {
      let commentsTextValue = document.getElementById('commentsText').value;


    console.log(commentsTextValue.value);


    if (commentsTextValue != '' && commentsTextValue != null) {
      let task = await Application.sendAjax('dstapps:ownkanban', 'testinfo', 'class', {
        className: 'OwnKanbanTasks',
        methodName: 'setTasksStopperComments',
        params: {
          taskId: taskId,
          userId: userId,
          commentsText: commentsTextValue,
          date: date
        }
      });
      console.log(task, 'ы');
      let task2 = await Application.sendAjax('dstapps:ownkanban', 'testinfo', 'class', {
        className: 'OwnKanbanTasks',
        methodName: 'setTasksStopper',
        params: {
          taskId: taskId,
        }
      });

      BX.SidePanel.Instance.postMessage(window, "onMessage", {
        taskId: taskId,
        eventActivity: "setStopper"
      });
      BX.SidePanel.Instance.close();

    } else {
      alert("Введите текст комментария к стопперу");
    }

    }
    
    //console.log(task);
  }
  }


 

  let unsetStopperButton = document.getElementById('unsetStopper');




  unsetStopperButton.onclick = async function() {
   
    let task2 = await Application.sendAjax('dstapps:ownkanban', 'testinfo', 'class', {
      className: 'OwnKanbanTasks',
      methodName: 'unsetTasksStopper',
      params: {
        taskId: taskId,
      }
    });

    BX.SidePanel.Instance.postMessage(window, "onMessage", {
        taskId: taskId,
        eventActivity: "unsetStopper"
      });



    BX.SidePanel.Instance.close();

  } //unsetclick

  console.log(mode);
</script>
</body>

</html>