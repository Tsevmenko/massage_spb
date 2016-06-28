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
// init section
$serviceName = "Массаж интимных мест";
$work_time_start = 8;
$work_time_end = 20;

$week_days = array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресение");

?>

<div class="col-margin-bottom service" style="display: inline-block;">
	<label>Выберите день недели:</label>
	<select class="select1 input serviceDay" name="time" style="margin-bottom: 0px;">
	<option>Выберите день недели</option>
		<?for ($i=0; $i < 7; $i++):?>
			<option style="padding:0 0 0 25px" value="<?=$i?>"><?=$week_days[$i]?></option>
		<?endfor;?>
	</select>
</div>
<span> </span>
<div class="col-margin-bottom service" style="display: inline-block;">
	<label>Выберите свободное время:</label>
	<select class="select1 input serviceTime" name="time" style="margin-bottom: 0px;">
	<option>Выберите свободное время</option>
		<?for ($i=$work_time_start; $i < $work_time_end; $i++):?>
			<option style="padding:0 0 0 25px" value="<?=$i?>"><?=$i?>:00</option>
		<?endfor;?>
	</select>
</div>

<button type="button" style="display: inline-block;" class="btn btn-w-md btn-primary btn-squared add_service_line">Добавить услугу</button>

<div id='calendar'></div>

<script>

	$(document).ready(function() {

		var current_page = 0;

		$('body').on('click', 'button.fc-prev-button', function() {
			$('.serviceDay').prop('selectedIndex',0);
			$('.serviceTime').prop('selectedIndex',0);
			current_page--;
			console.log(current_page);
		});

		$('body').on('click', 'button.fc-next-button', function() {
			$('.serviceDay').prop('selectedIndex',0);
			$('.serviceTime').prop('selectedIndex',0);
			current_page++;
			console.log(current_page);
		});

		$(".serviceTime").on("click", function(){
			// remove event
			$('#calendar').fullCalendar( 'removeEvents', [10000001] );

			$('#calendar').fullCalendar( 'renderEvent', {
			    id: 10000001,
			    title: '<?=$serviceName?>',
			    start: '2013-03-30',
			    end: '2013-03-30',
			    className: 'fancy-color'
			}, true );
			// add event
			var event={id:10000001 , title: 'New event', start:  new Date()};

			$('#calendar').fullCalendar( 'renderEvent', event, true);
		});

		$('#calendar').fullCalendar({
			header: {
				left: '',
				center: '',
			  	right: 'prev,next'
			},
			timeFormat: 'h:mm',
			minTime: "10:00",
			maxTime: "17:00",
			slotDuration: "00:30:00",
			slotLabelInterval:"00:30:00",
			lang: "ru",
			defaultDate: '2016-05-23',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			defaultView: "agendaWeek",
			allDaySlot: false,
			eventOverlap: false,
			eventClick: function(event, element) {

				//event.title = "CLICKED!";

				//$('#calendar').fullCalendar('updateEvent', event);

			},
			events: [
			  {
				id: "1",
				title: 'Занятое время',
				start: '2016-05-24 11:00',
				end: '2016-05-24 12:00',
				color: "#f00",
				editable: false,
			  },
			]
		  });
	});

</script>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>