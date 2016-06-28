<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(isset ($_REQUEST['arrFilter_ff']['SECTION_ID'])) 
	$ids=intval($_REQUEST['arrFilter_ff']['SECTION_ID']); 
elseif(isset ($_GET['sid'])) 
	$ids=intval($_GET['sid']); 
else 
	$ids='';?>
<div class="orglist col-margin-bottom">
<table class="org_list">
<?$i=0;?>
<?foreach ($arResult['ITEMS'] as $arrItem):?>
<?
?>
	<?if (!$i&1):?>	<tr class="OrgInfo"><?endif?>
		<td class="white-box p20 clearfix" id="<?=$this->GetEditAreaId($arrItem['ID']);?>">
			<div class="title">
				<?if($arParams["DISPLAY_NAME"]!="N" && $arrItem["NAME"]):?>
					<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arrItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
						<a href="<?echo $arrItem["DETAIL_PAGE_URL"]?>"><?echo $arrItem["NAME"]?></a>
					<?else:?>
						<?echo $arrItem["NAME"]?>
					<?endif;?>
				<?endif;?>
				<?if ($arrItem['PROPERTIES']['EL']['VALUE']!=''):?>
					<a href="<?=$arParams['EREG_LINK']?>?wstep=2&org=<?=$arrItem['ID']?>">
					<?if ($arParams['HIDE_ER_ICON']!='Y'): ?>
						<img src="<?=$APPLICATION->GetTemplatePath()?>images/icon_ereg_1.gif" alt="<?=GetMessage('IN_ER_ICON')?>">
					<?endif?>
					</a>
				<?endif?>
			</div>
			<div>
			<?foreach($arrItem['DISPLAY_PROPERTIES'] as $pid=>$arProperty):?>
				<?if ($pid == 'EL') continue?>
				<?if ($pid != 'MEDSITE_ID'):?>
					<?=$arProperty['NAME']?>:&nbsp;
						<?if(is_array($arProperty['VALUE'])):?>
							<?=implode("&nbsp;/&nbsp;", $arProperty["VALUE"]);?>
							<br />
						<?else:?>
							<?=$arProperty['VALUE'];?>
							<br />
						<?endif?>
				<?else:?>
					<?if (!empty($arProperty['VALUE'])):?>
						<?=GetMessage('SETE_ILNK_TITLE')?>: <a class="org_link" target="blank" href="http://<?=$arProperty['VALUE']?>"><?=$arProperty['VALUE']?></a>
						<br />
					<?endif;?>
				<?endif;?>
			<?endforeach;?>
			</div>
			<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arrItem["PREVIEW_TEXT"]):?>
				<div><?echo $arrItem["PREVIEW_TEXT"];?></div>
			<?endif;?>
			<br>
			<div class="rec-btn">
				<a class="btn btn-big" href="<?echo $arrItem["DETAIL_PAGE_URL"]?>"><?=GetMessage('MED_RECORD')?></a>
			</div>
		</td>
	<?if ($i&1):?></tr><tr><td class="rowDelimiter" colspan="2">&nbsp;</td></tr> <?else:?><td class="delimiter"></td><?endif?>
	<?$i++?>
<?endforeach;?>
</table>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>


<div class="clear"></div>
			</div>