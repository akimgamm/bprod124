<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$today=ConvertTimeStamp(time());
$tomorrow=ConvertTimeStamp(time()+86400);
$yestoday=ConvertTimeStamp(time()-86400);
?>

<table>
	<tr>
		<td>
			<form name="<?echo $arResult["FILTER_NAME"]."_form1"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get">
			<input type="hidden" name="set_filter" value="Y" />
			<input type="hidden" name="header" value="before" />
			<input type="hidden" id="arrFilter2_DATE_ACTIVE_FROM_1" name="arrFilter_DATE_ACTIVE_FROM_1" value="" />
			<input type="hidden" id="arrFilter2_DATE_ACTIVE_FROM_2" name="arrFilter_DATE_ACTIVE_FROM_2" value="" />
			<input type="hidden" id="arrFilter2_DATE_ACTIVE_TO_1" name="arrFilter_DATE_ACTIVE_TO_1" value="" />
			<input type="hidden" id="arrFilter2_DATE_ACTIVE_TO_1" name="arrFilter_DATE_ACTIVE_TO_2" value="<?=$yestoday?>" />
			<input type="submit" class="afisha_button1 <?if($_GET['header']=='before'){?> afisha_button_active<?}?>" name="set_filter" value="<?=GetMessage("BEFORE")?>" /> 
			</form>
		</td>
		<td>
			<form name="<?echo $arResult["FILTER_NAME"]."_form2"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get">
			<input type="hidden" name="set_filter" value="Y" />
			<input type="hidden" name="header" value="today" />
			<input type="hidden" id="arrFilter1_DATE_ACTIVE_FROM_1" name="arrFilter_DATE_ACTIVE_FROM_1" value="" />
			<input type="hidden" id="arrFilter1_DATE_ACTIVE_FROM_2" name="arrFilter_DATE_ACTIVE_FROM_2" value="<?=$today?>" />
			<input type="hidden" id="arrFilter1_DATE_ACTIVE_TO_1" name="arrFilter_DATE_ACTIVE_TO_1" value="<?=$today?>" />
			<input type="hidden" id="arrFilter1_DATE_ACTIVE_TO_1" name="arrFilter_DATE_ACTIVE_TO_2" value="" />
			<input type="submit" class="afisha_button1<?if($_GET['header']!='future' && $_GET['header']!='before'){?> afisha_button_active<?}?>" value="<?=GetMessage("TODAY")?>"/>
			</form>
		</td>
		<td>
			<form name="<?echo $arResult["FILTER_NAME"]."_form3"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get">
			<input type="hidden" name="set_filter" value="Y" />
			<input type="hidden" name="header" value="future" />
			<input type="hidden" id="arrFilter3_DATE_ACTIVE_FROM_1" name="arrFilter_DATE_ACTIVE_FROM_1" value="<?=$tomorrow?>" />
			<input type="hidden" id="arrFilter3_DATE_ACTIVE_FROM_2" name="arrFilter_DATE_ACTIVE_FROM_2" value="" />
			<input type="hidden" id="arrFilter3_DATE_ACTIVE_TO_1" name="arrFilter_DATE_ACTIVE_TO_1" value="" />
			<input type="hidden" id="arrFilter3_DATE_ACTIVE_TO_1" name="arrFilter_DATE_ACTIVE_TO_2" value="" />
			<input type="submit" class="afisha_button1<?if($_GET['header']=='future'){?> afisha_button_active<?}?>" value="<?=GetMessage("FUTURE")?>" />
			</form>
		</td>
	</tr>
</table>





