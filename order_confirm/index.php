<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Успешная оплата");

if($_SESSION["confirmed_order"] == "") header("Location: /");

CModule::IncludeModule("iblock");

$arFilter = Array("IBLOCK_ID"=>29, "ID"=>$_SESSION["confirmed_order"]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, array());
while($ob = $res->GetNextElement())
{
  $arFields = $ob->GetFields();
  $arProperties = $ob->GetProperties();
}

$rsMassagist = CUser::GetByID($arProperties["MASSAGIST"]["VALUE"]);
$asMassagist = $rsMassagist->Fetch();

$arFilter = Array("IBLOCK_ID"=>28, "ID" => $arProperties['SERVICE']["VALUE"]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

$arResult = array();

while($ob = $res->GetNextElement()){
	$service_name = $ob->GetFields();
	$service_name = $service_name["NAME"];
	$service_price = $ob->GetProperties();
	$service_price = $service_price["PRICE"]["VALUE"];
}
?>

<div class="content">
	<div class="col col-12 print-block">
		<div class="h3">Ваш талон №<?=$arFields["ID"]?></div>
	</div>
	<div class="col col-7 print-block">
		<div class="white-box record-tikket">

			<div class="content">
				<div class="col col-6">
					<div class="record-tikket-header">
						Дата приёма
					</div>
					<div class="record-tikket-text fz20"><?=$arProperties["DATE"]["VALUE"]?></div>
				</div>

				<div class="col col-6">
					<div class="record-tikket-header">
						Время приёма
					</div>
					<div class="record-tikket-text fz20"><?=$arProperties["TIME"]["VALUE"]?></div>
				</div>
			</div>
			<hr>

			<div class="record-tikket-header">
				Услуга
			</div>
			<div class="record-tikket-text"><?=$service_name?></div>

			<hr>

			<div class="record-tikket-header">
				Врач
			</div>
			<div class="record-tikket-text"><?=$asMassagist["NAME"]?></div>

			<hr>

			<div class="record-tikket-header">Адрес</div>
			<div class="record-tikket-text"><?=$asMassagist["PERSONAL_STREET"]?></div>

			<hr>

				<div class="record-tikket-text clearfix">
					<div class="fl-l text-light">Стоимость</div>
					<div class="fl-r">
						<b>
							<span class="service-price"><?=$service_price?><span class="rub">р</span></span>
						</b>
					</div>
				</div>

				<hr>

		</div> <!-- .white-box record-tikket -->
	</div> <!-- .col col-7 -->
	<?if($arProperties["CLIENT_NAME"]["VALUE"] != ""):?>
	<div class="col col-5 print-hide">
		<div class="white-box p30">
			<p class="mt0">Вы записались как:  <b><?=$arProperties["CLIENT_NAME"]["VALUE"]?></b></p>
		</div>
	</div>
	<?endif;?>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>