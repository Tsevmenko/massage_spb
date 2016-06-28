<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?require(dirname(__FILE__).'/header.php')?>

<?if (empty ($arResult['ScheduleEmployes'])) {
	ShowError(GetMessage('MCRWizard_NO_SCHEDULE'));
	return;
}?>


<?global $arDepartmentFilter,$arServicesFilter;
$arDepartmentFilter = array(
	'ID' => array_keys($arResult['ScheduleSpeciality']),
	'PROPERTY_ORGANIZATION'=>$arResult['VARIABLES']["COMPANY"],
);
if (empty($arDepartmentFilter['ID']))
	$arParams['SHOW_SPECIALITY_SELECTION'] = 'N';
$arServicesFilter = array(
	'ID' => $arResult['ScheduleServices'],
	'PROPERTY_not_for_record' => false,
	'PROPERTY_FOUNDATION' => array(false,$arResult['VARIABLES']["COMPANY"]),
);
$SpecialityCount = count ($arResult['ScheduleSpeciality']);
$SpecialityTemplate = '';
if ($SpecialityCount<10) {
	$SpecialityTemplate = $arParams['SHOW_SPECIALITY_TEMPLATE_0'];
}elseif ($SpecialityCount<10) {
	$SpecialityTemplate = $arParams['SHOW_SPECIALITY_TEMPLATE_10'];
}else {
	$SpecialityTemplate = $arParams['SHOW_SPECIALITY_TEMPLATE_20'];
}
if (empty($SpecialityTemplate)) $SpecialityTemplate= 'speciality_20';

?>
	<input id="SHOW_VIDEO" style="display: none" type="checkbox" <?=array_key_exists('VIDEO',$_REQUEST)?'checked':''?>>

<div class="tabs tabs-gray">
	<ul class="tabs-switchers">
		<?if (isset($arParams['SHOW_SPECIALITY_SELECTION'])&&$arParams['SHOW_SPECIALITY_SELECTION']=='Y'):?>
			<li class="active tabs-switcher"><span><?=GetMessage('MCRWizard_BY_SPECIALITY')?></span></li>
		<?endif;?>
		<li <?=(isset($arParams['SHOW_SPECIALITY_SELECTION'])&&$arParams['SHOW_SPECIALITY_SELECTION']=='Y')?'class="tabs-switcher"':'class="active tabs-switcher"'?>>
			<span><?=GetMessage('MCRWizard_BY_EMPLOYEE')?></span>
		</li>
		<?if (isset($arParams['SHOW_SERVICE_SELECTION'])&&$arParams['SHOW_SERVICE_SELECTION']=='Y'):?>
			<li class="tabs-switcher"><span><?=GetMessage('MCRWizard_BY_SERVICE')?></span></li>
		<?endif;?>
	</ul>

	<?if (isset($arParams['SHOW_SPECIALITY_SELECTION'])&&$arParams['SHOW_SPECIALITY_SELECTION']=='Y'):?>
	<div class="tabs-item active">
		<div class="content col-margin">

		<?$APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"$SpecialityTemplate",
			Array(
				"IBLOCK_TYPE"                     => 'foundations',
				"IBLOCK_ID"                       => $arParams['SPEC_IB_ID'],
				"NEWS_COUNT"                      => 100,
				"SORT_BY1"                        => "NAME",
				"SORT_ORDER1"                     => "ASC",
				"SORT_BY2"                        => "ID",
				"SORT_ORDER2"                     => "DESC",
				"FILTER_NAME"                     => "arDepartmentFilter",
				"ACTIVE_DATE_FORMAT"			  => '',
				"FIELD_CODE" => array(
					0 => "NAME",
				),
				"PROPERTY_CODE" => array(),
				'SET_TITLE' => 'N',
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"CACHE_FILTER" => "Y",
				"CACHE_GROUPS" => "Y",
				'COUNT_ELEMENTS' => 'N',
				'TOP_DEPTH' => 10,
				'ADD_SECTIONS_CHAIN' => 'N',
				'PAGER_SHOW_ALL' => 'N',
				"DISPLAY_TOP_PAGER"	=>	'N',
				"DISPLAY_BOTTOM_PAGER"	=>	'N',
				"PAGER_SHOW_ALWAYS"	=>	'N',
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"DISPLAY_DATE" => "N",
				"DISPLAY_NAME" => "Y",
				"DISPLAY_PICTURE" => "N",
				"DISPLAY_PREVIEW_TEXT" => "N",
				"WITH_VIDEO" => $arResult['VideoSpeciality'],
				'USER_INFO_BASE_LINK' => $APPLICATION->GetCurPageParam('STEP=service&SHOW=speciality',array('WEEK_START','STEP','SHOW','SPECIALITY','SERVICE','EMPLOYEE')),
			),
			$component,
			Array("HIDE_ICONS"=>"Y")
		);?>
		</div>
	</div>
	<?endif;?>
	<div class="tabs-item <?=!isset($arParams['SHOW_SPECIALITY_SELECTION'])||$arParams['SHOW_SPECIALITY_SELECTION']!='Y'?'active':''?>">
		<div class="content">
			<?include_once(dirname(__FILE__).'/employes_tab.php');?>
		</div>
	</div>

	<?if (isset($arParams['SHOW_SERVICE_SELECTION'])&&$arParams['SHOW_SERVICE_SELECTION']=='Y'):?>
		<div class="tabs-item">
			<div class="content">
			<?
			$arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID"], $arParams["PRICE_CODE"]);
			$arResult['PRICES_ALLOW'] = CIBlockPriceTools::GetAllowCatalogPrices($arResult["PRICES"]);
			$bIBlockCatalog = false;
			$arCatalog = false;
			$boolNeedCatalogCache = false;
			$bCatalog = \Bitrix\Main\Loader::includeModule('catalog');
			if ($bCatalog) {
				$arResultModules['catalog'] = true;
				$arCatalog = CCatalogSKU::GetInfoByIBlock($arParams["IBLOCK_ID"]);
				if (!empty($arCatalog) && is_array($arCatalog)) {
					$bIBlockCatalog = $arCatalog['CATALOG_TYPE'] != CCatalogSKU::TYPE_PRODUCT;
					$boolNeedCatalogCache = true;
				}
			}
			$arSelect = array('NAME');
			foreach($arResult["PRICES"] as &$value)
			{
				if (!$value['CAN_VIEW'] && !$value['CAN_BUY'])
					continue;
				$arSelect[] = $value["SELECT"];
			}
			$arPriceTypeID = array();
			?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"services_list",
				Array(
					"IBLOCK_TYPE"                     => 'medservices',
					"IBLOCK_ID"                       => $arParams['IBLOCK_ID'],
					"NEWS_COUNT"                      => 2000,
					"SORT_BY1"                        => "SORT",
					"SORT_ORDER1"                     => "ASC",
					"SORT_BY2"                        => "NAME",
					"SORT_ORDER2"                     => "ASC",
					"FILTER_NAME"                     => "arServicesFilter",
					"ACTIVE_DATE_FORMAT"			  => '',
					"FIELD_CODE" => $arSelect,
					"PROPERTY_CODE" => array(),
					'SET_TITLE' => 'N',
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "36000000",
					"CACHE_FILTER" => "Y",
					"PRICE_CODE" => $arParams["PRICE_CODE"],
					"PRICE_IB_ID" => $arParams['PRICE_IB_ID'],
					"PRICE_VAT_INCLUDE" => $arParams['PRICE_VAT_INCLUDE'],
					"CACHE_GROUPS" => "Y",
					'COUNT_ELEMENTS' => 'N',
					'ADD_SECTIONS_CHAIN' => 'N',
					'PAGER_SHOW_ALL' => 'N',
					"DISPLAY_TOP_PAGER"	=>	'N',
					"DISPLAY_BOTTOM_PAGER"	=>	'N',
					"PAGER_SHOW_ALWAYS"	=>	'N',
					'DETAIL_URL' => $APPLICATION->GetCurPageParam('STEP=service&SERVICE=#SERVICE_ID#',array('WEEK_START','STEP','SHOW','SPECIALITY','SERVICE','EMPLOYEE')),
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"DISPLAY_DATE" => "N",
					"DISPLAY_NAME" => "Y",
					"DISPLAY_PICTURE" => "N",
					"DISPLAY_PREVIEW_TEXT" => "N",
					"WITH_VIDEO" => $arResult['VideoServices'],
				),
				$component,
				Array("HIDE_ICONS"=>"Y")
			);?>
		</div>
	</div>
	<?endif;?>
</div>
