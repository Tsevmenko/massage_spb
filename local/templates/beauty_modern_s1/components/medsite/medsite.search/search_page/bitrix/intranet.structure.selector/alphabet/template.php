<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
if ($arResult['FILTER_VALUES'][$arParams['FILTER_NAME'].'_LAST_NAME']) {
    if ($key = array_search($arParams['FILTER_NAME'].'_LAST_NAME', $arResult['FILTER_PARAMS'], true)) {
        unset($arResult['FILTER_PARAMS'][$key]);
    }
}
$arParams['LIST_URL'] .= strpos($arParams['LIST_URL'], '?') === false ? '?' : '&';
$arExtraVars = array('current_view' => $arParams['CURRENT_VIEW'], 'current_filter' => $arParams['CURRENT_FILTER']);
$current_lang = '';
$bMultipleLang = count($arResult['ALPHABET']) > 1;
if ($bMultipleLang) {
    ?>
    <script>
        var bx_alph_current_lang = null;
        function BXToggleAlphabet(lang) {
            if (null != bx_alph_current_lang) {
                BX('bx_alphabet_' + bx_alph_current_lang).style.display = 'none';
                BX('bx_alph_select_' + bx_alph_current_lang).className = '';
            }
            bx_alph_current_lang = lang;
            BX('bx_alphabet_' + bx_alph_current_lang).style.display = 'inline';
            BX('bx_alph_select_' + bx_alph_current_lang).className = 'bx-current';
        }
    </script>
    <span id="bx_alphabet_selector">
<?
foreach ($arResult['ALPHABET'] as $lang => $mess) {
    ?><a href="javascript:void(0)" onclick="BXToggleAlphabet('<?= CUtil::JSEscape($lang) ?>')"
         id="bx_alph_select_<?= htmlspecialcharsbx($lang) ?>"><?= htmlspecialcharsbx($lang) ?></a>&nbsp;<?
}
?>
</span>
<?
}
foreach ($arResult['ALPHABET'] as $lang => $arMess) {
    ?>
    <span id="bx_alphabet_<?= htmlspecialcharsbx($lang) ?>"<?= $bMultipleLang ? ' style="display: none;"' : ''; ?>>
<a href="<?= $arParams['LIST_URL'] ?>set_filter_<?= $arParams['FILTER_NAME'] ?>=Y<?= GetFilterParams($arResult['FILTER_PARAMS'], true, $arExtraVars) ?>"><? echo $arMess['ISS_TPL_APLH_ALL'] ?></a>&nbsp;|
        <?
        $alph = $arMess['ISS_TPL_ALPH'];
        for ($i = 0; $i < strlen($alph); $i++) {
            $symbol = substr($alph, $i, 1);
            $bCurrent = $arResult['FILTER_VALUES'][$arParams['FILTER_NAME'].'_LAST_NAME'] == $symbol.'%';
            if ($bCurrent && !$current_lang)
                $current_lang = $lang;
            ?><a
            href="<?= $arParams['LIST_URL'] ?>set_filter_<?= $arParams['FILTER_NAME'] ?>=Y&<?= $arParams['FILTER_NAME'] ?>_LAST_NAME=<?= urlencode($symbol.'%') ?><?= GetFilterParams($arResult['FILTER_PARAMS'], true, $arExtraVars) ?>"><?= $bCurrent ? '<b>' : '' ?><?= $symbol ?><?= $bCurrent ? '</b>' : '' ?></a>&nbsp;<?
        }
        ?>
</span>
<?
}
if ($bMultipleLang) {
    if (!$current_lang) {
        $arLang = array_keys($arResult['ALPHABET']);
        $current_lang = $arLang[0];
    }
    ?>
    <script type="text/javascript">
        BXToggleAlphabet('<?=CUtil::JSEscape($current_lang)?>');
    </script>
<?
}
?>