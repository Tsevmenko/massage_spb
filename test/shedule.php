<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Мастер записи");
$APPLICATION->SetPageProperty("description", "Мастер записи");
$APPLICATION->SetTitle("Запись на прием");
?>

<meta charset='utf-8' />
<link href='http://fullcalendar.io/js/fullcalendar-2.7.2/fullcalendar.css' rel='stylesheet' />
<link href='http://fullcalendar.io/js/fullcalendar-2.7.2/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js'></script>
<script src='//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='http://fullcalendar.io/js/fullcalendar-2.7.2/fullcalendar.js'></script>
<script src='http://fullcalendar.io/js/fullcalendar-2.7.2/lang/es.js'></script>
<script src='http://fullcalendar.io/js/fullcalendar-2.7.2/lang-all.js'></script>

<style>
  body {
    margin: 40px 10px;
    padding: 0;
    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    font-size: 14px;
  }
  
  #calendar {
    max-width: 900px;
    margin: 0 auto;
  }
</style>

<?
  CModule::IncludeModule("iblock");

  switch($_REQUEST["action"])
  {
    case "addnewbusytime":
      
      $week = array("Понедельник" => 18, "Вторник" => 19, "Среда" => 20, "Четверг" => 21, "Пятница" => 22, "Суббота" => 23, "Воскресение" => 24);

      $el = new CIBlockElement;

      $PROP = array();
      $PROP[75] = $week[$_REQUEST["workday"]];
      $PROP[76] = $_REQUEST["timefrom"];
      $PROP[77] = $_REQUEST["timeto"];
      $PROP[78] = $_REQUEST["massagist"];
      $PROP[79] = $_REQUEST["client"];
      $PROP[80] = $_REQUEST["order_id"];

      $arLoadProductArray = Array(
        "IBLOCK_ID"      => 30,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => $_REQUEST["massagist"] . "_" . $_REQUEST["workday"] . "_" . $_REQUEST["timefrom"],
      );

      if($PRODUCT_ID = $el->Add($arLoadProductArray))
      {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        echo $PRODUCT_ID;
        die();
      }
    break;
    case "removebusytime":
      CIBlockElement::Delete($_REQUEST["id"]);
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
$arFilter = Array("IBLOCK_ID"=>30, "MASSAGIST" => $USER->GetID());
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
$slice = -(date("w") + 1);
foreach($week_days as $k => $v)
{  
  $date = new DateTime();
  $date->modify('-'.$slice.' days');
  $week_calendar_numbers[] = $date->format('d');
  $slice++;
}

$week_calendar_numbers = array_reverse($week_calendar_numbers);
$week = array();

foreach ($week_days as $k => $v) 
{
  $week[$week_calendar_numbers[$k]] = $v;
}

$work_time_start = $arUser["UF_WORKTIME_FROM"];
$work_time_end = $arUser["UF_WORKTIME_TO"];
?>

<div>
  <p>Рабочее время:</p>
  <div class="col-margin-bottom service" style="display: inline-block;">
      <label>С:</label>
      <select class="select1 input" id="workTimeFrom" name="time" style="margin-bottom: 0px;">
        <?for ($i=$work_time_start; $i < $work_time_end; $i++):?>
            <option <?if($arUser["UF_WORKTIME_FROM"] == $i):?> selected <?endif;?>data-hour="<?=$i?>" value="<?=$i?>"><?=$i?>:00</option>
        <?endfor;?>
      </select>
  </div>
  <div class="col-margin-bottom service" style="display: inline-block;">
      <label>До:</label>
      <select class="select1 input serviceTime" id="workTimeTo" name="time" style="margin-bottom: 0px;">
        <?for ($i=$work_time_start; $i < $work_time_end; $i++):?>
            <option <?if($arUser["UF_WORKTIME_TO"] == $i):?> selected <?endif;?> data-hour="<?=$i?>" value="<?=$i?>"><?=$i?>:00</option>
        <?endfor;?>
      </select>
  </div>
  <hr/>
  <p>Занятое время:</p>
  <div id="sheduler_place">
    <?$js_events = "";
      $page_event_ids = "";
    ?>
    <?foreach($arResult as $k => $v):?>
      <?foreach($v as $kk => $vv):?>
        <?
          $df = DateTime::createFromFormat('Y-m-d H:i:s', $vv["TIME_FROM"]);
          $dt = DateTime::createFromFormat('Y-m-d H:i:s', $vv["TIME_TO"]);
                
          $page_event_ids .= $vv["ID"] . ", ";

          $js_events .= "{
            title: 'Занятое время',
            start: '" . $vv["TIME_FROM"] . "',
            end: '" . $vv["TIME_TO"] . "',
            id: '" . $vv["ID"] . "_0',
            editable: false,
          },";
        ?>
        <div class="sheduleline" data-id="<?=$vv['ID']?>">
          <div class="col-margin-bottom service" style="display: inline-block; margin-bottom: 0px;">
            <div>
              <input class="input userProp dayofweek" type="text" value="<?=$k?>" disabled="disabled">
            </div>
          </div>
          <div class="col-margin-bottom service" style="display: inline-block; margin-bottom: 0px;">
            <div class="col-margin-bottom service" style="display: inline-block;">
              <div>
                <input class="input userProp timefrom" type="text" value="<?=$df->format('H:i:s')?>" disabled="disabled">
              </div>
            </div>
          </div>
          <div class="col-margin-bottom service" style="display: inline-block; margin-bottom: 0px;">
            <div class="col-margin-bottom service" style="display: inline-block;">
              <div>
                <input class="input userProp timeto" type="text" value="<?=$dt->format('H:i:s');?>" disabled="disabled">
              </div>
            </div>
          </div>
          <button type="button" style="display: inline-block;margin-top: -15px;" data-id="<?=$vv['ID']?>" class="btn btn-w-md btn-primary btn-squared removeFromShedule">Удалить</button>
        </div>
      <?endforeach;?>
    <?endforeach;?>
  </div>
  <div class="shedulerblank" style="display: none;">
    <div class="col-margin-bottom service" style="display: inline-block; margin-bottom: 0px;">
      <div>
        <input class="input userProp dayofweek" type="text" name="properties[NAME]" data-code="NAME" value="Анатолий">
      </div>
    </div>
    <div class="col-margin-bottom service" style="display: inline-block; margin-bottom: 0px;">
      <div class="col-margin-bottom service" style="display: inline-block;">
        <div>
          <input class="input userProp timefrom" type="text" name="properties[NAME]" data-code="NAME" value="Анатолий">
        </div>
      </div>
    </div>
    <div class="col-margin-bottom service" style="display: inline-block; margin-bottom: 0px;">
      <div class="col-margin-bottom service" style="display: inline-block;">
        <div>
          <input class="input userProp timeto" type="text" name="properties[NAME]" data-code="NAME" value="Анатолий">
        </div>
      </div>
    </div>
    <button type="button" style="display: inline-block;margin-top: -15px;" class="btn btn-w-md btn-primary btn-squared removeFromShedule">Удалить</button>
  </div>
  <div>
    <div class="col-margin-bottom service" style="display: inline-block;margin-right: 20px;">
      <label>Выберите день недели:</label>
      <select class="select1 input serviceDay" id="shedulerWeekDay" name="time" style="margin-bottom: 0px;">
          <?foreach($week as $k => $v):?>
              <option value="<?=$k?>"><?=$week[$k]?></option>
          <?endforeach;?>
      </select>
    </div>
    <span> </span>
    <div class="col-margin-bottom service" style="display: inline-block; margin-right: 20px;">
      <label>Время с:</label>
      <select class="select1 input serviceTime" id="shedulerTimeFrom" name="time" style="margin-bottom: 0px;">
        <?for ($i=$work_time_start; $i < $work_time_end; $i++):?>
            <option data-hour="<?=$i?>" value="<?=$i?>"><?=$i?>:00</option>
        <?endfor;?>
      </select>
    </div>
    <div class="col-margin-bottom service" style="display: inline-block;margin-right: 20px;">
      <label>Время до:</label>
      <select class="select1 input serviceTime" id="shedulerTimeTo" name="time" style="margin-bottom: 0px;">
        <?for ($i=$work_time_start; $i < $work_time_end; $i++):?>
            <option data-hour="<?=$i?>" value="<?=$i?>"><?=$i?>:00</option>
        <?endfor;?>
      </select>
    </div>
    <button type="button" id="addToShedule" style="display: inline-block;" class="btn btn-w-md btn-primary btn-squared">Добавить в расписание</button>
  </div>
</div>

<div id='calendar'></div>

<script>

    $(document).ready(function() {

        var current_page = 0;
        var page_event_ids = [<?=$page_event_ids?>];
        var current_date = new Date();
        var week = JSON.parse('<?=json_encode($week)?>');

        $("#addToShedule").on("click", function(){

          var weekday = $("#shedulerWeekDay").val();
          var timefrom = $("#shedulerTimeFrom").val();
          var timeto = $("#shedulerTimeTo").val();
          var d = new Date();
          var df = current_date.getFullYear() + "-" + (current_date.getUTCMonth() + 1) + "-" + weekday + " " + timefrom + ":00:00";
          var dt = current_date.getFullYear() + "-" + (current_date.getMonth() + 1) + "-" + weekday + " " + timeto + ":00:00";
          // add to db
          $.ajax({
            data: { action: "addnewbusytime", workday: week[weekday], timefrom: df, timeto: dt, massagist: "<?=$arUser['ID']?>" }
          }).done(function(eventid){
            // successfully added, then add to view

            // add to list
            var line = $(".shedulerblank").clone();
            line.addClass("sheduleline");
            line.removeClass("shedulerblank");
            line.find(".dayofweek").val(week[weekday]);
            line.find(".timefrom").val(timefrom);
            line.find(".timeto").val(timeto);
            line.find(".dayofweek").attr("disabled", "disabled");
            line.find(".timefrom").attr("disabled", "disabled");
            line.find(".timeto").attr("disabled", "disabled");
            line.css("display", "block");
            line.data("id", eventid);
            line.find(".removeFromShedule").data("id", eventid);
            $("#sheduler_place").append(line);

            // add to calender
            addCalenderEvent(eventid + "_" + current_page, df, dt, "Занятое время");
          });

        });

        $("body").on("click", ".removeFromShedule", function(){
            
            var removed_id = $(this).data("id");
            // remove from db
            $.ajax({
              data: { action: "removebusytime", id: removed_id }
            }).done(function(eventid){
              // remove from list
              $(".sheduleline").each(function(){
                if($(this).data("id") == removed_id)
                  $(this).remove();
              });
              // remove from calender
              removeCalenderEvent(removed_id + "_" + current_page);
            });
        });

        $('body').on('click', 'button.fc-prev-button', function() {
            $('.serviceDay').prop('selectedIndex',0);
            $('.serviceTime').prop('selectedIndex',0);
            current_page--;
            current_date.setDate(current_date.getDate() - 7);
            $($('#calendar').fullCalendar('clientEvents')).each(function(i, e){
              
              var id, df, dt;
              
              if(e.end !== null) 
              {
                dt = new Date(e.end._i);
                dt.setDate(dt.getDate() - 7);
              }

              id = e.id.substr(0, e.id.indexOf("_")) + "_" + current_page;

              df = new Date(e.start._i);
              df.setDate(df.getDate() - 7);
              addCalenderEvent(id, df, dt, "Занятое время");
            });

            $($('#calendar').fullCalendar('clientEvents')).each(function(i, e){

              var splitter = e.id.split("_");
              if(splitter[1] != current_page)
              {
                removeCalenderEvent(e.id);
              }
              
            });
        });

        $('body').on('click', 'button.fc-next-button', function() {
           $('.serviceDay').prop('selectedIndex',0);
            $('.serviceTime').prop('selectedIndex',0);
            current_page++;
            current_date.setDate(current_date.getDate() + 7);
            $($('#calendar').fullCalendar('clientEvents')).each(function(i, e){
              
              var id, df, dt;
              
              if(e.end !== null) 
              {
                dt = new Date(e.end._i);
                dt.setDate(dt.getDate() + 7);
              }

              id = e.id.substr(0, e.id.indexOf("_")) + "_" + current_page;

              df = new Date(e.start._i);
              df.setDate(df.getDate() + 7);
              addCalenderEvent(id, df, dt, "Занятое время");
            });

            $($('#calendar').fullCalendar('clientEvents')).each(function(i, e){

              var splitter = e.id.split("_");
              if(splitter[1] != current_page)
              {
                removeCalenderEvent(e.id);
              }
              
            });

        });

        $('body').on('change', '#workTimeFrom', function() {
          postNewWorkTime();
        });

        $('body').on('change', '#workTimeTo', function() {
          postNewWorkTime();
        });

        function addCalenderEvent(id, start, end, title, colour)
        {
          var eventObject = {
            title: title,
            start: start,
            end: end,
            id: id,
            color: colour,
            editable: false,
          };

          $('#calendar').fullCalendar('renderEvent', eventObject, true);
          return eventObject;
        }

        function removeCalenderEvent(id)
        {
          $('#calendar').fullCalendar( 'removeEvents', [id] );
        }

        function sendEventToServer(id)
        {
          var events = $('#calendar').fullCalendar('clientEvents');

          events.each(function(){
            if($(this).id == id)
            {
              $.ajax({
                data: { obj: JSON.stringify(), master: "" }
              }).done(function(){

              });
            }
          });
        
        }

        $('#calendar').fullCalendar({
            header: {
                left: '',
                center: '',
                right: 'prev,next'
            },
            timeFormat: 'h:mm',
            minTime: "<?=$work_time_start?>:00",
            maxTime: "<?=$work_time_end?>:00",
            slotDuration: "00:60:00",
            slotLabelInterval:"00:60:00",
            lang: "ru",
            defaultDate: "<?=date('Y-m-d')?>",
            editable: true,
            eventLimit: true,
            defaultView: "agendaWeek",
            allDaySlot: false,
            eventOverlap: false,
            eventClick: function(event, element) { /*event click handler*/  },
            events: [<?=$js_events?>]
          });
  
        //$(".fc-axis.fc-time.fc-widget-content").each(function(){if($(this).text().indexOf(":") < 1) {console.log($(this).text($(this).text() + ":00"));}});
    });

</script>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>