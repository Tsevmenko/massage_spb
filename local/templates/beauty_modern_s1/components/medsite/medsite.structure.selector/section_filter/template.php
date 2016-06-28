<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
$this->setFrameMode(true);

if ($arResult['FILTER_VALUES'][$arParams['FILTER_NAME'].'_UF_DEPARTMENT']) {
    if ($arKeys = array_keys($arResult['FILTER_PARAMS'], $arParams['FILTER_NAME'].'_UF_DEPARTMENT')) {
        foreach ($arKeys as $key) {
            unset($arResult['FILTER_PARAMS'][$key]);
        }
    }
}


if (!function_exists('intr_SelectorTplGetSectionList')) {
    function intr_SelectorTplGetSectionList($ID, $arSections, $arParams, $arResult, $missLevel = null) {
        if (is_array($arSections[$ID])) {
            $cnt = 0;
            foreach ($arSections[$ID] as $key => $arSect) {
                $selected = '';
                if ($arResult['FILTER_VALUES'][$arParams['FILTER_NAME'].'_UF_DEPARTMENT'] == $arSect['ID'])
                    $selected = ' selected ';
                if ($arSect['DEPTH_LEVEL'] == $missLevel) {
                    intr_SelectorTplGetSectionList($arSect['ID'], $arSections, $arParams, $arResult, $missLevel);
                }
                else {
                    echo '<option'.$selected.' value="'.$arSect['ID'].'">';
                    echo htmlspecialcharsbx($arSect['NAME']);
                    intr_SelectorTplGetSectionList($arSect['ID'], $arSections, $arParams, $arResult, $missLevel);
                    echo '</option>';
                }
            }
        }
    }
}


?>
<div class="col col-4">
    <select name="<?= $arParams['FILTER_NAME'] ?>_UF_DEPARTMENT" onChange="this.form.submit();" class="styler input-block" data-placeholder="<?=  GetMessage('TPL_PLACEHOLDER_CLINIC_OPTION') ?>" data-search-placeholder="<?=  GetMessage('TPL_PLACEHOLDER_CLINIC') ?>">
        <option value=""><?= GetMessage(INTR_ABSC_TPL_CHECK_ALL) ?></option>
        <?
        intr_SelectorTplGetSectionList(0, $arResult['SECTIONS'], $arParams, $arResult, 0);
        ?>
    </select>
</div>