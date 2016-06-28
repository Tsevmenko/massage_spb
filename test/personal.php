<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?>
<script src="http://api-maps.yandex.ru/2.1-dev/?lang=ru-RU&load=package.full" type="text/javascript"></script>

<?
  $arData["lat"] = "45.042149";
  $arData["lng"] = "38.980640";
?>

<div class="center">
  <script type="text/javascript">
  ymaps.ready(init);

  function init() {
    myMapGeo = new ymaps.Map('YMapsIDgeopoint', {
      center: ["<?=$arData['lat']?>", "<?=$arData['lng']?>"],
      zoom: 14,
      controls: ['zoomControl', 'searchControl', 'typeSelector', 'geolocationControl']
    });
    var myCollection = new ymaps.GeoObjectCollection();
    myMapGeo.events.add('click', function (e) {
      var coords = e.get('coords');
      $('#geopoint').val( [coords[0].toPrecision(6), coords[1].toPrecision(6)].join(', ') );  
      
      myCollection.removeAll();
      var myPlacemark = new ymaps.Placemark(coords); 
      myCollection.add(myPlacemark); 
      myMapGeo.geoObjects.add(myCollection);

    });
    myMapGeo.controls.add("zoomControl", { position: {top: 15, left: 15} });
    
    var myPlacemark = new ymaps.Placemark([<?=$arData["lat"]?>, <?=$arData["lng"]?>]); 
    myCollection.add(myPlacemark); 
    myMapGeo.geoObjects.add(myCollection);
  }
  </script>  
  <div id="YMapsIDgeopoint" style="width:100%; height:300px;"></div>  
  <input type="text" id="geopoint" name="geopoint" placeholder="GPS координаты" value="".$geopoint."" maxlength="80" size="65" class="validate[required,custom[inputgps]] text-input">    
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>