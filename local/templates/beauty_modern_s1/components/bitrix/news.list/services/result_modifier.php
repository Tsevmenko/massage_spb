<?
	$arResult["TYPES"] = array();
	foreach($arResult["ITEMS"] as $k => $v)
	{
		// создали список разделов
		if($arResult["TYPES"][$v["PROPERTIES"]["SECTION"]["VALUE_ENUM_ID"]] == "")
		{
			if($v["PROPERTIES"]["SECTION"]["VALUE_ENUM_ID"] == "")
				$v["PROPERTIES"]["SECTION"]["VALUE_ENUM_ID"] = 0;

			$arResult["TYPES"][$v["PROPERTIES"]["SECTION"]["VALUE_ENUM_ID"]] = array("NAME" => $v["PROPERTIES"]["SECTION"]["VALUE"], "ITEMS" => array());
		}
		// добавили в раздел элемент
		$arResult["TYPES"][$v["PROPERTIES"]["SECTION"]["VALUE_ENUM_ID"]]["ITEMS"][] = $v;
	}
?>