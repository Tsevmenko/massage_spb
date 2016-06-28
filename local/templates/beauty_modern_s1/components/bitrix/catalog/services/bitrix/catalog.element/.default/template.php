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
$this->setFrameMode(true);
$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PICT' => $strMainID.'_pict',
	'DISCOUNT_PICT_ID' => $strMainID.'_dsc_pict',
	'STICKER_ID' => $strMainID.'_sticker',
	'BIG_SLIDER_ID' => $strMainID.'_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID.'_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID.'_slider_cont',
	'SLIDER_LIST' => $strMainID.'_slider_list',
	'SLIDER_LEFT' => $strMainID.'_slider_left',
	'SLIDER_RIGHT' => $strMainID.'_slider_right',
	'OLD_PRICE' => $strMainID.'_old_price',
	'PRICE' => $strMainID.'_price',
	'DISCOUNT_PRICE' => $strMainID.'_price_discount',
	'SLIDER_CONT_OF_ID' => $strMainID.'_slider_cont_',
	'SLIDER_LIST_OF_ID' => $strMainID.'_slider_list_',
	'SLIDER_LEFT_OF_ID' => $strMainID.'_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID.'_slider_right_',
	'QUANTITY' => $strMainID.'_quantity',
	'QUANTITY_DOWN' => $strMainID.'_quant_down',
	'QUANTITY_UP' => $strMainID.'_quant_up',
	'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
	'QUANTITY_LIMIT' => $strMainID.'_quant_limit',
	'BUY_LINK' => $strMainID.'_buy_link',
	'ADD_BASKET_LINK' => $strMainID.'_add_basket_link',
	'COMPARE_LINK' => $strMainID.'_compare_link',
	'PROP' => $strMainID.'_prop_',
	'PROP_DIV' => $strMainID.'_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
	'OFFER_GROUP' => $strMainID.'_set_group_',
	'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['JS_OBJ'] = $strObName;
?>
<? if (!empty($arResult['PREVIEW_TEXT'])) { ?>
    <? if (is_array($arResult['PREVIEW_PICTURE'])) { ?>
    <div class="white-box col-margin-top">
        <div class="content">
            <div class="col col-3"><img src="<?= $arResult['PREVIEW_PICTURE']['src'] ?>" alt="<?= $arResult['NAME'] ?>"></div>
            <div class="col col-9">
                <div class="p20 pl0">
                    <?= ('html' == $arResult['PREVIEW_TEXT_TYPE'] ? $arResult['PREVIEW_TEXT'] : '<p>'.$arResult['PREVIEW_TEXT'].'</p>'); ?>
                </div>
            </div>
        </div>
    </div>
    <? } else { ?>
    <div class="white-content-box col-margin-top">
        <?= ('html' == $arResult['PREVIEW_TEXT_TYPE'] ? $arResult['PREVIEW_TEXT'] : '<p>'.$arResult['PREVIEW_TEXT'].'</p>'); ?>
    </div>
    <? } ?>
<? } ?>
<?  if ( !empty($arResult['DETAIL_TEXT'])
    || !empty($arResult['PROPERTIES']['ANALYSIS']['VALUE']['TEXT'])
    || !empty($arResult['PROPERTIES']['DOCUMENTS']['VALUE']['TEXT'])
    ) { ?>
<div class="white-box col-margin-top">
    <div class="tabs">
        <ul class="tabs-switchers">
            <?
            $tabActive = '';
            if (!empty($arResult['DETAIL_TEXT'])) {
                $tabActive = 'detail';
            ?>
            <li class="tabs-switcher active"><?= GetMessage('M_TAB_DESCRIPTION'); ?></li>
            <? } ?>
            <? if (!empty($arResult['PROPERTIES']['ANALYSIS']['VALUE']['TEXT'])) {
                if (empty($tabActive)) {
                    $tabActive = 'analysis';
                }
            ?>
            <li class="tabs-switcher<?= ($tabActive == 'analysis' ? ' active' : '') ?>"><?= GetMessage('M_TAB_ANALYSIS'); ?></li>
            <? } ?>
            <? if (!empty($arResult['PROPERTIES']['DOCUMENTS']['VALUE']['TEXT'])) {
                if (empty($tabActive)) {
                    $tabActive = 'documents';
                }
            ?>
            <li class="tabs-switcher<?= ($tabActive == 'documents' ? ' active' : '') ?>"><?= GetMessage('M_TAB_DOCUMENTS'); ?></li>
            <? } ?>
        </ul>
        <? if (!empty($arResult['DETAIL_TEXT'])) { ?>
        <div class="tabs-item active">
            <?= ('html' == $arResult['DETAIL_TEXT_TYPE'] ? $arResult['DETAIL_TEXT'] : '<p>'.$arResult['DETAIL_TEXT'].'</p>'); ?>
            <? if (is_array($arResult['DETAIL_PICTURE'])) { ?>
            <div class="photoalbum-cover"><img src="<?= $arResult['DETAIL_PICTURE']['src'] ?>" alt=""></div>
            <? } ?>
        </div><!-- .tabs-item active -->
        <? } ?>
        <? if (!empty($arResult['PROPERTIES']['ANALYSIS']['VALUE']['TEXT'])) { ?>
        <div class="tabs-item<?= ($tabActive == 'analysis' ? ' active' : '') ?>">
            <?= ('html' == $arResult['PROPERTIES']['ANALYSIS']['VALUE']['TYPE'] ? $arResult['PROPERTIES']['ANALYSIS']['~VALUE']['TEXT'] : '<p>'.$arResult['PROPERTIES']['ANALYSIS']['VALUE']['TEXT'].'</p>'); ?>
        </div><!-- .tabs-item active -->
        <? } ?>
        <? if (!empty($arResult['PROPERTIES']['DOCUMENTS']['VALUE']['TEXT'])) { ?>
        <div class="tabs-item<?= ($tabActive == 'documents' ? ' active' : '') ?>">
            <?= ('html' == $arResult['PROPERTIES']['DOCUMENTS']['VALUE']['TYPE'] ? $arResult['PROPERTIES']['DOCUMENTS']['~VALUE']['TEXT'] : '<p>'.$arResult['PROPERTIES']['DOCUMENTS']['VALUE']['TEXT'].'</p>'); ?>
        </div><!-- .tabs-item active -->
        <? } ?>
    </div> <!-- .tabs -->
</div>
<? } ?>

<? if (!empty($arResult['PROPERTIES']['AMBULATORY']['VALUE']['TEXT'])) { ?>
<h3 class="col-margin-top"><?=GetMessage('M_TITLE_AMBULATORY');?></h3>
<div class="white-box col-margin-top">
    <?= $arResult['PROPERTIES']['AMBULATORY']['~VALUE']['TEXT'] ?>
</div>
<? } ?>

<?  if (!empty($arResult['PROPERTIES']['INDICATIONS']['VALUE']['TEXT'])
    || !empty($arResult['PROPERTIES']['CONTRAINDICATIONS']['VALUE']['TEXT'])
    ) { ?>
<div class="white-box col-margin-top">
    <div class="tabs">
        <ul class="tabs-switchers">
            <?
            $tabActive = '';
            if (!empty($arResult['PROPERTIES']['INDICATIONS']['VALUE']['TEXT'])) {
                if (empty($tabActive)) {
                    $tabActive = 'indications';
                }
            ?>
            <li class="tabs-switcher<?= ($tabActive == 'indications' ? ' active' : '') ?>"><?= GetMessage('M_TAB_INDICATIONS'); ?></li>
            <? } ?>
            <? if (!empty($arResult['PROPERTIES']['CONTRAINDICATIONS']['VALUE']['TEXT'])) {
                if (empty($tabActive)) {
                    $tabActive = 'contraindications';
                }
            ?>
            <li class="tabs-switcher<?= ($tabActive == 'contraindications' ? ' active' : '') ?>"><?= GetMessage('M_TAB_CONTRAINDICATIONS'); ?></li>
            <? } ?>
        </ul>
        <? if (!empty($arResult['PROPERTIES']['INDICATIONS']['VALUE']['TEXT'])) { ?>
        <div class="tabs-item<?= ($tabActive == 'indications' ? ' active' : '') ?>">
            <?= ('html' == $arResult['PROPERTIES']['INDICATIONS']['VALUE']['TYPE'] ? $arResult['PROPERTIES']['INDICATIONS']['~VALUE']['TEXT'] : '<p>'.$arResult['PROPERTIES']['INDICATIONS']['VALUE']['TEXT'].'</p>'); ?>
        </div><!-- .tabs-item active -->
        <? } ?>
        <? if (!empty($arResult['PROPERTIES']['CONTRAINDICATIONS']['VALUE']['TEXT'])) { ?>
        <div class="tabs-item<?= ($tabActive == 'contraindications' ? ' active' : '') ?>">
            <?= ('html' == $arResult['PROPERTIES']['CONTRAINDICATIONS']['VALUE']['TYPE'] ? $arResult['PROPERTIES']['CONTRAINDICATIONS']['~VALUE']['TEXT'] : '<p>'.$arResult['PROPERTIES']['CONTRAINDICATIONS']['VALUE']['TEXT'].'</p>'); ?>
        </div><!-- .tabs-item active -->
        <? } ?>
    </div>
</div> <!-- .white-box col-margin-top -->
<? } ?>

<? if(is_array($arResult['SERVICE_EMPLOYES']) && count($arResult['SERVICE_EMPLOYES']) > 0) { ?>
<div class="clearfix col-margin-top">
	<h3 class="m0">
		<?= GetMessage('M_SERVICE_EMPLOYES'); ?>
        <?if (empty($arResult['PROPERTIES']['not_for_record']['VALUE'])) { ?>
		<div class="fl-r fz16">
            <a href="<?=$arParams['RECORD_WIZARD_LINK'].'?SERVICE='.$arResult['ID']?>" class="border-link"><?= GetMessage('M_BTN_ALL_DOCROTRS'); ?></a> <span class="badge"><?=count($arResult['SERVICE_EMPLOYES'])?></span>
		</div>
        <? } ?>
	</h3>
</div>
<div class="content mt10 doctors-list">
    <?foreach ($arResult['SERVICE_EMPLOYES'] as $arUser){ ?>
        <?
        $APPLICATION->IncludeComponent("medsite:medsite.system.person", "", Array(
                "USER"          => $arUser,
                "SCHEDULE_LINK" => $arParams['RECORD_WIZARD_LINK']."?SHOW=employee&EMPLOYEE=#ID#",
                "LIST_VIEW"     => "list",
                "USER_PROPERTY" => array(
                    0 => "WORK_POSITION",
                ),
                "SHOW_SERVICES" => "N",
                "USER_INFO_LINK" => empty($arResult['PROPERTIES']['not_for_record']['VALUE']) ? $arParams['RECORD_WIZARD_LINK'].'?SHOW=employee'.'&EMPLOYEE='.$arUser['ID'].'&SERVICE='.$arResult['ID'] : '',
                "REVIEW_FORM_ID" => $arParams["REVIEW_FORM_ID"],
                "REVIEW_URL" => $arParams["REVIEW_URL"]
            ),
            $component->GetParent()
        );
        ?>
    <? } ?>
</div>
<? } ?>