<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оставить отзыв");

CModule::IncludeModule("iblock");

if($_REQUEST["employee"] == "") header("Location: /employees/");

$rsUser = CUser::GetByID($_REQUEST["employee"]);
$user = $rsUser->Fetch();
$user["PERSONAL_PHOTO"] = CFile::GetPath($user["PERSONAL_PHOTO"]);
?>

<div class="container container-h1 container-white">
  <div class="content">
    <div class="col col-9">
      <div class="doctor-short-info">
        <div class="clearfix">
          <div class="doctor-photo" style="background-image: url('<?=$user["PERSONAL_PHOTO"]?>');"></div>
          <div class="ov-h">
            <h1><?=$user["LAST_NAME"]?> <?=$user["NAME"]?></h1>
            <div class="text-light"><?=$user["WORK_POSITION"]?></div>
          </div>
        </div>
      </div>
    </div>
  </div> <!-- .content -->
</div>

<div class="white-box col-margin-top">
  <div class="tabs">
    <ul class="tabs-switchers">
      <li class="tabs-switcher active">Отзывы</li>
      <li class="tabs-switcher">Образование</li>
	  <li class="tabs-switcher text-muted">Расписание</li>
	  <li class="tabs-switcher text-muted">Услуги</li>
    </ul>
    <div class="tabs-item active">

		<?
			// получить массив всех заказов массажиста
			$orders = array();
			$orders_id = array();
			$arSelect = Array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "CREATED_BY", "PROPERTY_CLIENT");
			$arFilter = Array("IBLOCK_ID"=>29, "PROPERTY_MASSAGIST"=>$_REQUEST["employee"]);
			$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
			while($ob = $res->GetNext())
			{
				$orders[$ob["ID"]] = $ob["PROPERTY_CLIENT_VALUE"];
				$orders_id[] = $ob["ID"];
			}
			// формируем массив отзывов
			$reviews = array();
			$arSelect = Array("ID", "NAME", "PREVIEW_TEXT", "IBLOCK_ID", "DATE_CREATE", "CREATED_BY", "PROPERTY_ORDER_VALUE");
			$arFilter = Array("IBLOCK_ID"=>32, "PROPERTY_ORDER"=>$orders_id);
			$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
			while($ob = $res->GetNextElement())
			{
				$fields = $ob->GetFields();
				$props = $ob->GetProperties();
			
				$user = CUser::GetByID($orders[$props["ORDER"]["VALUE"]]);
				$usr = $user->Fetch();
				$reviews[] = array(
					"NAME" => $usr["LAST_NAME"].' '.$usr["NAME"], 
					"DATE" => date("d.m.Y", strtotime($fields["DATE_CREATE"])),
					"PREVIEW_TEXT" => $fields["PREVIEW_TEXT"]);
			}

		?>

		<?foreach($reviews as $k => $v):?>
			<p></p>
			<div class="feedback-block">
			  <div class="feedback-item white-content-box col-margin-top">
				<div class="feedback-item-header">
				  <div class="fl-l"><?=$v['NAME']?></div>
				  <div class="fl-r fz14 text-light"><?=$v["DATE"]?></div>
				</div>
				<div class="feedback-item-content">
				  <?=$v["PREVIEW_TEXT"]?>
				</div>
			  </div>
			</div>
			<p></p>
		<?endforeach?>


    </div>
    <div class="tabs-item active" style="padding-bottom: 0px;">
      <?=$arResult["DETAIL_TEXT"]?>
    </div>

  </div> <!-- .tabs -->



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>