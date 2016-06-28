<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
global $USER;
global $APPLICATION;

$rsUser = CUser::GetByLogin($USER->GetLogin());
$arUser = $rsUser->Fetch();

$rsMassagist = CUser::GetByID($_SESSION["massagist"]);
$asMassagist = $rsMassagist->Fetch();

if($_SESSION["service"] != "")
{
  $arFilter = Array("IBLOCK_ID"=>28, "ID"=>$_SESSION["service"]);
  $res = CIBlockElement::GetList(Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_PRICE_VALUE"), $arFilter, false, false, $arSelect);
  while($ob = $res->GetNextElement())
  {
    $service_price = $ob->GetFields();
    $service_name = $service_price["NAME"];
    $service_price = $ob->GetProperties();
    $service_price = $service_price["PRICE"]["VALUE"];
  }
}

else header("Location: /");

if($_REQUEST["book_massage"] == "Y")
{
  if($_SESSION["service"] != "")
  {
    // получаем цену услуги
    $sms_code = rand(10000, 99999);
    $service_price = 0;
    $arSelect = Array("ID", "NAME", "IBLOCK", "PROPERTY_PRICE");
    $arFilter = Array("IBLOCK_ID"=>28, "ID"=>$_SESSION["service"]);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement())
    {
      $service_price = $ob->GetFields();
      $service_name = $service_price["NAME"];
      $service_price = $service_price["PROPERTY_PRICE_VALUE"];
    }
    // создание заказа
    $el = new CIBlockElement;
    
    $PROP = array();
    $PROP[69] = $service_price; // price
    $PROP[70] = $_SESSION["service"]; // service
    $PROP[71] = $sms_code; // sms code
    $PROP[72] = $_SESSION["massagist"]; // massagist
	$PROP[81] = $_SESSION["time"]; // time
	$PROP[82] = $_SESSION["date"]; // date
	$PROP[83] = $_REQUEST["properties"]["NAME"]; // fio
	$PROP[84] = $_REQUEST["properties"]["PHONE"]; // phone
	$PROP[85] = $_REQUEST["properties"]["EMAIL"]; // email
	$PROP[86] = $USER->GetID(); // email

    $arLoadProductArray = Array(
      "IBLOCK_SECTION_ID" => false,
      "IBLOCK_ID"      => 29,
      "PROPERTY_VALUES"=> $PROP,
      "NAME"           => date("d-m-Y H:i:s") . $_REQUEST["properties"]["NAME"],
    );
    
    if(!$PRODUCT_ID = $el->Add($arLoadProductArray))
      $errors .= $el->LAST_ERROR;
    else
    {
      // отправка смс
      // отправка email по шаблону
      $arEventFields = array(
        "TO" => $_REQUEST["properties"]["EMAIL"],
        "CODE" => $sms_code
      );
      CEvent::SendImmediate("SEND_CODE_FOR_NEW_ORDER", "s1", $arEventFields);
      // очистка сессии, редирект
	  $_SESSION["confirmed_order"] = $PRODUCT_ID;
      unset($_SESSION["service"]);
      unset($_SESSION["massagist"]);
      header("Location: /order_confirm/");
      die();
    }
  }
  else
  {
    $errors .= "Не удалось сохранить услугу, которую Вы выбрали. Пожалуйста, повторите заказ еще раз.";
  }
}
?>

<div class="content">
  <div class="col col-4">
    <div class="h3">Данные записи</div>
    <div class="white-box record-tikket">
      <div class="date"></div>
      <div class="record-tikket-date clearfix">
        <div class="record-tikket-time"><?=$_SESSION["time"]?></div>
          <?=$_SESSION["date"]?></div>
          <hr>
          <div class="record-tikket-header">Услуга</div>
              <div class="record-tikket-text"><?=$service_name?></div>
              <p class="service"></p>
              <div class="record-tikket-header">Массажист</div>
              <div class="record-tikket-text"><?=$asMassagist["NAME"]?></div>
			<?if($service_price != ""):?>
              <div class="record-tikket-price clearfix">
                <div class="fl-l text-light">Стоимость</div>
                <div class="fl-r">
                  <span class="service-price"><?=$service_price?><span class="rub">р</span></span>
                </div>
              </div>
			<?endif;?>
          
          <!-- <div class="ta-center">
              <a href="/schedule/record_wizard.php?STEP=service&amp;SERVICE=195&amp;SHOW=speciality&amp;COMPANY=9&amp;cat=s2%2Cs1&amp;item=e19d&amp;DEPARTMENT=14%2C15&amp;SPECIALITY=318&amp;EMPLOYEE=26&amp;DATE=15.06.2016&amp;START=14%3A30&amp;END=30&amp;TTYPE=2" class="btn btn-outline btn-outline-bold">Изменить</a>
          </div> -->
    </div> 
  </div> 
  <div class="col col-8">
    <div class="h3">Контактные данные</div>
      <p id="error_box" <?if(strlen($errors) == 0):?>style="display: none;"<?endif;?>>
        <?=$errors?>
      </p>
      <?if($USER->IsAuthorized()):?>
        <input type="hidden" id="userIdHolder" value="<?=$arUser['ID']?>">
      <?endif;?>
      <div class="white-content-box">
        <form enctype="multipart/form-data" id="register">
          <input type="hidden" name="book_massage" value="Y">
          <input type="hidden" name="STEP" value="registration">
          <div class="col-margin-bottom">
              <div class="mb10">Эл. почта <span class="req">*</span></div>
              <div class="input-status">
                <input data-require-error="Введите адрес электронной почты" data-require="Y" data-isemail="Y" data-verification="Y" placeholder="name@example.com"
                  class="input col-6 mb20 userProp" id="personal_email" type="text" name="properties[EMAIL]" value="<?=$arUser['EMAIL']?>">
              </div>
          </div>
          <div class="col-margin-bottom">
            <div class="mb10">Телефон </div>
            <input data-verification="Y" placeholder="8 (000) 000-00-00" class="input col-4" type="text"
            name="properties[PERSONAL_PHONE]" data-code="PERSONAL_PHONE" value="<?=$arUser['PERSONAL_PHONE']?>">
          </div>
          <div class="col-margin-bottom">
            <div class="mb10">ФИО <span class="req">*</span></div>
            <div class="input-status">
              <input id="personal_name" type="text" name="properties[NAME]" data-code="NAME" value="<?=$arUser['NAME']?>"
                 data-require-error="Введите ФИО" data-require="Y" data-verification="Y" placeholder="ФИО" class="input col-6 mb20 userProp" type="text" >
            </div>
          </div>

          <div class="ta-center">
            <button type="button" id="booktime" class="btn btn-big">Записаться</button>
          </div>
        </form>
      </div>
  </div> <!-- .col col-8 -->
</div>

<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.maskedinput.min.js"></script>
<script type="text/javascript">

  $(function(){
    $("#personal_phone_mask").mask("+7(999) 999-99-99");
  });
  
  $("#booktime").on("click", function(){
    var pr = true;
    $("#register .userProp").each(function(){
      if($(this).val() == "") 
      {
        $(this).css("border", "solid 1px red");
        pr = false;
      }
      else 
        $(this).css("border", "solid 1px #b4b4b4");
    });
    
    if(pr)
    {
      $("#register").submit();
    }
    else
      return false;
  });
</script>