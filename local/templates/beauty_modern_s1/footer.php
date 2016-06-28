<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die(); ?>
<?
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
                        <?=$APPLICATION->AddBufferContent('getMedSiteWrapper', 'footer');?>
                    </div> <!-- .col col-12 -->
				</div> <!-- .content -->
			</div> <!-- .container container-main -->
		</div> <!-- .body-wrapper clearfix -->
		<div class="footer-wrapper">
			<div class="container container-primary-line col-padding col-margin-top">
				<div class="content">
					<div class="col col-12">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                            array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH"           => SITE_DIR."includes/callme.php"),
                        false);?>
					</div> <!-- .col col-12 -->
				</div> <!-- .content -->
			</div> <!-- .container container-primary-line col-padding col-margin-top -->
			<footer class="container container-footer">
				<div class="container container-white">
					<div class="content footer-menu-wrapper">
						<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", array(
	"ROOT_MENU_TYPE" => "bottom",
		"MAX_LEVEL" => "2",
		"USE_EXT" => "N",
		"MENU_CACHE_TYPE" => "Y",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => "",
		"CHILD_MENU_TYPE" => "left",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "N"
	)
);?>
						<div class="col col-4">
							<h4 class="footer-header"><?=Loc::getMessage('CONTACTS_TITLE')?></h4>
							<div class="footer-contacts">
                                <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "PATH"           => SITE_DIR."includes/contacts.php"),
                                false);?>
                                <? if($USER->IsAuthorized()): ?>
								<a href="/personal/requests/" class="text-light"><?=Loc::getMessage('FEEDBACK_TITLE')?></a>
                                <? endif; ?>
							</div>
							<div class="round-search mb20">
                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:search.form",
                                    "bottom",
                                    array(
                                        "COMPONENT_TEMPLATE" => "bottom",
                                        "PAGE" => SITE_DIR."search/index.php"
                                    ),
                                    false
                                );?>
							</div>
                            <div id="bx-composite-banner"><?/*Это место для композитного баннера*/?></div>


						</div> <!-- .col col-4 -->

						<div class="col col-12 ta-center col-margin">
							<hr class="col-margin-bottom">
                            <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH"           => SITE_DIR."includes/copyright.php"),
                            false);?>
						</div> <!-- .col col-12 ta-center col-margin -->
					</div> <!-- .content footer-menu-wrapper-->
				</div> <!-- .container container-white -->

				<div class="container">
					<div class="content">
						<div class="col col-12">
							<?$APPLICATION->IncludeComponent("bitrix:news.list", "partners", array(
	"IBLOCK_TYPE" => "additional_info",
		"IBLOCK_ID" => "24",
		"SET_TITLE" => "N",
		"COMPONENT_TEMPLATE" => "partners",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "DESC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "LINK",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "undefined",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => ""
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "N"
	)
);?>
						</div> <!-- .col col-12 -->
					</div> <!-- .content -->
				</div> <!-- .container -->
			</footer>
		</div> <!-- .footer-wrapper -->
<?$APPLICATION->IncludeComponent(
	"medsite:captcha.reload",
	"",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"FORM_NAME" => array(""),
		"USE_GLOBAL" => "Y"
	)
);?>
<?$APPLICATION->IncludeComponent("medsite:videocall", '', array(
	),
	false
);?>
	</body>
</html>