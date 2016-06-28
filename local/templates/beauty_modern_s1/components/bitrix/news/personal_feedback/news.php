<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die(); ?>

<script>
$(document).ready(function(){
	$("#filter_requests").change(function() {
		window.location.href = '<?$APPLICATION->GetCurPage()?>'+'?filter='+$("#filter_requests option:selected").val();
	});
});
</script>
<?if (empty($arParams["FILTER_NAME"])) $arParams["FILTER_NAME"] = 'arrFilter'?>
<? if ($arParams["USE_FILTER"] == "Y"): ?>
	<?
	if ($_REQUEST['filter'] == "open") $GLOBALS[$arParams["FILTER_NAME"]]["DETAIL_TEXT"] = false;
	if ($_REQUEST['filter'] == "close") $GLOBALS[$arParams["FILTER_NAME"]]["!DETAIL_TEXT"] = false;
	?>

	<select name="114" id="filter_requests" class="styler" >
		<option value="all" <?if ($_REQUEST['filter'] == 'all'):?>selected<?endif;?>><?=GetMessage('FB_ALL')?></option>
		<option value="open" <?if ($_REQUEST['filter'] == 'open'):?>selected<?endif;?>><?=GetMessage('FB_OPENED')?></option>
		<option value="close" <?if ($_REQUEST['filter'] == 'close'):?>selected<?endif;?> ><?=GetMessage('FB_CLOSED')?></option>
	</select>
	<br/><br/>
<?endif?>

<?$GLOBALS[$arParams["FILTER_NAME"]]["CREATED_BY"] = $USER->GetID();?>

<?$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "personal_requests",
    Array(
        "IBLOCK_TYPE"                     => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID"                       => $arParams["IBLOCK_ID"],
        "NEWS_COUNT"                      => $arParams["NEWS_COUNT"],
        "SORT_BY1"                        => $arParams["SORT_BY1"],
        "SORT_ORDER1"                     => $arParams["SORT_ORDER1"],
        "SORT_BY2"                        => $arParams["SORT_BY2"],
        "SORT_ORDER2"                     => $arParams["SORT_ORDER2"],
        "FIELD_CODE"                      => $arParams["LIST_FIELD_CODE"],
        "PROPERTY_CODE"                   => $arParams["LIST_PROPERTY_CODE"],
        "DETAIL_URL"                      => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
        "SECTION_URL"                     => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
        "IBLOCK_URL"                      => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
        "DISPLAY_PANEL"                   => $arParams["DISPLAY_PANEL"],
        "SET_TITLE"                       => $arParams["SET_TITLE"],
        "SET_STATUS_404"                  => $arParams["SET_STATUS_404"],
        "INCLUDE_IBLOCK_INTO_CHAIN"       => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
        "CACHE_TYPE"                      => $arParams["CACHE_TYPE"],
        "CACHE_TIME"                      => $arParams["CACHE_TIME"],
        "CACHE_FILTER"                    => $arParams["CACHE_FILTER"],
        "CACHE_GROUPS"                    => $arParams["CACHE_GROUPS"],
        "DISPLAY_TOP_PAGER"               => $arParams["DISPLAY_TOP_PAGER"],
        "DISPLAY_BOTTOM_PAGER"            => $arParams["DISPLAY_BOTTOM_PAGER"],
        "PAGER_TITLE"                     => $arParams["PAGER_TITLE"],
        "PAGER_TEMPLATE"                  => $arParams["PAGER_TEMPLATE"],
        "PAGER_SHOW_ALWAYS"               => $arParams["PAGER_SHOW_ALWAYS"],
        "PAGER_DESC_NUMBERING"            => $arParams["PAGER_DESC_NUMBERING"],
        "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
        "PAGER_SHOW_ALL"                  => $arParams["PAGER_SHOW_ALL"],
        "DISPLAY_DATE"                    => $arParams["DISPLAY_DATE"],
        "DISPLAY_NAME"                    => "Y",
        "DISPLAY_PICTURE"                 => $arParams["DISPLAY_PICTURE"],
        "DISPLAY_PREVIEW_TEXT"            => $arParams["DISPLAY_PREVIEW_TEXT"],
        "PREVIEW_TRUNCATE_LEN"            => $arParams["PREVIEW_TRUNCATE_LEN"],
        "ACTIVE_DATE_FORMAT"              => $arParams["LIST_ACTIVE_DATE_FORMAT"],
        "USE_PERMISSIONS"                 => 'Y',
        "GROUP_PERMISSIONS"               => $arParams["GROUP_PERMISSIONS"],
        "FILTER_NAME"                     => $arParams["FILTER_NAME"],
        "HIDE_LINK_WHEN_NO_DETAIL"        => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],
		"CHECK_DATES" => "N",
    ),
    $component
);?>
