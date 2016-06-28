<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?$APPLICATION->SetPageProperty('hideWrapper', true);?>

<?$APPLICATION->IncludeComponent(
	"medsite:talon",
	"",
	Array(
		'TALON_ID'          => $arResult['VARIABLES']['TALON'],
		"SHOW_PROPERTIES"   => array(
			0 => "EMPLOYEE",
			1 => "DEPARTAMENT",
			2 => "SERVICE",
			3 => "ORGANIZATION",
			4 => "PLACE",
			5 => "",
			6 => "",
		),
		'SHOW_SEARCH'       => 'N',
		'SHOW_ICONS'        => 'N',
		'SHOW_PRINTED'      => 'Y',
		'SHOW_PRINT_BUTTON' => 'Y',
		'BASKET_URL' => 'Y',
	),
	$component
);?>
