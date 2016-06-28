<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div>
	<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<?=$arResult["DISPLAY_ACTIVE_FROM"]?>&nbsp;&nbsp;
	<?endif;?>
	
	<?if ($arResult['PROPERTIES']['STATUS']['VALUE']):?>
		<b class="<?=$arResult['PROPERTIES']['STATUS']['VALUE_XML_ID']?>"><?=$arResult['PROPERTIES']['STATUS']['VALUE']?></b>
	<?endif;?>
</div>		

<div style="padding-top: 10px;">
<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["PREVIEW_TEXT"]):?>
	<p><b><?=$arResult["PREVIEW_TEXT"];?></b></p>
<?endif;?>

<?if($arResult["DETAIL_TEXT"]):?>
	<?echo $arResult["DETAIL_TEXT"];?>
<?endif?>
</div>
