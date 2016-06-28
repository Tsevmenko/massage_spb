<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

if($_REQUEST["updateItems"] == "Y")
{ 

  CModule::IncludeModule("iblock");
  global $APPLICATION;
  global $USER;
  $APPLICATION->RestartBuffer();

  foreach ($_REQUEST["items"] as $k => $v) {
    $PROP = array();
    // получить список всех мастеров, которые оказывают даннус услугу и добавить текущего пользователя
    $service = array();
    $arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_*");
    $arFilter = Array("IBLOCK_ID"=>28, "ID"=>$_REQUEST["items"][$k]['id']);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement())
    {
     $service["fields"] = $ob->getFields();
     $service["properties"] = $ob->getProperties();
    }
    
    $service["properties"]["MASSAGIST"]["VALUE"][] = $USER->GetID();
    $service["properties"]["MASSAGIST"]["VALUE"] = array_unique($service["properties"]["MASSAGIST"]["VALUE"]);
    
    $PROP[68] = $service["properties"]["MASSAGIST"]["VALUE"];

    $arLoadProductArray = Array("ID" => $_REQUEST['items'][$k]['id'], "IBLOCK_ID" => 28, "IBLOCK_SECTION_ID" => false, "PROPERTY_VALUES"=> $PROP);
    $el = new CIBlockElement;
    if($_REQUEST['items'][$k]['id'] != "")
      $res = $el->Update($_REQUEST['items'][$k]['id'], $arLoadProductArray);
    else
      $PRODUCT_ID = $el->Add($arLoadProductArray);
  }
  die();
}
?>

  <form id="services_form" type="post">
    <input type="hidden" name="updateItems" value="Y">
    <div class="service_wrapper">
      <?
      CModule::IncludeModule("iblock");

      $sevices = array();

      $arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTIES_*");
      $arFilter = Array("IBLOCK_ID"=>28);
      $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
      while($ob = $res->GetNextElement())
      {
        $arFields = $ob->GetFields();
        $services[$arFields["ID"]] = array("NAME" => $arFields["NAME"], "ID" => $arFields["ID"]);
      }
      ?>

      <?foreach($arResult["ITEMS"] as $k => $v):?>
        <div class="col-margin-bottom service">
          <label>Выберите услугу:</label>
          <select class="select1 input userProp" name="items[<?=$k?>][id]">
            <option>Выберите услугу</option>
            <?foreach($services as $kk => $vv):?>
              <option style="padding:0 0 0 25px" <?if($vv['ID'] == $v["ID"]):?> selected <?endif;?> value="<?=$vv['ID']?>"><?=$services[$vv['ID']]["NAME"]?></option>
            <?endforeach;?>
          </select>

          <?/*Цена: <input class="input col-3 userProp" type="text" name="items[<?=$k?>][price]" value="<?=$v['PROPERTIES']['PRICE']['VALUE']?>">*/?>
        </div>
      <?endforeach;?>
    </div>
    <div class="col-margin-bottom blank" style="display: none;">
      <label>Выберите услугу:</label>
      <select class="select1 input blank_id">
        <option>Выберите услугу</option>
        <?foreach($services as $kk => $vv):?>
          <option style="padding:0 0 0 25px" value="<?=$vv['ID']?>"><?=$services[$vv['ID']]["NAME"]?></option>
        <?endforeach;?>
      </select>
      <?//Цена: <input class="input col-3 blank_price" type="text">*/?>
    </div>
    <button type="button" style="float: left;" class="btn btn-w-md btn-primary btn-squared add_service_line">Добавить услугу</button>
  </form>

  <button type="button" id="updateITEMS" class="btn btn-w-md btn-primary btn-squared">Обновить услуги</button>

  <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
  <?=$arResult["NAV_STRING"]?>
  <?endif;?>

  <script type="text/javascript">

  var k;

  if(parseInt("<?=$k?>") > 0) k = 1 + parseInt("<?=$k?>");
  else k = 1;

  $(".add_service_line").on("click", function(){
    var service = $(".blank").first().clone();

    $(service).find(".blank_id").attr("name", "items[" + k + "][id]");
    $(service).find(".blank_name").attr("name", "items[" + k + "][name]");
    $(service).find(".blank_price").attr("name", "items[" + k + "][price]");
    $(service).removeClass("blank");
    $(service).css("display", "block");

    k++;

    $(".service_wrapper").append(service);

    return false;
  });

  $("#updateITEMS").on("click", function(){
    var fd = new FormData(document.getElementById("services_form"));
    $.ajax({
      type: "POST",
      data: fd,
      processData: false,
      contentType: false
    }).done(function(data){
      window.location.reload();
    });
  });
  function removeParam(key, sourceURL) {
    var rtn = sourceURL.split("?")[0],
    param,
    params_arr = [],
    queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
      params_arr = queryString.split("&");
      for (var i = params_arr.length - 1; i >= 0; i -= 1) {
        param = params_arr[i].split("=")[0];
        if (param === key) {
          params_arr.splice(i, 1);
        }
      }
      rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
  }
  var routeData = window.location.origin + window.location.pathname + window.location.hash;
  history.pushState('', '', removeParam("items", routeData));
  </script>