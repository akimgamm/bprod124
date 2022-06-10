<? 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
\Bitrix\Main\UI\Extension::load(['sidepanel']);
// \Bitrix\Main\UI\Extension::load("ui.buttons"); 
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

?>
<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>Поставить стопер</title>
  <style>
    textarea {
      background: #fce9c0; /* Цвет фона */
      border: 2px solid #a9c358; /* Параметры рамки */
      padding: 10px; /* Поля */
      width: 100%; /* Ширина */
      height: 200px; /* Высота */
      box-sizing: border-box; /* Алгоритм расчёта ширины */
      font-size: 14px; /* Размер шрифта */
      resize: none;
    }

    textarea:focus, *:focus, *:focus-visible {
      outline: none;
    }

  </style>
 </head>
 <body> 
  
 <?
 if ($_REQUEST['mode'] == "setStopper") {

 
 ?>
   <p><b>Введите причину постановки стоппера:</b></p>
   <p><textarea name="comment"></textarea></p>
   <input id="saveComment" type="submit" class="ui-btn ui-btn-success"  name="submit"  value="Сохранить" title="Сохранить и перейти к просмотру">


<? } else { ?>

  <p><b>Комментарии к стопперу:</b></p>

  <? }; ?>
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

    let saveCommentButton = document.getElementById('saveComment');
    console.log(saveCommentButton);
    saveCommentButton.onclick = function () {
     let task = await Application.sendAjax('dstapps:ownkanban', 'testinfo', 'class', { className: 'OwnKanbanTasks', methodName: 'getTasksStopperComments', params: { taskId: "" } });
     console.log(task);
    }

    console.log(mode);
  </script>
 </body>

</html>