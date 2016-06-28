<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
  die(); ?>
<? require(dirname(__FILE__).'/header.php') ?>

<?
CModule::IncludeModule("iblock");

switch($_REQUEST["action"])
{
case "booktime":
  global $APPLICATION;
  $APPLICATION->RestartBuffer();
  $_SESSION["massagist"] = $_REQUEST["massagist"];
  $_SESSION["service"] = $_REQUEST["service"];
  $_SESSION["date"] = $_REQUEST["date"];
  $_SESSION["time"] = $_REQUEST["time"];
  die();
break;
default: break;
}

// init section
global $USER;
$rsUser = CUser::GetByLogin($USER->GetLogin());
$arUser = $rsUser->Fetch();

if(!$USER->IsAuthorized()) header("Location: /login/");

// retrieve from db all information for create schedule
$arSelect = Array("ID", "NAME", "IBLOCK_ID");
$arFilter = Array("IBLOCK_ID"=>30, "MASSAGIST" => $_REQUEST['massagist']);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

$arResult = array();

while($ob = $res->GetNextElement())
{
  $arFields = $ob->GetFields();
  $arProperties = $ob->GetProperties();

  $arResult[$arProperties["WEEK_DAY"]["VALUE"]][] = array(
    "ID" => $arFields["ID"],
    "WEEK_DAY" => $arProperties["WEEK_DAY"]["VALUE"],
    "TIME_FROM" => $arProperties["TIME_FROM"]["VALUE"],
    "TIME_TO" => $arProperties["TIME_TO"]["VALUE"],
    "ORDER_ID" => $arProperties["ORDER_ID"]["VALUE"]
  );
}


$week_days = array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресение");
$week_calendar_numbers = array();
$slice = -(date("w") - 1);

foreach($week_days as $k => $v)
{  
  $date = new DateTime();
  $date->modify($slice.' days');
  $week_calendar_numbers[] = $date->format('d');
  $slice++;
}

$week = array();

foreach ($week_days as $k => $v) 
{
  $week[$week_calendar_numbers[$k]] = $v;
}

$rsUser = CUser::GetByID($_REQUEST["massagist"]);
$arMassagistUser = $rsUser->Fetch();


$short_week_days = array("Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс");
$work_time_start = $arMassagistUser["UF_WORKTIME_FROM"];
$work_time_end = $arMassagistUser["UF_WORKTIME_TO"];

if($work_time_start == "") $work_time_start = 7;
if($work_time_end == "") $work_time_end = 23;

$js_events = "";
$page_event_ids = "";

$busy_time = array();

foreach($arResult as $k => $v):?>
  <?foreach($v as $kk => $vv):?>
    <?
      $day_num = array_search($k, $week);
      $format = "Y-m-" . $day_num;

      $df = date_create(date($format) . substr($vv["TIME_FROM"], strpos($vv["TIME_FROM"], " ")));
      $dt = date_create(date($format) . substr($vv["TIME_TO"], strpos($vv["TIME_TO"], " ")));

      $page_event_ids .= $vv["ID"] . ", ";

      $js_events .= "{
        title: 'Занятое время',
        start: '" . $df->format('Y-m-d H:i:s') . "',
        end: '" . $dt->format('Y-m-d H:i:s') . "',
        id: '" . $vv["ID"] . "_0',
        editable: false,
      },";


		if(strtotime($vv["TIME_FROM"]) > strtotime($vv["TIME_TO"]))
		{
			$timestamp = strtotime($vv["TIME_FROM"]) + 60*60;
			$vv["TIME_TO"] = date('Y.m.d H:i:s', $timestamp);
		}

      $busy_time[$day_num][] = array(
        "from" => substr($vv["TIME_FROM"], strpos($vv["TIME_FROM"], " ")),
        "to" => substr($vv["TIME_TO"], strpos($vv["TIME_TO"], " "))
      );
    ?>
  <?endforeach;?>
<?endforeach;?>

<?

  $arResult["free_time"] = array();

  echo "<pre>";
  print_r($week);
  echo "</pre>";

  $day_counter = 0;
  foreach($week as $k => $v)
  {
	for($i = $work_time_start; $i < $work_time_end; $i++)
	{
		$arResult["free_time"][$k][] = $i;
	}
	$day_counter++;
  }

  foreach ($busy_time as $k => $v) 
  {
    foreach ($v as $kk => $vv) 
    {
      $from = substr($vv["from"], 0, strpos($vv["from"], ":"));
      $to = substr($vv["to"], 0, strpos($vv["to"], ":"));

      if($from > $to)
        $to = $from + 2;

      $start_i = array_search($from, $arResult["free_time"][$k]);
      $end_i = array_search($to, $arResult["free_time"][$k]) - 1;

	  if($start_i != -1)
	  {
		if($start_i != -1 && $end_i == -1)
		{
			$end_i = count($arResult["free_time"]);
		}

		if($start_i == "" || $end_i == "") continue;

		for ($i=$start_i; $i <= $end_i; $i++)
		{
		  unset($arResult["free_time"][$k][$i]);
		}
	  }
    }
  }
?>

<?
$dates = array_keys($arResult["free_time"]);
?>

<div class="container container-main">
    <div class="content">
        <div class="col col-12">
            <div class="col-margin-top">
                <div class="schedule">
                    <div class="schedule-header-wrapper white-box sticky clearfix">
                        <div class="schedule-header">
                            <!-- <div class="fl-r">
                                <a id="NextWeek" href="/schedule/record_wizard.php?WEEK_START=20.06.2016&amp;STEP=service&amp;SHOW=speciality&amp;COMPANY=9&amp;cat=s1&amp;item=e20d&amp;DEPARTMENT=14%2C15&amp;SPECIALITY=318">Следующая неделя</a>
                            </div> -->
                            <div class="ta-center"><?=$dates[0]?> <?=date("m")?> — <?=$dates[count($dates) - 1]?> <?=date("m")?> 2016</div>
                        </div>
                        <div class="schedule-search col-4 mb0">
                            <form>
                                <div class="search-in-page-wrapper employee-search">
                                    <input class="input input-block input-search search-in-page-input" type="text" name="search-in-page" placeholder="Поиск врача">
                                    <button id="bx-btn-reset" class="search-in-page-reset" type="reset">
                                        <i class="icon icon-search-reset"></i>
                                    </button>
                                    <button class="search-in-page-btn"><i class="icon icon-search"></i></button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="schedule-week-wrapper col-8">
                            <div class="schedule-week-header">
                              <?
                              $counter = 0;
                              ?>
                              <?for($i=$dates[0]; $i < $dates[count($dates) - 1]; $i++):?>
                                <div class="schedule-day-header today"><?=$short_week_days[$counter++]?>, <?=$i?></div>
                              <?endfor;?>
                            </div>
                        </div>
                    </div>
                    <template id="schedule-template">
                        <div class="cn-modal cn-modal-medium">
                            <div class="mfp-close cn-modal-close">×</div>
                            <div class="cn-modal-header">Проверьте корректность выбранных параметров</div>
                            <div class="cn-modal-content">
                                <div class="content col-margin-bottom">
                                    <div class="col col-4 ta-right">
                                        <b>Массажист</b>
                                    </div>
                                    <div class="col col-8"><?=$arMassagistUser["NAME"]?></div>
                                </div>
                                <div class="content col-margin-bottom">
                                    <div class="col col-4 ta-right">
                                        <b>Адрес</b>
                                    </div>
                                    <div class="col col-8"><?=$arMassagistUser["PERSONAL_STREET"]?></div>
                                </div>
                
                                <div class="content col-margin-bottom">
                                    <div class="col col-4 ta-right">
                                        <b>Квалификация</b>
                                    </div>
                                    <div class="col col-8"><?=$arMassagistUser["UF_QUALIFICATION"]?></div>
                                </div>

                                <div class="content col-margin-bottom">
                                    <div class="col col-4 ta-right">
                                        <b>Дата и время записи</b>
                                    </div>
                                    <div class="col col-8" id="confirm_form_date_and_time">
                                        date, <b>time</b>
                                    </div>
                                </div>

                                <div class="ta-center">
                                    <a href="#" class="btn btn-big" id="go_to_the_next_step">Записаться</a>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div class="schedule-item white-box clearfix" id="emp_26">
                        <div class="schedule-doctor col-4">
                            <div class="schedule-doctor-item">
                                <span class="schedule-doctor-image" style="background-image: url('<?=CFile::GetPath($arUser["PERSONAL_PHOTO"])?>');"></span>

                                <div class="schedule-doctor-content">
                                    <div class="schedule-doctor-name"><?=$arMassagistUser["NAME"]?></div>
                                    <div class="doctor-item-description"><?=$arMassagistUser["UF_QUALIFICATION"]?></div>
                                    
                                    <div class="schedule-doctor-schedule">
                                        <?for ($i=0; $i < 7; $i++):?>
                                          <div class="day-wrapper">
                                            <span class="day"><?=$short_week_days[$i]?></span><?=$work_time_start?>:00-<?=$work_time_end?>:00
                                          </div>  
                                        <?endfor?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="schedule-week-wrapper col-8">
                            <div class="schedule-week collapsed" data-week-id="ww26">
                                <?for($i = 0; $i < 6; $i++):?>
								<?if(date("N") > $i):?>
									<div class="schedule-day equal">
                                    <div class="talon">
                                        <?for($ii = $work_time_start; $ii < $work_time_end; $ii++):?>
                                          <div class="hour" data-date="<?=$dates[$i]?>.<?=date('m.Y')?>"></div>
                                        <?endfor;?>
                                    </div>
                                  </div>
								<?else:?>
                                  <div class="schedule-day equal">
                                    <div class="talon">
                                        <?for($ii = $work_time_start; $ii < $work_time_end; $ii++):?>
                                          <div class="hour" data-date="<?=$dates[$i]?>.<?=date('m.Y')?>">
                                            <?if(in_array($ii, $arResult["free_time"][$dates[$i]])):?>
                                              <a class="btn btn-schedule" href="#"><?=$ii?>:00</a>
                                            <?endif;?>
                                          </div>
                                        <?endfor;?>
                                    </div>
                                  </div>
								<?endif;?>
                                <?endfor;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

var free_time = JSON.parse('<?=json_encode($arResult["free_time"])?>');
var order_time = false;
var order_date = false;

$(document).ready(function() {
  $(".hour").on("click", function(){
    var time = $(this).find("a").text();
    var date = $(this).data("date");

    order_time = time;
    order_date = date;

    setTimeout(function(){
      $("#confirm_form_date_and_time").html(date + ", " + time);
    }, 50);
    
  });

  $("body").on("click", "#go_to_the_next_step", function(){
     $.ajax({
      data: {
        action: "booktime", 
        date: order_date, 
        time: order_time
      },
      success: function(data){
        window.location.replace("/schedule/record_wizard.php/?STEP=registration");
      },
      error: function(data){
        alert("Ошибка резервации времени. Попробуйте перегрузить страницу и повторить.");
      }
    });
  });

  $(".fc-scroller.fc-time-grid-container").height("inherit")
});

</script>