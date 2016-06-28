<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?require(dirname(__FILE__).'/header.php')?>

<?global $arServicesFilter;
$arServicesFilter = array(
	'ID' => $arResult['ScheduleServices'],
	'PROPERTY_not_for_record' => false,
);
?>
<input id="SHOW_VIDEO" style="display: none" type="checkbox" <?=array_key_exists('VIDEO',$_REQUEST)?'checked':''?>>

	<div class="tab-section clearfix">
	<div class="sticky-block">
		<ul class="tabs">
			<li><span><?=GetMessage('MCRWizard_BY_SERVICE')?></span></li>
		</ul>
	</div> <!-- .sticky-block -->

	<div class="tabs-content">

	<div class="box visible">
		<div class="all-services">
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
					"SORT_BY1"                        => "NAME",
					"SORT_ORDER1"                     => "ASC",
					"SORT_BY2"                        => "ID",
					"SORT_ORDER2"                     => "DESC",
					"FILTER_NAME"                     => "arServicesFilter",
					"SECTION_FIELDS"                  => array(0 => "NAME"),
					"PRICE_CODE" => $arParams["PRICE_CODE"],
					"PRICE_IB_ID" => $arParams['PRICE_IB_ID'],
					"PRICE_VAT_INCLUDE" => $arParams['PRICE_VAT_INCLUDE'],
					'SET_TITLE' => 'N',
					'COUNT_ELEMENTS' => 'N',
					'ADD_SECTIONS_CHAIN' => 'N',
					'PAGER_SHOW_ALL' => 'N',
					"DISPLAY_TOP_PAGER"	=>	'N',
					"DISPLAY_BOTTOM_PAGER"	=>	'N',
					"PAGER_SHOW_ALWAYS"	=>	'N',
					"FIELD_CODE"	=>	array('NAME'),
					'RECORD_WITHOUT_SERVICE' => GetMessage('RECORD_WITHOUT_SERVICE'),
					'DETAIL_URL' => $APPLICATION->GetCurPageParam('STEP=service&SERVICE=#SERVICE_ID#',array('STEP','SERVICE')),
					"WITH_VIDEO" => $arResult['VideoServices'],
				),
				$component
			);?>
		</div> <!-- .all-services -->
	</div>
	</div> <!-- .tabs-content -->

	</div> <!-- .tab-section -->
