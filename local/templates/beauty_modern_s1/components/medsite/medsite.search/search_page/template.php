<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
$this->setFrameMode(true);
?>
<div class="content col-margin-top">
<?
$arParams['CURRENT_VIEW'] = $current_view;
$arParams['CURRENT_FILTER'] = $current_filter;
$current_view = 'list';

?>

<script>
    var current_filter = '<?echo CUtil::JSEscape($current_filter)?>';
    var arFilters = ['simple', 'adv'];
    function BXSetFilter(new_current_filter) {
        if (current_filter == new_current_filter)
            return;
        for (var i = 0; i < arFilters.length; i++) {
            var obTabContent = document.getElementById('bx_users_filter_' + arFilters[i]);
            var obTab = document.getElementById('bx_users_selector_tab_' + arFilters[i]);
            if (null != obTabContent) {
                obTabContent.style.display = new_current_filter == arFilters[i] ? 'block' : 'none';
                current_filter = new_current_filter;
            }
            if (null != obTab) {
                obTab.className = new_current_filter == arFilters[i] ? 'bx-selected' : '';
            }
        }
    }
</script>

<? if ($arFilterValues[$arParams['FILTER_NAME'].'_LAST_NAME'] = '%') $arFilterValues[$arParams['FILTER_NAME'].'_LAST_NAME'] = '' ?>
<form action="<?= $APPLICATION->GetCurPage() ?>" method="post">
	<?// $APPLICATION->IncludeComponent("medsite:medsite.structure.selector", 'section_filter', $arParams, $component, array('HIDE_ICONS' => 'Y')); ?>
<?/*if(!empty($arResult['SPECIALTIES']) && false) { ?>
<div class="col col-3">
        <select  name="<?= $arParams['FILTER_NAME'] ?>_UF_SPECIALITY" onChange="this.form.submit();" class="styler input-block" data-placeholder="<?= GetMessage('S_ALL_SPECIALTIES'); ?>" data-search-placeholder="<?= GetMessage('S_SPECIALITY'); ?>">
            <option value=""><?= GetMessage('S_ALL_SPECIALTIES'); ?></option>
        <? foreach ($arResult['SPECIALTIES'] as $id => $spec){
            $selected = $_REQUEST[$arParams['FILTER_NAME']. '_UF_SPECIALITY'] == $id ? ' selected="selected"' : '';
        ?>
            <option<?=$selected?> value="<?= $id ?>"><?= $spec ?></option>
        <? } ?>
        </select>
</div>
<? } */?>
	<?// $APPLICATION->IncludeComponent("medsite:medsite.structure.selector", 'fio', $arParams, $component, array('HIDE_ICONS' => 'Y')); ?>
</form>
</div>
<?
if ($current_view == 'list' && $arParams['LIST_VIEW'] == 'group') {
    $arParams['SHOW_NAV_TOP'] = 'N';
    $arParams['SHOW_NAV_BOTTOM'] = 'N';
    $arParams['USERS_PER_PAGE'] = 0;
    $arParams['USER_PROPERTY'] = $arParams['USER_PROPERTY_GROUP'];
} else {
    $arParams['USER_PROPERTY'] = $arParams['USER_PROPERTY_LIST'];
}


$APPLICATION->IncludeComponent("medsite:medsite.structure.list", 'list', $arParams, $component, array('HIDE_ICONS' => 'Y'), $arParams['USER_SORT'], $arParams['SORT_TYPE']);
?>
