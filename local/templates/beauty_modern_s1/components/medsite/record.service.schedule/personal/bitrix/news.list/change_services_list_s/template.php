<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? if (isset($arParams['FILTER_NAME']) && is_array($GLOBALS[$arParams['FILTER_NAME']]['ID']))
	$haveNoServiceRecord = in_array('NONE',$GLOBALS[$arParams['FILTER_NAME']]['ID']);
else
	$haveNoServiceRecord = false;
?>

<select id="servselect" class="styler styler-steps" name="servselect">
	<?if ($haveNoServiceRecord):?>
		<option <?=$arParams['CURRENT_SERVICE']=='NONE'?'selected':''?> data-url="<?=str_replace('#ELEMENT_ID#','NONE',$arParams['DETAIL_URL'])?>" id="service_0">
			<?=GetMessage('RECORD_WITHOUT_SERVICE')?>
		</option>
	<?endif?>
	<?foreach($arResult['ITEMS'] as $arService):?>
		<option <?=$arParams['CURRENT_SERVICE']==$arService['ID']?'selected':''?> data-url="<?=str_replace('#ELEMENT_ID#',$arService['ID'],$arParams['DETAIL_URL'])?>" id="service_<?=$arService['ID']?>">
			<?=$arService['NAME']?>
		</option>
	<?endforeach;?>
</select>
