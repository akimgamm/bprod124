<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="afisha_row">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="afisha_span" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		
		
		<?if ($arItem["FIELDS"]["DETAIL_PICTURE"]["SRC"]){?>
		<img src="<?=$arItem["FIELDS"]["DETAIL_PICTURE"]["SRC"]?>" width="180">	
		<?}?>
		<br>
		<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><b><?echo $arItem["NAME"]?></b></a><br />
		<?=$arItem["FIELDS"]["DATE_ACTIVE_FROM"]?>   <?=$arItem["FIELDS"]["DATE_ACTIVE_TO"]?><br>
		
	</div> 
<?endforeach;?>
 
</div>

