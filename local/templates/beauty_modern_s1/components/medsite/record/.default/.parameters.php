<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arSpecialityTemplate = array(
	'speciality_0' =>  GetMessage("SPECIALITY_TEMPLATE_0"),
	'speciality_10' =>  GetMessage("SPECIALITY_TEMPLATE_10"),
	'speciality_20' =>  GetMessage("SPECIALITY_TEMPLATE_20"),
);

$arTemplateParameters = array(
	"SHOW_SPECIALITY_SELECTION" => Array(
		"PARENT"  => "ADDITIONAL_SETTINGS",
		"NAME"    => GetMessage("SHOW_SPECIALITY_SELECTION"),
		"TYPE"    => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"SHOW_SPECIALITY_TEMPLATE_0" => Array(
		"PARENT"  => "ADDITIONAL_SETTINGS",
		"NAME"    => GetMessage("SHOW_SPECIALITY_TEMPLATE_0"),
		"TYPE"    => "LIST",
		"VALUES"  => $arSpecialityTemplate,
		"DEFAULT" => "speciality_0",
	),
	"SHOW_SPECIALITY_TEMPLATE_10" => Array(
		"PARENT"  => "ADDITIONAL_SETTINGS",
		"NAME"    => GetMessage("SHOW_SPECIALITY_TEMPLATE_10"),
		"TYPE"    => "LIST",
		"VALUES"  => $arSpecialityTemplate,
		"DEFAULT" => "speciality_10",
	),
	"SHOW_SPECIALITY_TEMPLATE_20" => Array(
		"PARENT"  => "ADDITIONAL_SETTINGS",
		"NAME"    => GetMessage("SHOW_SPECIALITY_TEMPLATE_20"),
		"TYPE"    => "LIST",
		"VALUES"  => $arSpecialityTemplate,
		"DEFAULT" => "speciality_20",
	),
	"SHOW_SERVICE_SELECTION" => Array(
		"PARENT"  => "ADDITIONAL_SETTINGS",
		"NAME"    => GetMessage("SHOW_SERVICE_SELECTION"),
		"TYPE"    => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"EMPLOYEE_PERSONAL_PAGE" => Array(
		"PARENT"  => "ADDITIONAL_SETTINGS",
		"NAME"    => GetMessage("EMPLOYEE_PERSONAL_PAGE"),
		"TYPE"    => "STRING",
		"DEFAULT" => "",
	),
);
?>