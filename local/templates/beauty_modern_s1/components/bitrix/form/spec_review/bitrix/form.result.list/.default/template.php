<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<?
if (strlen($arResult["FORM_ERROR"]) > 0) ShowError($arResult["FORM_ERROR"]);
if (strlen($arResult["FORM_NOTE"]) > 0) ShowNote(GetMessage("NOTE_SUCCESS"));
?>
<div class="col-margin-top ta-center">
    <a href="<?=$arParams["NEW_URL"]?><?=$arParams["SEF_MODE"] != "Y" ? (strpos($arParams["NEW_URL"], "?") === false ? "?" : "&")."WEB_FORM_ID=".$arParams["WEB_FORM_ID"] : ""?>"><i class="icon icon-addcomment"></i><?=GetMessage("FORM_ADD")?></a>
</div>

<p>
<?=$arResult["pager"]?>
</p>
    <?
    if(count($arResult["arrResults"]) > 0)
    {
        ?>
        <div class="feedback-block">
        <?
        $j=0;
        foreach ($arResult["arrResults"] as $arRes)
        {
            $j++;
            ?>

            <?
            $arFIO = isset($arResult["arrAnswers"][$arRes["ID"]][$arResult['questionCode']['fio']])
                ? current($arResult["arrAnswers"][$arRes["ID"]][$arResult['questionCode']['fio']])
                : '';
            $arReviewText = isset($arResult["arrAnswers"][$arRes["ID"]][$arResult['questionCode']['review_text']])
                ? current($arResult["arrAnswers"][$arRes["ID"]][$arResult['questionCode']['review_text']])
                : '';
            ?>

                <div class="feedback-item white-content-box col-margin-top">
                    <div class="feedback-item-header">
                        <div class="fl-l">
                            <?= $arFIO['USER_TEXT'] ?>
                        </div>
                        <div class="fl-r fz14 text-light">
                            <?= ConvertDateTime($arRes['DATE_CREATE'], 'DD.MM.YYYY') ?>
                        </div>
                    </div> <!-- .feedback-item-header -->
                    <div class="feedback-item-content">
                        <?= $arReviewText['USER_TEXT'] ?>
                    </div>
                </div>
        <?
        } //foreach
        ?>
        </div>
    <?
    }
    ?>
<p><?=$arResult["pager"]?></p>
