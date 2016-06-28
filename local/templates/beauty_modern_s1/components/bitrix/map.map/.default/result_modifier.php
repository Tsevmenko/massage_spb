<?
if ($arParams["MAP_TYPE"] != "yandex" && $arParams["MAP_TYPE"] != "google")
{
    $arParams["MAP_TYPE"] = "google";
}
if ($arParams["DATA_TYPE"] != "events" && $arParams["DATA_TYPE"] != "routes")
{
    $arParams["DATA_TYPE"] = "objects";
}
$arParams["MAP_HEIGHT"] = IntVal($arParams["MAP_HEIGHT"]);
if ($arParams["MAP_HEIGHT"] < 270)
{
    $arParams["MAP_HEIGHT"] = 270;
}
if ($arParams["MAP_HEIGHT"] > 2000)
{
    $arParams["MAP_HEIGHT"] = 2000;
}

$arResult["JSON_SECTIONS"] = array();
$arResult["JSON_ELEMENTS"] = array();
$now = time();
$arParams['DEPARTMENTS_IB_ID'] = intval (COption::GetOptionString("bitrix.sitemedicine", "dep_iblock_id", 0));
CModule::Includemodule('bitrix.sitemedicine');

switch ($arParams["DATA_TYPE"])
{
    default:
        foreach ($arResult["SECTIONS"] as $arSection)
        {
            $arResult["JSON_SECTIONS"]["s" . $arSection["ID"]] = array("name" => $arSection["NAME"]);

            if (strlen($arSection["PICTURE"]["SRC"]) > 0)
            {
                $arResult["JSON_SECTIONS"]["s" . $arSection["ID"]]["icon"] = $arSection["PICTURE"]["SRC"];
                $arResult["JSON_SECTIONS"]["s" . $arSection["ID"]]["pos"]  = 0;
				$arResult["JSON_SECTIONS"]["s" . $arSection["ID"]]["marker"]  = 0;
				$arResult["JSON_SECTIONS"]["s" . $arSection["ID"]]["count"]  = 0;
            }
            else
            {
                $arResult["JSON_SECTIONS"]["s" . $arSection["ID"]]["pos"] = (int)$arSection[$arParams["ICONPOS_PROP_CODE"]];
				$arResult["JSON_SECTIONS"]["s" . $arSection["ID"]]["marker"] = (int)$arSection[$arParams["ICONPOS_PROP_CODE"]];
				$arResult["JSON_SECTIONS"]["s" . $arSection["ID"]]["count"]  = 0;
            }
        }

        foreach ($arResult["ELEMENTS"] as $arElement)
        {
			$arElement["DETAIL_PAGE_URL"] = str_replace('&amp;','&',$arElement["DETAIL_PAGE_URL"]);
			if (!isset($arElement["PROPERTIES"][$arParams["LATITUDE_PROP_CODE"]]) || !isset($arElement["PROPERTIES"][$arParams["LONGITUDE_PROP_CODE"]]))
				continue;
            if (is_array($arElement["PREVIEW_PICTURE"]))
            {
                $arPhoto = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"]["ID"], array("width" => 300, "height" => 300), BX_RESIZE_IMAGE_PROPORTIONAL);
            }
            else
            {
                $arPhoto = false;
            }
			$haveDepartments = false;
			$arJsonDepartments = array();
			$arAddress = array();
			$arOtherDepartments = array();
			if ($arParams['DEPARTMENTS_IB_ID']>0) {
				$LAT = COption::GetOptionString("bitrix.sitemedicine", "prop_dep_lat", 0);
				$LON = COption::GetOptionString("bitrix.sitemedicine", "prop_dep_lon", 0);
				if (0!==$LAT && 0!==$LON) {
					$ADDRESS = COption::GetOptionString("bitrix.sitemedicine", "prop_dep_address", 0);
					$rsDepartment = CIBlockSection::GetList(
						array("left_margin"=>"asc"),
						array(
							'IBLOCK_ID'=>$arParams['DEPARTMENTS_IB_ID'],
							'UF_ORGANIZATION'=>$arElement['ID'],
							'ACTIVE' => 'Y',
						),
						false,
						array('ID','NAME','DEPTH_LEVEL','IBLOCK_SECTION_ID',$LAT,$LON,$ADDRESS,'PREVIEW_TEXT','UF_PHONE')
					);
					$parent = false;
					$parentDepth = 0;
					while ($arDepartment = $rsDepartment->GetNext()) {
						if ($parentDepth && $arDepartment['DEPTH_LEVEL']<=$parentDepth) {
							$parentDepth = 0;
							$parent = false;
						}
						if ((!$parentDepth && empty($arDepartment[$LAT]))) {
							$arOtherDepartments[] = $arDepartment["ID"];
							continue;
						}

						if (empty($arDepartment[$LAT])) {
							$arAddress[$arDepartment["ID"]] = '';
							if ($parent)
								$arJsonDepartments[$parent]['url'] .= ','.$arDepartment["ID"];
							continue;
						}

						$parentDepth = $arDepartment['DEPTH_LEVEL'];

						if (in_array($arDepartment[$LAT].$arDepartment[$LON],$arAddress))
							continue;
						else
							$arAddress[$arDepartment["ID"]] = $arDepartment[$LAT].$arDepartment[$LON];

						$haveDepartments = true;
						$parent = "e" . $arDepartment["ID"].'d';
						$arResult["JSON_SECTIONS"]["s" . $arElement["IBLOCK_SECTION_ID"]]["count"]++;
						$arJsonDepartments[$parent] = array(
							"cat" => "s" . $arElement["IBLOCK_SECTION_ID"],
							"item" => "e" . $arElement["ID"],
							"name" => $arDepartment["NAME"],
							"ADRESS" => $arDepartment[$ADDRESS],
							"PHONE" => $arDepartment["UF_PHONE"],
							"opening" => $arElement["PROPERTIES"][$arParams["OPENING_PROP_CODE"]]["VALUE"],
							"url" => $arElement["DETAIL_PAGE_URL"].'&DEPARTMENT='.$arDepartment["ID"],
							"description" => $arDepartment["PREVIEW_TEXT"],
							"LAT" => (double)$arDepartment[$LAT],
							"LON" => (double)$arDepartment[$LON],
							"photo" => $arPhoto ? $arPhoto["src"] : ""
						);
					}
				}
			}
			$orgAddressKey = $arElement["PROPERTIES"][$arParams["LATITUDE_PROP_CODE"]]["VALUE"].$arElement["PROPERTIES"][$arParams["LONGITUDE_PROP_CODE"]]["VALUE"];
			$arResult["JSON_ELEMENTS"]["e" . $arElement["ID"]] = array(
				"cat" => "s" . $arElement["IBLOCK_SECTION_ID"],
				"name" => $arElement["NAME"],
				"ADRESS" => $haveDepartments?'':$arElement["PROPERTIES"][$arParams["ADDRESS_PROP_CODE"]]["VALUE"],
				"phone" => $arElement["PROPERTIES"][$arParams["PHONE_PROP_CODE"]]["VALUE"],
				"opening" => $arElement["PROPERTIES"][$arParams["OPENING_PROP_CODE"]]["VALUE"],
				"link" => $arElement["PROPERTIES"][$arParams["LINK_PROP_CODE"]]["VALUE"],
				"url" => $arElement["DETAIL_PAGE_URL"],
				"description" => $arElement["PREVIEW_TEXT"],
				"photo" => $arPhoto ? $arPhoto["src"] : ""
			);
			if (!$haveDepartments) {
				$arResult["JSON_ELEMENTS"]["e" . $arElement["ID"]]["url"] = $arElement["DETAIL_PAGE_URL"];
				$arResult["JSON_ELEMENTS"]["e" . $arElement["ID"]]["lat"] = (double)$arElement["PROPERTIES"][$arParams["LATITUDE_PROP_CODE"]]["VALUE"];
				$arResult["JSON_ELEMENTS"]["e" . $arElement["ID"]]["lng"] = (double)$arElement["PROPERTIES"][$arParams["LONGITUDE_PROP_CODE"]]["VALUE"];
			}
			if ($haveDepartments && !in_array($orgAddressKey,$arAddress) && !empty($arOtherDepartments)) {
				$arResult["JSON_SECTIONS"]["s" . $arElement["IBLOCK_SECTION_ID"]]["count"]++;
				$arResult["JSON_ELEMENTS"]["e" . $arElement["ID"].'ed'] = array(
					"cat" =>  "s" . $arElement["IBLOCK_SECTION_ID"],
					"item" => "e" . $arElement["ID"],
					"name" => $arElement["NAME"],
					"ADRESS" => $arElement["PROPERTIES"][$arParams["ADDRESS_PROP_CODE"]]["VALUE"],
					"url" => $arElement["DETAIL_PAGE_URL"].'&DEPARTMENT='.implode(',',$arOtherDepartments),
					"LAT" => (double)$arElement["PROPERTIES"][$arParams["LATITUDE_PROP_CODE"]]["VALUE"],
					"LON" => (double)$arElement["PROPERTIES"][$arParams["LONGITUDE_PROP_CODE"]]["VALUE"],
					"photo" => $arPhoto ? $arPhoto["src"] : ""
				);
			}
			$arResult["JSON_ELEMENTS"] = array_merge($arResult["JSON_ELEMENTS"],$arJsonDepartments);
        }
        break;
}

CModule::IncludeModule("iblock");

$arResult["JSON_SECTIONS"] = array();
$arResult["JSON_ELEMENTS"] = array();

$sections = array();

$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTIES_*");
$arFilter = Array("IBLOCK_ID"=>28);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
$i = 1;
while($ob = $res->GetNextElement())
{
  $arFields = $ob->GetFields();
  $arProperties = $ob->GetProperties();
  $section = array("name" => $arFields["NAME"], "pos" => 0, "marker" => 0, "count" => count($arProperties["MASSAGIST"]["VALUE"]));
  if($arProperties["MASSAGIST"]["VALUE"] != "")
  	$arResult["JSON_SECTIONS"]["s" . $arFields["ID"]] = $section;
  $sections[$arFields["ID"]] = $arProperties["MASSAGIST"]["VALUE"];
}
$users = array();

foreach($sections as $k => $v)
  foreach($v as $kk => $vv)
    $users[] = $vv;
$users = array_unique($users);

global $USER;
$arParams["SELECT"] = array("UF_COORDS");
$arParams["FIELDS"] = array("ID", "NAME", "EMAIL", "PERSONAL_PHONE", "PERSONAL_STREET", "PERSONAL_BIRTHDAY", "PERSONAL_PHOTO");

$filter = array("ID" => implode('|', $users));
$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter, $arParams);

$users = array();
while($user = $rsUsers->GetNext()){
  $users[$user["ID"]] = $user;
}

foreach($sections as $k => $v)
{
  $cat = "s" . $k;
  foreach($v as $kk => $vv)
  {
    $coords = explode(",", $users[$vv]["UF_COORDS"]);
    $image = CFile::ResizeImageGet($users[$vv]["PERSONAL_PHOTO"], array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_PROPORTIONAL, true);
    $element = array(
      "cat" => $cat,
      "name" => $users[$vv]["NAME"] . " - " . $arResult["JSON_SECTIONS"][$cat]["name"],
      "ADRESS" => $users[$vv]["PERSONAL_STREET"],
      "url" => "?STEP=service&massagist=" . $users[$vv]["ID"] . "&service=" .$k,
      "LAT" => trim($coords[0]),
      "LON" => trim($coords[1]),
      "photo" => $image['src']
    );
    $arResult["JSON_ELEMENTS"]["el_" . $vv . '_' . $k] = $element;
  }
}
echo "<pre>";
//print_r($arResult["JSON_SECTIONS"]);
//print_r($arResult["JSON_ELEMENTS"]);
echo "</pre>";

if (empty($arResult["JSON_SECTIONS"])) {
	$arResult["JSON_SECTIONS"] = false;
} else {
	$arResult["JSON_SECTIONS"] = CDataTools::PhpToJSObject($arResult["JSON_SECTIONS"], true);
}
if (empty($arResult["JSON_ELEMENTS"])) {
	$arResult["JSON_ELEMENTS"] = false;
} else {
	$arResult["JSON_ELEMENTS"] = CDataTools::PhpToJSObject($arResult["JSON_ELEMENTS"], true);
}
if(!array_key_exists('ICONS',$arResult["PARAMS"]))
	$arResult["PARAMS"]["ICONS"] = array();

$arResult["JSON_FIELDS"] = !empty($arResult["JSON_FIELDS"]) ? Bitrix\Main\Web\Json::encode($arResult["JSON_FIELDS"], JSON_FORCE_OBJECT) : '';

?>