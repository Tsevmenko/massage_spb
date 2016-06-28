<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>
<div class="search-form">
<form action="<?=$arResult["FORM_ACTION"]?>">
	<input class="input input-search input-round-search" type="text" name="q" value=""  placeholder="<?=GetMessage('T_SEARCH_ON_SITE')?>" />
    <button class="btn btn-round-search"><?=GetMessage("T_SEARCH_BUTTON");?></button>
	<!--input name="s" type="submit" value="" /-->
</form>
</div>