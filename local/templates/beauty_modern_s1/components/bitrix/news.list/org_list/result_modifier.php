<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CModule::IncludeModule("iblock");
if(isset ($_REQUEST['arrFilter_ff']['SECTION_ID'])) 
	$ids=intval($_REQUEST['arrFilter_ff']['SECTION_ID']); 
elseif(isset ($_GET['sid'])) 
	$ids=intval($_GET['sid']); 
else 
	$ids='';
$res = CIBlockSection::GetByID($ids);
if($ar_res = $res->GetNext())
	$arResult["SectionName"]=$ar_res['NAME'];
?>