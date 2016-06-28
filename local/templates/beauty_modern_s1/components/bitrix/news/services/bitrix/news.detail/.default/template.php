<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if($arResult["DETAIL_TEXT"] == "") $arResult["DETAIL_TEXT"] = "К сожалению, описания данной услуги еще нет.";
?>
<p><?=$arResult["NAME"]?><br></p>

<div class="white-box col-margin-top">
  <div class="tabs">
    <ul class="tabs-switchers">
      <li class="tabs-switcher active">Описание</li>
    </ul>
    <div class="tabs-item active">
      <?=$arResult["DETAIL_TEXT"]?>
    </div><!-- .tabs-item active -->
  </div> <!-- .tabs -->