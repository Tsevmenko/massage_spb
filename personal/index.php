<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");

global $USER;
global $APPLICATION;

CModule::IncludeModule("iblock");

if(!$USER->IsAuthorized())
{
    header("Location: /login/");
    die();
}

$rsUser = CUser::GetByLogin($USER->GetLogin());

if($_REQUEST["action"] == "updateWorkTime")
{
  $APPLICATION->RestartBuffer();
  $user = new CUser;
  $fields = Array(
    "UF_WORKTIME_FROM" => $_REQUEST["wf"],
    "UF_WORKTIME_TO" => $_REQUEST["wt"],
    );
  $user->Update($USER->GetID(), $fields);

  die();
}

if($_REQUEST["action"] == "sendSMSPassword")
{
  $APPLICATION->RestartBuffer();
  
  $arFilter = Array("IBLOCK_ID"=>29, "PROPERTY_MASSAGIST"=>$USER->GetID(), "PROPERTY_SMS_CODE"=>72337, "!PROPERTY_STATUS"=>27);
  $res = CIBlockElement::GetList(Array(), $arFilter, false, false, array());
  while($ob = $res->GetNextElement())
  {
    $arFields = $ob->GetFields();
    $arProperties = $ob->GetProperties();
    CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array(87 => 27));
    
    // send money to massagist 

    // and log it
    $el = new CIBlockElement;
    
    $PROP = array();
    $PROP[88] = $USER->GetID();
    $PROP[89] = $arProperties["PRICE"]["VALUE"];
    $PROP[90] = $arFields["ID"];
    
    $arLoadProductArray = Array(
        "IBLOCK_ID"      => 31,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => date($USER->GetFullName() . "d.m.Y H:i:s"),
    );
    
    $el->Add($arLoadProductArray);
    
    break;
  }

  die();
}

if($arUser = $rsUser->Fetch())
{
    if($arUser["UF_TYPE"] == 8) header("Location: /personal/client/");

    if($_REQUEST["updateUser"] == "Y")
    {
      $APPLICATION->RestartBuffer();
      
      $fields = Array(); 
      
      foreach($_REQUEST["properties"] as $k => $v)
        $fields[$k] = $v;

      if($_FILES["PERSONAL_PHOTO"]["error"] == "")
      {
        $arFile = $_FILES['PERSONAL_PHOTO'];
        $arFile['del'] = "Y";           
        $arFile['old_file'] = $arUser["PERSONAL_PHOTO"]; 
        $fields["PERSONAL_PHOTO"] = $arFile;
      }

      $user = new CUser;
      if($user->Update($_REQUEST["userId"], $fields))
          echo "ok";
      else
          print_r($user->LAST_ERROR);

      die();
    }

    $arParams["SKIP_PROPERTIES"] = array("PERSONAL_PHOTO", "NAME", "EMAIL", "PERSONAL_STREET", "PERSONAL_CITY", "PERSONAL_PHONE", "UF_QUALIFICATION", "UF_COORDS");

    foreach($arUser as $k => $v)
    {
        if(in_array($k, $arParams["SKIP_PROPERTIES"]))
        {
            $rsData = CUserTypeEntity::GetList( array("sort"=>"asc"), array("ENTITY_ID" => "USER", "FIELD_NAME" => $k, "LANG" => "ru") );
            while($arRes = $rsData->Fetch()){ break; }
            
            if(!$arRes["EDIT_FORM_LABEL"] || $k == "UF_QUALIFICATION" || $k == "UF_CLIENT_CLASS")
            {
                $arRes["ENABLE"] = true;
                switch($k)
                {
                    case "NAME": $arRes["EDIT_FORM_LABEL"] = "Ваше полное имя"; break;
                    case "EMAIL": $arRes["EDIT_FORM_LABEL"] = "Email"; break;
                    case "PERSONAL_PHONE": $arRes["EDIT_FORM_LABEL"] = "Телефон"; break;
                    case "PERSONAL_STREET": $arRes["EDIT_FORM_LABEL"] = "Адрес"; break;
                    case "PERSONAL_PHOTO": $arRes["EDIT_FORM_LABEL"] = "Фотография"; break;
                    case "PERSONAL_BIRTHDAY": $arRes["EDIT_FORM_LABEL"] = "Дата рождения"; $arRes["ENABLE"] = false; break;
					case "PERSONAL_CITY": $arRes["EDIT_FORM_LABEL"] = "Город"; break;
                    default: /*$arRes["ENABLE"] = false;*/
                }
            }
            
            if($k == "UF_COORDS")
            {
              $coords = explode(",", $v);
              $arResult["COORDS"] = array("CODE" => $k, "VALUE" => array("lat" => trim($coords[0]), "lng" => trim($coords[1]))); 
            }

            if($k == "PERSONAL_PHOTO")
              $arResult["PHOTO"] = array("CODE" => $k, "VALUE" => $v, "NAME" => $arRes["EDIT_FORM_LABEL"], "ENABLE" => $arRes["ENABLE"]);
            else
                $arResult["USER_PROPERTIES"][] = array("CODE" => $k, "VALUE" => $v, "NAME" => $arRes["EDIT_FORM_LABEL"], "ENABLE" => $arRes["ENABLE"]);

            if($k == "EMAIL")
              $arResult["EMAIL"] = $v;
        }
    }
}
else
{
    $arResult["ERRORS"] = "Пользователь с ID " . $arUser["ID"] . " не найден.";
}

$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_*");
$arFilter = Array("IBLOCK_ID"=>3, "ACTIVE"=>"Y", "PROPERTY_USER" => $arUser["ID"]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement())
{
  $props = $ob->GetProperties();
  $pricelist = array();
  foreach($props["PRICELIST"]["VALUE"] as $k => $v)
  {
    $pricelist[] = array("NAME" => $v, "VALUE" => $props["PRICELIST"]["DESCRIPTION"][$k]);
  }
}

$arResult["PRICELIST"] = $pricelist;

if($arResult["COORDS"]["VALUE"]["lat"] != "" && $arResult["COORDS"]["VALUE"]["lng"] != "")
{
  $arData["lat"] = $arResult["COORDS"]["VALUE"]["lat"];
  $arData["lng"] = $arResult["COORDS"]["VALUE"]["lng"];
}
else
{
  if(CModule::IncludeModule("altasib.geoip"))
  {
    $arData = ALX_GeoIP::GetAddr();
  }
  if($arData["lat"] == "" && $arData["lng"] == "")
  {
    $arData["lat"] = 55.75237259;
    $arData["lng"] = 37.62177210;
  }
}

?>

<script src="<?=SITE_TEMPLATE_PATH?>/js/2.1-dev?lang=ru-RU&load=package.full" type="text/javascript"></script>

<div class="row">

  <div class="col-md-6">

	<div class="white-box p20 clearfix">
	<?
		CModule::IncludeModule("iblock");
		$arSelect = Array("ID", "NAME", "PREVIEW_PICTURE");
		$arFilter = Array("IBLOCK_ID"=>33, "ACTIVE"=>"Y");
		$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		while($ob = $res->GetNext())
			break;
	?>
		<img style="margin: 0 auto; display: block; max-width: 600px; max-height: 200px;" src="<?=CFile::GetPath($ob['PREVIEW_PICTURE'])?>"/>
	</div>

    <div class="services-list col-margin-top">
        <div id="sec_34" class="services-list-item">
          <div class="services-list-header active">Персональные данные</div>
          <div class="services-list-content white-content-box">
              <form enctype="multipart/form-data" id="update_user_form">
                  <p id="error_box" style="display: none;"></p>
                  <input type="hidden" id="userIdHolder" value="<?=$arUser['ID']?>"></td>

                  <div class="col-margin-bottom" style="float:right;margin-right: 50px;">
                      <div class="mb10">Выберите Ваше фото:</div>
                  
                      <?$file = CFile::ResizeImageGet($arResult['PHOTO']["VALUE"], array('width'=>250, 'height'=>250), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
                      <?if($file['src'] != ""):?>
                          <img src="<?=$file['src']?>"><br/>
                      <?endif;?>
                      <input type="file" name="PERSONAL_PHOTO">
                  </div>

                  <?foreach ($arResult["USER_PROPERTIES"] as $k => $v):?>
                      <?if($v["CODE"] == "UF_COORDS") continue;?>
                          <div class="col-margin-bottom">
                              <div class="mb10"><?=$v["NAME"]?>:</div>
                              <input class="input col-6 userProp" type="text" name="properties[<?=$v['CODE']?>]" data-code="<?=$v["CODE"]?>" value="<?=$v['VALUE']?>">
                          </div>
                  <?endforeach?>

                  <div class="center">
                      <div class="mb10">Выберите Ваше местоположение:</div>
                      <div id="YMapsIDgeopoint" style="width:100%; height:500px;"></div>  
                      <input type="text" name="properties[UF_COORDS]" style="display: none;" id="geopoint" name="geopoint" value="<?=$arData['lat']?>, <?=$arData['lng']?>" maxlength="80" size="65" class="validate[required,custom[inputgps]] text-input">    
                  </div>
                  
                  <button type="button" id="updateUser" class="btn btn-w-md btn-primary btn-squared">Обновить</button>
              </form>
          </div>
        </div>
        <div id="sec_35" class="services-list-item">
            <div class="services-list-header">Услуги</div>
            <div class="services-list-content white-content-box">
                <div class="panel-body">

                    <p class="small">Услуги, которые Вы можете предоставить и цены на них</p>
                        <?
                        global $arrFilter;
                        $arrFilter = Array("PROPERTY_MASSAGIST" => $USER->GetID());
                        $APPLICATION->IncludeComponent(
                        "bitrix:news.list", 
                        "services_in_personal_cabinet", 
                        array(
                          "ACTIVE_DATE_FORMAT" => "d.m.Y",
                          "ADD_SECTIONS_CHAIN" => "Y",
                          "AJAX_MODE" => "N",
                          "AJAX_OPTION_ADDITIONAL" => "",
                          "AJAX_OPTION_HISTORY" => "N",
                          "AJAX_OPTION_JUMP" => "N",
                          "AJAX_OPTION_STYLE" => "Y",
                          "CACHE_FILTER" => "N",
                          "CACHE_GROUPS" => "Y",
                          "CACHE_TIME" => "36000000",
                          "CACHE_TYPE" => "A",
                          "CHECK_DATES" => "Y",
                          "DETAIL_URL" => "",
                          "DISPLAY_BOTTOM_PAGER" => "Y",
                          "DISPLAY_DATE" => "Y",
                          "DISPLAY_NAME" => "Y",
                          "DISPLAY_PICTURE" => "Y",
                          "DISPLAY_PREVIEW_TEXT" => "Y",
                          "DISPLAY_TOP_PAGER" => "N",
                          "FIELD_CODE" => array(
                            0 => "NAME",
                            1 => "",
                          ),
                          "FILTER_NAME" => "arrFilter",
                          "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                          "IBLOCK_ID" => "28",
                          "IBLOCK_TYPE" => "massagist",
                          "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                          "INCLUDE_SUBSECTIONS" => "Y",
                          "MESSAGE_404" => "",
                          "NEWS_COUNT" => "9000000000",
                          "PAGER_BASE_LINK_ENABLE" => "N",
                          "PAGER_DESC_NUMBERING" => "N",
                          "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                          "PAGER_SHOW_ALL" => "N",
                          "PAGER_SHOW_ALWAYS" => "N",
                          "PAGER_TEMPLATE" => ".default",
                          "PAGER_TITLE" => "Новости",
                          "PARENT_SECTION" => "",
                          "PARENT_SECTION_CODE" => "",
                          "PREVIEW_TRUNCATE_LEN" => "",
                          "PROPERTY_CODE" => array(
                            0 => "PRICE",
                            1 => "",
                          ),
                          "SET_BROWSER_TITLE" => "Y",
                          "SET_LAST_MODIFIED" => "N",
                          "SET_META_DESCRIPTION" => "Y",
                          "SET_META_KEYWORDS" => "Y",
                          "SET_STATUS_404" => "N",
                          "SET_TITLE" => "Y",
                          "SHOW_404" => "N",
                          "SORT_BY1" => "ID",
                          "SORT_BY2" => "SORT",
                          "SORT_ORDER1" => "ASC",
                          "SORT_ORDER2" => "ASC",
                          "COMPONENT_TEMPLATE" => "services_in_personal_cabinet"
                        ),
                        false
                      );?>
                  </div>
            </div>
        </div> 
        <div id="sec_36" class="services-list-item">
          <div class="services-list-header">Время работы</div>
          <div class="services-list-content white-content-box">
              <div class="panel-body" style="float: left; margin-right: 50px;">

                <p class="small">Работаю с: </p>
                <select class="select1 input" name="empl_sel" id="work_time_from">
                  <?for ($i=5; $i < 23; $i++):?>
                    <option <?if($i == 5):?> selected <?endif;?> value="<?=$i?>"><?=$i?>:00</option>  
                  <?endfor?>
                </select>
                     
              </div>
              <div class="panel-body" style="float: left;">
                <p class="small">Работаю по: </p>
                <select class="select1 input" name="empl_sel" id="work_time_to">
                  <?for ($i=5; $i < 23; $i++):?>
                    <option <?if($i == 23):?> selected <?endif;?> value="<?=$i?>"><?=$i?>:00</option>  
                  <?endfor?>
                </select>
              </div>

              <button type="button" id="updateWorkTime" class="btn btn-w-md btn-primary btn-squared" style="margin-left: 50px;margin-top: 40px;">Обновить</button>
          </div>
        </div> 
        <div id="sec_37" class="services-list-item">
          <div class="services-list-header active">Форма ввода смс пароля</div>
          <div class="services-list-content white-content-box">
              <div class="panel-body">

                <div class="col-margin-bottom">
                    <div class="mb10">СМС пароль:</div>
                    <input class="input col-6" id="smsPassword" type="text" placeholder="85787">
                </div>

              <button type="button" id="sendSMSPassword" class="btn btn-w-md btn-primary btn-squared" >Отправить</button>
          </div>
        </div> 


    </div>


  </div>

</div>

<script>
  
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
      $('#geopoint').val( [coords[0].toPrecision(10), coords[1].toPrecision(10)].join(', ') );  
      
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

  $(function () {

    $("#new_ava").on("click", function(){ $("#new_ava_input").click(); return false; });

    $("#sendSMSPassword").on("click", function(){
      var sms = $("#smsPassword").val();
      if(sms != "" && parseInt(sms) > 0)
      {
        $.ajax({
          data: { sms: sms, action: "sendSMSPassword" }
        }).done(function(){
          window.location.reload();
        });
      }
    });

    $("#updateWorkTime").on("click", function(){
      var wf = $("#work_time_from").val();
      var wt = $("#work_time_to").val();
      if(parseInt(wf) >= parseInt(wt) || wf == "" || wt == "")
      {
        alert("Время работы ошибочно. Проверьте, пожалуйста, указанные данные.");
      }
      else
      {
        $.ajax({
          data: { action: "updateWorkTime", wf: wf, wt: wt, },
          success: function(){
            window.location.reload();
          },
          error: function(){
            alert("Ошибка во время обновления рабочего времени");
          }
        });
      }
    });

    $("#updateUser").on("click", function(){
      
      var props = [];
      $(".userProp").not(".disabled").each(function(i, el){
        props.push({
            code: $(el).data("code"), 
            val: $(el).val()
        });
      });

      var fd = new FormData(document.getElementById("update_user_form"));
      fd.append("userId", "<?=$arUser['ID']?>");
      fd.append("updateUser", "Y");

      $.ajax({
        type: "POST",
        data: fd,
        processData: false,
        contentType: false
      }).done(function(data){
        if(data == "ok")
        {
          window.location.reload();
        }
        else
        {
          if(data.length > 0)
          {
            $("#error_box").html(data);
            $("#error_box").css("display", "block");
          }
        }
      });
    });
  });
</script>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>