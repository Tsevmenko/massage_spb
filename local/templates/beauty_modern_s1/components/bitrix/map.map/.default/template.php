<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? if (strlen($arResult["ERROR"]) > 0) { ?>
    <? ShowError($arResult["ERROR"]); ?>
<? } else { ?>
<?
$arResult["INTERFACE"]["main"] = str_replace("'collapsePanel'","'record':'".GetMessage('BX_MAP_RECORD')."','collapsePanel'",$arResult["INTERFACE"]["main"]);
$arResult["INTERFACE"]["main"] = str_replace("\"collapsePanel\"","'record':'".GetMessage('BX_MAP_RECORD')."',\"collapsePanel\"",$arResult["INTERFACE"]["main"]);
unset($arResult["SECTIONS"]);
unset($arResult["ELEMENTS"]);
unset($arResult["SESSION_PARAMS"]);
if(CModule::IncludeModule("altasib.geoip"))
{
  $arData = ALX_GeoIP::GetAddr();

  if($arData["lat"] == "" || $arData["lng"] == "")
  {
    $arData["lat"] = 55.75237259;
      $arData["lng"] = 37.62177210;
  }
}
?>
<script src="https://tsevmenko.com<?=SITE_TEMPLATE_PATH?>/components/bitrix/map.map/.default/js/common.js"></script>
<script>

  $GeoMapp.extend({
    createItemContent: function (options) {
      var $ = this.$,
        $Temp = this.get('$Temp'),
        $Common = this.get('$Common'),
        content = $Common.createElement('*div', 'bxmap-item-wrapper'),
        photo = '',
        result = [
          'photo',
          'name',
          'url',
          'address',
          'link',
          'phone',
          'opening'
        ];
      var url = options.correspondence.url;

      result.forEach(function (name) {
        var realName = options.correspondence[name],
          field = options.fields[realName],
          elem;

        if (options.data[realName]) {
          switch (name) {
            case 'name':
              if (url) {
                elem = $('<bxmap class="alt-definition-title bxmap-item-name">').append($('<a>', {
                  href: options.data[url]
                }).text(options.data[realName]));
              } else {
                elem = $('<bxmap class="alt-definition-title bxmap-item-name">').append($('<strong>').text(options.data[realName]));
              }

              break;
            case 'url':
              break;
            case 'address':
              content.append($Temp.createRouteLink());
            case 'opening':
              elem = create(field, options.data[realName]);
              break;
            case 'photo':
              photo = options.data[realName];
              break;
            case 'link':
              elem = create(field, $Temp.createLinks(options.data[realName]));
              break;
            case 'phone':
              elem = create(field, $Temp.createPhones(options.data[realName]));
              break;
          }
        }

        if (elem) {
          content.append(elem);
        }
      });
      if (url) {
        content.append($('<bxmap class="alt-block map-item-toregister">').append($('<a>', {
          href: options.data[url],
          class: "btn btn-small"
        }).text(this.get('interfaceText').record)));
      }

      return {
        photo: photo,
        content: content
      };

      function create (field, value) {
        return $('<bxmap class="alt-definition-description bxmap-item-detail">').append(value);
      }
    }
  });
    $GeoMapp.init({
        device: {desktop: 'map.js'},
    mapBounds: <?=$arResult["PARAMS"]["BOUNDS"]?>,
        ajax: '<?=$arParams["AJAX_PATH"]?>',
        <? if (!empty($arParams["OVERLAY_TYPE"])) { ?>overlayType: <?=$arParams["OVERLAY_TYPE"]?>,<? } ?>
    defaultPath: {
      libs: '<?=$this->__folder?>/js/',
      images: '<?=$this->__folder?>/images/'
    },
        mapType: '<?=$arParams["MAP_TYPE"]?>',
        pageType: '<?=$arParams["DATA_TYPE"]?>',
        loadTime: <?=$arResult["PARAMS"]["LOAD_TIME"]?>,
        responseTime: <?=$arResult["PARAMS"]["RESPONSE_TIME"]?>,
        universalMarker: <?=$arParams["UNIVERSAL_MARKER"] == "Y" ? "true" : "false"?>,
        noCatIcons: <?=$arParams["NO_CAT_ICONS"] == "Y" ? "true" : "false"?>,
        noCats: <?=$arParams["FULLSCREEN_SLIDE"] != "Y" ? ($arParams["NO_CATS"] == "Y" ? "true" : "false") : "false"?>,
        replaceRules: <?=$arParams["REPLACE_RULES"] != "N" ? "true" : "false"?>,
        height: <?=intval($arParams["MAP_HEIGHT"])?>,
        narrowWidth: <?=intval($arParams["MAP_NARROW_WIDTH"])?>,
        animationTime: <?=$arResult["PARAMS"]["ANIMATION_TIME"]?>,
        interfaceText: <?=$arResult["INTERFACE"]["main"]?>,
        routeMessages: <?=$arResult["INTERFACE"]["routes"]?>,
        parseMessages: <?=$arResult["INTERFACE"]["errors"]?>,
        <? foreach ($arResult["PARAMS"]["ICONS"] as $strVarName => $strData) { ?>
            <?=$strVarName?>: <?=$strData?>,
        <? } ?>
        <? if(!empty($arResult["JSON_FIELDS"])): ?>
        fields: <?=$arResult["JSON_FIELDS"]?>,
        <? endif; ?>
        cats: <?=$arResult["JSON_SECTIONS"]?>,
        <? if ($arParams["LOAD_ITEMS"]) { ?>
        items: <?=$arResult["JSON_ELEMENTS"]?>,
        <? } ?>
        routeType: <?=$arResult["PARAMS"]["ROUTE_TYPES"]?>,
        title: '<?= (!empty($arParams["TITLE_MAP"]) ? $arParams["TITLE_MAP"] : $arResult['NAME']) ?>',
        <? if (!empty($arParams["QUERY"])) { ?>
        query: '<?= $arParams["QUERY"] ?>',
        <? } ?>
        itemCustomView: <?=$arParams["CUSTOM_VIEW"] ? "true" : "false"?>
    });
    </script>

    <div id="bxMapContainer" class="bxmap-wrapper"></div>
<? } ?>
<script>
  var plmrk;
  $(function(){
    $GeoMapp.complete(function(){
      var map = $GeoMapp.getMap();

      if (navigator.geolocation) 
        navigator.geolocation.getCurrentPosition(geolocationSuccess, geolocationFailure);

      map.setZoom(10);

    });
    
    function geolocationSuccess(position) {
      var map = $GeoMapp.getMap();

      if(position.coords != null && position.coords.latitude != null && position.coords.longitude != null)
      {
        map.setCenter([position.coords.latitude, position.coords.longitude]);
        var myPlacemark = new ymaps.Placemark([position.coords.latitude, position.coords.longitude], { balloonContent: 'Мое местоположение' });
        plmrk = myPlacemark;
        map.geoObjects.add(myPlacemark);
      }
      else
      {
        map.setCenter([<?=$arData["lat"]?>,<?=$arData["lng"]?>]);
        var myPlacemark = new ymaps.Placemark([<?=$arData["lat"]?>,<?=$arData["lng"]?>], { balloonContent: 'Мое местоположение' });
        plmrk = myPlacemark;
        map.geoObjects.add(myPlacemark);
      }

    }

    function geolocationFailure(positionError) {
      var map = $GeoMapp.getMap();
      map.setCenter([<?=$arData["lat"]?>,<?=$arData["lng"]?>]);
      var myPlacemark = new ymaps.Placemark([<?=$arData["lat"]?>,<?=$arData["lng"]?>], { balloonContent: 'Мое местоположение' });
      plmrk = myPlacemark;
      map.geoObjects.add(myPlacemark);
    }

  });

</script>