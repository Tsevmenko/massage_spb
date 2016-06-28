<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оставить отзыв");

CModule::IncludeModule("iblock");

if($_REQUEST["ID"] == "") header("Location: /personal/client/");

// получаем id массажиста из заказа
$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_MASSAGIST");
$arFilter = Array("IBLOCK_ID" => 29, "ID" => $_REQUEST["ID"]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
$massagist_id = -1;
while($ob = $res->GetNext())
{
  $massagist_id = $ob["PROPERTY_MASSAGIST_VALUE"];
  if($massagist_id == "") header("Location: /personal/client/");
  break;
}

// проверка, если пользователь уже оставлял комментарий, то редиректим его на список всех отзывов
$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_ORDER");
$arFilter = Array("IBLOCK_ID" => 32, "PROPERTY_ORDER" => $_REQUEST["ID"]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNext())
{
	header("Location: /employees/personal_info.php?employee=".$massagist_id);
	die();
}

$rsUser = CUser::GetByID($massagist_id);
$user = $rsUser->Fetch();
$user["PERSONAL_PHOTO"] = CFile::GetPath($user["PERSONAL_PHOTO"]);

if($_REQUEST["POST_FEEDBACK"] == "Y")
{
  global $USER;
  $el = new CIBlockElement;

  $PROP = array(94 => $_REQUEST["ID"]);

  $arLoadProductArray = Array(
      "IBLOCK_ID"      => 32,
      "PROPERTY_VALUES"=> $PROP,
      "NAME"           => $USER->GetFullName(),
      "PREVIEW_TEXT"   => $_REQUEST["form_textarea_6"]
  );
  
  if(!$PRODUCT_ID = $el->Add($arLoadProductArray))
    $errors .= $el->LAST_ERROR;

  header("Location: /employees/personal_info.php?employee=".$massagist_id);
  die();
}

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
    <div class="col col-12">
      <div class="doctor-menu col-margin-top">
        <ul>
          <!-- <li><a href="/employees/71/">Образование и опыт работы</a></li> -->
          <!-- <li class="text-muted">Расписание</li> -->
          <li><a class="active" href="/employees/personal_info.php?employee=<?=$ob["PROPERTY_MASSAGIST_VALUE"]?>">Отзывы</a> <!-- <span class="badge ml10">1</span> --></li>
          <!-- <li class="text-muted">Услуги</li> -->
        </ul>
      </div> <!-- .doctor-menu col-margin-top -->
    </div> <!-- .col col-12 -->
  </div> <!-- .content -->
</div>

<div class="container container-main" id="viewSection">
  <div class="content">
    <div class="col col-12">
      <div id="comp_faba8ebf54b1abdd4ed97205a3b228e4">
		<div class="col-margin-top ta-center">
          <a href="#" id="addFeedback"><i class="icon icon-addcomment"></i>Добавить</a>
		</div>

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
    </div>
  </div>
</div>

	<div class="white-content-box col-margin-top" id="giveFeedbackContainer" style="display: none;">
  <form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="POST_FEEDBACK" value="Y">
    <table>
      <tbody>
        <tr>
          <td>            
            <h3>Ваш отзыв о услуге</h3>
          </td>
        </tr>
      </tbody>
    </table>
    <br>
    <div class="col-margin-bottom">
      <div class="mb10">Ваш рейтинг</div>
      <?$APPLICATION->IncludeComponent(
        "bitrix:iblock.vote", 
        "stars", 
        array(
          "IBLOCK_ID" => "29",
          "ELEMENT_ID" => $_REQUEST["ID"],
          "MAX_VOTE" => "5",
          "COMPONENT_TEMPLATE" => "stars",
          "IBLOCK_TYPE" => "additional_info",
          "ELEMENT_CODE" => $_REQUEST["code"],
          "CACHE_TYPE" => "A",
          "CACHE_TIME" => "36000000",
          "VOTE_NAMES" => array(
            0 => "1",
            1 => "2",
            2 => "3",
            3 => "4",
            4 => "5",
            5 => "",
          ),
          "SET_STATUS_404" => "N",
          "MESSAGE_404" => "",
          "DISPLAY_AS_RATING" => "rating"
        ),
        $component
      );?>
    </div>
    <div class="col-margin-bottom">
      <div class="mb10">Ваш комментарий</div>
      <textarea name="form_textarea_6" name="text" cols="50" rows="5" class="inputtextarea"></textarea>
    </div>
    <div class="col-margin-bottom">
      <input class="btn btn-blue" type="submit" name="web_form_submit" value="Оставить отзыв">
    </div>
	<input type="hidden" value="<?=$_REQUEST["ID"]?>" name="ID" />
  </form>                         
</div>         

<script type="text/javascript">

  $(function(){
    $("#addFeedback").on("click", function(){
      $("#viewSection").css("display", "none");
      $("#giveFeedbackContainer").css("display", 'block');
      return false;
    });
  });

</script>           
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>