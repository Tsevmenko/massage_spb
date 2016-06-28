<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die(); ?>
<?
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
CUtil::InitJSCore();
?>
<!DOCTYPE html>
<html>
	<head>
        <title><?$APPLICATION->ShowTitle()?></title>
        <link rel="shortcut icon" type="image/x-icon" href="<?=SITE_TEMPLATE_PATH?>/favicon.ico" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<? $APPLICATION->ShowHead(); ?>
        <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/jquery.autocolumnlist.min.js')?>
        <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/jquery.cookie.min.js')?>
        <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/jquery.formstyler.min.js')?>
        <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/jquery.magnificpopup.min.js')?>
        <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/jquery.matchHeight-min.js')?>
        <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/jquery.owl.carousel.min.js')?>
        <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/jquery.sticky-kit.min.js')?>
        <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/jquery.tooltipster.min.js')?>
        <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/perfect-scrollbar.jquery.min.js')?>
        <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/special_version.js')?>
        <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/main.js')?>

		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/special_version.css", true);?>


    </head>
    <body>
        <? $APPLICATION->ShowPanel(); ?>
		<div class="body-wrapper clearfix">
            <div class="special-settings">
                <div class="container special-panel-container">
                    <div class="content">
                        <div class="aa-block aaFontsize">
                            <div class="fl-l"><?=Loc::getMessage('FONT_SIZE')?>:</div>
                            <a class="aaFontsize-small" data-aa-fontsize="small" href="#" title="<?=Loc::getMessage('FONT_SIZE_SMALL')?>"><?=Loc::getMessage('LETTER_A')?></a><!--
                            --><a class="aaFontsize-normal a-current" href="#" data-aa-fontsize="normal" title="<?=Loc::getMessage('FONT_SIZE_NORMAL')?>"><?=Loc::getMessage('LETTER_A')?></a><!--
                            --><a class="aaFontsize-big" data-aa-fontsize="big" href="#" title="<?=Loc::getMessage('FONT_SIZE_BIG')?>"><?=Loc::getMessage('LETTER_A')?></a>
                        </div>
                        <div class="aa-block aaColor">
                            <?=Loc::getMessage('FONT_COLOR')?>:
                            <a class="aaColor-black a-current" data-aa-color="black" href="#" title="<?=Loc::getMessage('FONT_COLOR_BLACK_ON_WHITE')?>"><span><?=Loc::getMessage('LETTER_C')?></span></a><!--
                            --><a class="aaColor-yellow" data-aa-color="yellow" href="#" title="<?=Loc::getMessage('FONT_COLOR_YELLOW_ON_BLACK')?>"><span><?=Loc::getMessage('LETTER_C')?></span></a><!--
                            --><a class="aaColor-blue" data-aa-color="blue" href="#" title="<?=Loc::getMessage('FONT_COLOR_BLUE_ON_LBLUE')?>"><span><?=Loc::getMessage('LETTER_C')?></span></a>
                        </div>

                        <div class="aa-block aaImage">
                            <?=Loc::getMessage('SWITCH_PICTURE')?>
                            <span class="aaImage-wrapper">
                                <a class="aaImage-on a-current" data-aa-image="on" href="#"><?=Loc::getMessage('SWITCH_PICTURE_ON')?></a><!--
                                --><a class="aaImage-off" data-aa-image="off" href="#"><?=Loc::getMessage('SWITCH_PICTURE_OFF')?></a>
                            </span>
                        </div>
                        <span class="aa-block"><a href="/?set-aa=normal" data-aa-off><?=Loc::getMessage('SWITCH_MAIN_VERSION')?></a></span>

                    </div>
                </div> <!-- .container special-panel-container -->
            </div> <!-- .special-settings -->
			<header>
				<div class="container container-top-line">
					<div class="content">
						<div class="col col-4">
                            <span class="aa-enable aa-hide" tabindex="1" data-aa-on><?=Loc::getMessage('LETTER_A')?><span><?=Loc::getMessage('LETTER_A')?></span></span>
						</div> <!-- .col col-4 -->
						<div class="col col-4 ta-center">
                            <a class="top-line-link" href="<?= SITE_DIR ?>schedule/record_wizard.php"><i class="icon icon-record"></i> <?=Loc::getMessage('MAKING_AN_APPOINTMENT')?></a>
						</div> <!-- .col col-4 ta-center -->
						<div class="col col-4 ta-right">
                            <?
                            $APPLICATION->IncludeComponent("bitrix:system.auth.form", "light",
                                array(
                                "REGISTER_URL" => SITE_DIR."auth/",
                                "PROFILE_URL" => SITE_DIR."personal/profile/",
                                "SHOW_ERRORS" => "Y"
                                ), false
                            );
                            ?>
                            
						</div> <!-- .col col-4 ta-right -->
					</div> <!-- .content -->
				</div> <!-- .container container-top-line -->

				<div class="container container-white col-padding">
					<div class="content">
                        <div class="col col-9">
							<a class="logo-link" href="<?=SITE_DIR?>" title="">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "PATH"           => SITE_DIR."includes/company_logo.php"),
                                false);?>
								<span class="logo-block">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
	"AREA_FILE_SHOW" => "file",
		"PATH" => SITE_DIR."includes/company_area.php"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "N"
	)
);?>
									<span class="h1"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
	"AREA_FILE_SHOW" => "file",
		"PATH" => SITE_DIR."includes/company_name.php"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "N"
	)
);?></span>
								</span>
							</a>
						</div> <!-- .col col-9 -->
						<div class="col col-3 ta-right">
							<div class="header-phone">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "PATH"           => SITE_DIR."includes/company_phone.php"),
                                false);?>
							</div>
                            <a href="<?=SITE_DIR?>contacts/"><?=Loc::getMessage('LINK_ADDRESS_PAGE')?></a>
						</div> <!-- .col col-3 ta-right -->
					</div> <!-- .content -->
				</div> <!-- .container container-white col-padding -->

				<div class="container container-top-navigation">
					<div class="content">
						<div class="col col-12">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "top",
                                array(
                                    "ROOT_MENU_TYPE" => "top",
                                    "MAX_LEVEL" => "2",
                                    "USE_EXT" => "N",
                                    "MENU_CACHE_TYPE" => "Y",
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "MENU_CACHE_GET_VARS" => array(
                                    ),
                                    "CHILD_MENU_TYPE" => "left",
                                    "DELAY" => "N",
                                    "ALLOW_MULTI_SELECT" => "N"
                                ),
                                false
                            );?>
						</div> <!-- .col col-12 -->
					</div> <!-- .content -->
				</div> <!-- .container container-top-navigation -->
			</header>

			<div class="container container-h1 container-white">
				<div class="content">
                    <?if ($APPLICATION->GetCurPage(true) !== SITE_DIR."index.php"):?>
                    <div class="col col-12">
                        <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "", Array());?>
                    </div>
                    <?endif;?>

                    <?$APPLICATION->ShowViewContent('bx_head_content');?>
                    <?=$APPLICATION->AddBufferContent('getMedSiteTitleHtml');?>
				</div> <!-- .content -->
			</div> <!-- .container container-h1 container-white -->


			<div class="container container-main">
				<div class="content">
					<div class="col col-12">
                        <?=$APPLICATION->AddBufferContent('getMedSiteWrapper', 'header');?>