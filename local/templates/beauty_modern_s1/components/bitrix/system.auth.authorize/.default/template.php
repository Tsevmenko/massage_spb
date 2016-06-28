<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 

ShowMessage($arParams["~AUTH_RESULT"]); 

$APPLICATION->IncludeComponent( 
   "bitrix:main.register", 
   "reg", 
   Array(
      "USER_PROPERTY_NAME" => "", 
      "SEF_MODE" => "N", 
      "SHOW_FIELDS" => Array("NAME", "EMAIL", "PERSONAL_PHONE", "UF_TYPE"),
      "REQUIRED_FIELDS" => Array("NAME", "EMAIL", "PERSONAL_PHONE", "UF_TYPE"),
      "AUTH" => "Y", 
      "USE_BACKURL" => "Y", 
	  "SUCCESS_PAGE" => "/personal/", 
      "SET_TITLE" => "Y", 
      "USER_PROPERTY" => Array() 
   ) 
); 

/*?><p><a href="<?=$arResult["AUTH_AUTH_URL"]?>"><b><?=GetMessage("AUTH_AUTH")?></b></a></p><?*/

?>