<?
\Bitrix\Main\Loader::IncludeModule("iblock");
// создаем объект Query. В качестве параметра он принимает объект сущности, относительно которой мы строим запрос
$query = new \Bitrix\Main\Entity\Query(Bitrix\Iblock\ElementTable::getEntity());
// можно еще так: :)
// $query = new \Bitrix\Main\Entity\Query(Bitrix\Main\Entity\Base::getInstance("Bitrix\Iblock\ElementTable"));
$query
    ->setSelect(array("ID", "NAME"))
    ->setFilter(array("IBLOCK_ID" => 1))
    ->setOrder(array("ID" => "ASC"))
    ->setLimit(10);
$query->exec();
$query->dump();

$this->IncludeComponentTemplate();

?>