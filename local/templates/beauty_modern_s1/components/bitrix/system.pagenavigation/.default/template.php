<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */

$this->setFrameMode(true);

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>

<div class="page-navigation-wrapper col-margin-top">
	<div class="page-navigation">
<?if($arResult["bDescPageNumbering"] === true):?>

	<?if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
		<?if($arResult["bSavePage"]):?>
			<a class="left-arr" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?echo GetMessage("round_nav_back")?></a>
			<span class="page-navigation-pages"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>">1</a>
		<?else:?>
			<?if (($arResult["NavPageNomer"]+1) == $arResult["NavPageCount"]):?>
				<a class="left-arr" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?echo GetMessage("round_nav_back")?></a>
			<?else:?>
				<a class="left-arr" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?echo GetMessage("round_nav_back")?></a>
			<?endif?>
			<span class="page-navigation-pages"><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a>
		<?endif?>
	<?else:?>
			<span class="left-arr"><?echo GetMessage("round_nav_back")?></span>
			<span class="page-navigation-pages"><a class="current" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a>
	<?endif?>

	<?
	$arResult["nStartPage"]--;
	while($arResult["nStartPage"] >= $arResult["nEndPage"]+1):
	?>
		<?$NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;?>

		<a<?=($arResult["nStartPage"] == $arResult["NavPageNomer"] ? ' class="current"' : '')?> href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$NavRecordGroupPrint?></a>
		
		<?$arResult["nStartPage"]--?>
	<?endwhile?>

	<?if ($arResult["NavPageNomer"] > 1):?>
		<?if($arResult["NavPageCount"] > 1):?>
			<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1"><?=$arResult["NavPageCount"]?></a>
		<?endif?>
            </span><a class="right-arr" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?echo GetMessage("round_nav_forward")?></a>
	<?else:?>
		<?if($arResult["NavPageCount"] > 1):?>
			<a class="current" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"])?>"><?=$arResult["NavPageCount"]?></a>
		<?endif?>
			</span><span class="right-arr"><?echo GetMessage("round_nav_forward")?></span>
	<?endif?>

<?else:?>

	<?if ($arResult["NavPageNomer"] > 1):?>
		<?if($arResult["bSavePage"]):?>
			<a class="left-arr" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?echo GetMessage("round_nav_back")?></a>
            <span class="page-navigation-pages">
			<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1">1</a>
		<?else:?>
			<?if ($arResult["NavPageNomer"] > 2):?>
				<a class="left-arr" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?echo GetMessage("round_nav_back")?></a>
                <span class="page-navigation-pages">
			<?else:?>
				<a class="left-arr" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?echo GetMessage("round_nav_back")?></a>
			<?endif?>
            <span class="page-navigation-pages">
			<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a>
		<?endif?>
	<?else:?>
			<span class="left-arr"><?echo GetMessage("round_nav_back")?></span>
            <span class="page-navigation-pages">
			<a class="current" href="<?=$arResult["sUrlPath"]?>">1</a>
	<?endif?>

	<?
	$arResult["nStartPage"]++;
	while($arResult["nStartPage"] <= $arResult["nEndPage"]-1):
	?>
		<a<?=($arResult["nStartPage"] == $arResult["NavPageNomer"] ? ' class="current"' : '')?> href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
		<?$arResult["nStartPage"]++?>
	<?endwhile?>

	<?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
		<?if($arResult["NavPageCount"] > 1):?>
			<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a>
		<?endif?>
			</span><a class="left-arr" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?echo GetMessage("round_nav_forward")?></a>
	<?else:?>
		<?if($arResult["NavPageCount"] > 1):?>
			<a class="current" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a>
		<?endif?>
			</span><span class="right-arr"><?echo GetMessage("round_nav_forward")?></span>
	<?endif?>
<?endif?>

<?if ($arResult["bShowAll"]):?>
	<?if ($arResult["NavShowAll"]):?>
			<a class="left-arr" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0" rel="nofollow"><span><?echo GetMessage("round_nav_pages")?></span></a>
	<?else:?>
			<a class="left-arr" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1" rel="nofollow"><span><?echo GetMessage("round_nav_all")?></span></a>
	<?endif?>
<?endif?>
	</div>
</div>
