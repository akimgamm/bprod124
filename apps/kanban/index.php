<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

\Bitrix\Main\Loader::includeModule('ui');


$APPLICATION->SetTitle("Многомерный канбан");

//$test = new ButtonLocation;
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


require($_SERVER["DOCUMENT_ROOT"]."/local/apps/kanbanApp/title.php");
?>






<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>