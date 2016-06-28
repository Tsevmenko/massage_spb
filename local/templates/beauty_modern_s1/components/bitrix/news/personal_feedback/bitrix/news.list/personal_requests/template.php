<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="negative-margin">
<?=empty($arResult["ITEMS"])?GetMessage('NO_REQUESTS'):''?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<?echo $arItem["DISPLAY_ACTIVE_FROM"]?>&nbsp;&nbsp;
		<?if (!$arItem["DETAIL_TEXT"]): //РЅРµС‚ РѕС‚РІРµС‚Р°?>
			<?if (strtotime(date('d.m.Y H:i:s')) > strtotime($arItem['DATE_ACTIVE_TO'])):?><b class="text-red"><?=GetMessage('OVERDUE')?></b>
			<?else:?>
				<?$count_days = floor((strtotime($arItem['DATE_ACTIVE_TO']) - strtotime(date('d.m.Y H:i:s')))/3600/24);?>
				<b class="text-green"><?=$count_days?>
					<?if($count_days==1 || fmod($count_days, 10)==1):?><?=GetMessage("DAY");?></b>
					<?elseif($count_days==2 || fmod($count_days, 10)==2 || $count_days==3 || fmod($count_days, 10)==3 || $count_days==4 || fmod($count_days, 10)==4):?><?=GetMessage("DAY1");?></b>
					<?else:?><?=GetMessage("DAY2");?></b>
					<?endif;?>
			<?endif;?>
		<?else:?>
			<?if ($arItem['PROPERTIES']['STATUS']['VALUE']): //СЃС‚Р°С‚СѓСЃ СѓСЃС‚Р°РЅРѕРІР»РµРЅ?>
				<b class="<?=$arItem['PROPERTIES']['STATUS']['VALUE_XML_ID']?>"><?=$arItem['PROPERTIES']['STATUS']['VALUE']?></b>
			<?endif;?>
		<?endif;?>
		<div style="padding-top: 5px; padding-bottom: 10px;">
			<?if($arItem["DETAIL_TEXT"]):?>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><p><?=$arItem["PREVIEW_TEXT"];?></p></a>
			<?else:?>
				<p><?=$arItem["PREVIEW_TEXT"];?></p>
			<?endif;?>	
		</div>
	</div>
<?endforeach;?>

</div>


