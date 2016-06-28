<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<div class="box visible">
	<div class="orglist">
	<?foreach ($arResult['ITEMS'] as $arrItem):?>
		<?
		if(!$arrItem['IBLOCK_SECTION_ID'])
					$arrItem["DETAIL_PAGE_URL"]=str_replace(array("#SITE_DIR#/", "#SITE_DIR#", "#SECTION_ID#","#ID#","#ELEMENT_ID#"),array(SITE_DIR, SITE_DIR,"0",$arrItem['ID'],$arrItem['ID']),$arrItem['DETAIL_PAGE_URL']);
		?>
		<div>
			<div>
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
			<div class="property">
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
						<?echo (GetMessage('SETE_ILNK_TITLE').': <a class="org_link" target="blank" href="http://'.$arProperty['VALUE'].'">'.$arProperty['VALUE']."</a>");?>
						<br />
					<?endif;?>
				<?endif;?>
			<?endforeach;?>
			</div>
			<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arrItem["PREVIEW_TEXT"]):?>
				<div><?echo $arrItem["PREVIEW_TEXT"];?></div>
			<?endif;?>
		</div> <br>
	<?endforeach;?>
	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<br /><?=$arResult["NAV_STRING"]?>
	<?endif;?>
	</div>
</div>

