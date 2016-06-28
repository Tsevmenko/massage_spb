<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use \Bitrix\Main\Loader as ModuleLoader;

if (ModuleLoader::includeModule('form') && !empty($arParams["REVIEW_FORM_ID"])) {
    $arParams['~USER']['REVIEW'] = \Bitrix\SiteMedicine\FormTools::getReviewCount($arParams["REVIEW_FORM_ID"], $arParams['~USER']['ID']);
    $arParams['~USER']['REVIEW_URL'] = str_replace('#user_id#', $arParams['~USER']['ID'], $arParams['REVIEW_URL']);
}

$rsUser = CUser::GetByID($arParams["USER"]["ID"]);
if($arUser = $rsUser->Fetch())
{
	$arParams["USER"]["UF_QUALIFICATION"] = $arUser["UF_QUALIFICATION"];
	$arParams["USER"]["NAME"] = $arUser["NAME"];
	$arParams["USER"]["SECOND_NAME"] = $arUser["SECOND_NAME"];
	$arParams["USER"]["LAST_NAME"] = $arUser["LAST_NAME"];
	$arParams["USER"]["CITY"] = $arUser["PERSONAL_CITY"];
}

CModule::IncludeModule("iblock");

$arSelect = Array();
$arFilter = Array("IBLOCK_ID"=>29, "PROPERTY_MASSAGIST"=>$arParams["USER"]["ID"]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
$vote_sum = 0;
$vote_count = 0;
while($ob = $res->GetNextElement())
{
 	$fields = $ob->GetFields();
 	$props = $ob->GetProperties();

	$vote_sum = $vote_sum + intval($props["vote_sum"]["VALUE"]);
	$vote_count = $vote_count + intval($props["vote_count"]["VALUE"]);
}
$arParams["USER"]["RATE"] = $vote_sum/$vote_count;
if($arParams["USER"]["RATE"] == "") $arParams["USER"]["RATE"] = 0;