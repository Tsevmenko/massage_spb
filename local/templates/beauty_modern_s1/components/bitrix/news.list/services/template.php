<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<?/*<div class="search-in-page col-margin-top">
  <form>
    <div class="search-in-page-wrapper" id="bx_service_search_wrapper">
      <input class="input input-block input-search search-in-page-input" type="text" placeholder="Найдите услугу по названию">
      <button id="bx-btn-reset" type="reset" class="search-in-page-reset"><i class="icon icon-search-reset"></i></button>
      <button class="search-in-page-btn"><i class="icon icon-search"></i></button>
      <div id="serviceSearchR" style="display:none;" class="we-serch">Ищем услугу...</div>
    </div>
  </form>
</div>*/?>
<div class="services-list col-margin-top">

<?foreach($arResult["TYPES"] as $k => $arItem):?>
  <?
  $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
  $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
  if($arItem["NAME"] == "") $arItem["NAME"] = "Общий";
  ?>
  <div id="sec_<?=$k?>" class="services-list-item">
    <div class="services-list-header"><?=$arItem["NAME"]?></div>
    <div class="services-list-content white-content-box">
      <ul>
		<?foreach($arItem["ITEMS"] as $k => $v):?>
        <li id="sec_<?=$k?>_service_<?=$v['ID']?>">
          <a href="<?=$v["DETAIL_PAGE_URL"]?>">
            <?=$v["NAME"]?>
			<?if($v["PROPERTIES"]["PRICE"]["VALUE"] != ""):?>
			<span class="fl-r">
				<?=$v["PROPERTIES"]["PRICE"]["VALUE"]?> p
			</span>
			<?endif;?>
          </a>
        </li>
		<?endforeach;?>
      </ul>
    </div>
  </div>
<?endforeach;?>
</div>