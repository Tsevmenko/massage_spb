<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
    "AJAX_PATH" => array(
        "NAME" => GetMessage("T_MAP_DESC_AJAX_PATH"),
        "TYPE" => "STRING",
        "DEFAULT" => "/bitrix/components/bitrix/map.map/ajax.php",
    ),
    "REPLACE_RULES" => array(
        "NAME" => GetMessage("T_MAP_DESC_REPLACE_RULES"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y",
    ),
    "MAP_HEIGHT" => array(
        "NAME" => GetMessage("T_MAP_DESC_MAP_HEIGHT"),
        "TYPE" => "STRING",
        "DEFAULT" => COption::GetOptionInt("bitrix.map", "def_map_height"),
    ),
    "MAP_NARROW_WIDTH" => array(
        "NAME" => GetMessage("T_MAP_DESC_MAP_NARROW_WIDTH"),
        "TYPE" => "STRING",
        "DEFAULT" => "900",
    ),
    "TITLE_MAP" => array(
        "NAME" => GetMessage("T_MAP_TITLE_MAP"),
        "TYPE" => "STRING",
        "DEFAULT" => "",
    ),
);

//if ($arCurrentValues["DATA_TYPE"] == "objects")
//{
    //Workaround. При сохранении снимаем у зависимых параметров активность
    if ($_POST["FULLSCREEN_SLIDE"] != "Y") {
        $_POST["FULLSCREEN_SHOW"] = "N";
        $_POST["FULLSCREEN_SLIDE"] = "N";
        $_POST["CUSTOM_VIEW"] = "N";
    }

    if ($arCurrentValues["FULLSCREEN_SLIDE"] != "Y")
    {
        $arTemplateParameters["NO_CATS"] = array(
            "NAME" => GetMessage("T_MAP_DESC_MAP_NO_CATS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
            "REFRESH" => "Y"
        );
    } else {
        $arTemplateParameters["NO_CATS"] = array(
            "NAME" => GetMessage("T_MAP_DESC_MAP_NO_CATS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
            "HIDDEN" => "Y"
        );
        $_POST["NO_CATS"] = $arCurrentValues["NO_CATS"] = "N";
    }

    if ($arCurrentValues["NO_CATS"] != "Y") {
        $arTemplateParameters["LOAD_ITEMS"] = array(
            "NAME" => GetMessage("T_MAP_DESC_MAP_LOAD_ITEMS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y"
        );

        $arTemplateParameters["FULLSCREEN_SLIDE"] = array(
            "NAME" => GetMessage("T_MAP_DESC_MAP_FULLSCREEN_SLIDE"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
            "REFRESH" => "Y"
        );
    }


    if ($arCurrentValues["FULLSCREEN_SLIDE"] == "Y")
    {
        $arTemplateParameters["FULLSCREEN_NOTES"] = array(
			"TYPE" => "CUSTOM",
			"JS_FILE" => "/bitrix/js/main/comp_props.js",
			"JS_EVENT" => "BxShowComponentNotes",
			"JS_DATA" => GetMessage("T_MAP_DESC_MAP_FULLSCREEN_NOTE"),
		);

        $arTemplateParameters["FULLSCREEN_SHOW"] = array(
            "NAME" => GetMessage("T_MAP_DESC_MAP_FULLSCREEN_SHOW"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
        );

        if ($arCurrentValues["FULLSCREEN_SLIDE"] == "Y") {
        $arTemplateParameters["CUSTOM_VIEW"] = array(
            "NAME" => GetMessage("T_MAP_CUSTOM_VIEW"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N"
        );
    }
}
//}
?>
