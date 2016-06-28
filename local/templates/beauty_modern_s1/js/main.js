// РћРїСЂРµРґРµР»СЏРµРј РїРµСЂРµРјРµРЅРЅСѓСЋ РґР»СЏ СЌРєРѕРЅРѕРјРёРё РїР°РјСЏС‚Рё.
var $doc = $(document);

$doc
	.on('input keyup', '.jq-selectbox__search input', function(e) {
		// РћР±СЂР°Р±РѕС‚РєР° РєР»РёРєР° Рё С‚Р°С‡Р° РїРѕ СЂРѕРґРёС‚РµР»СЊСЃРєРёРј РїСѓРЅРєС‚Р°Рј РјРµРЅСЋ
		if (e.type == 'input') {
			// $(this).off('keyup');
		}
		$(this).closest('ul').perfectScrollbar('update');

	})
	.on('click', '.services-list-header', function(event) {
		// РћР±СЂР°Р±РѕС‚РєР° РєР»РёРєР° РїРѕ Р·Р°РіРѕР»РѕРІРєСѓ РІ СЃРїРёСЃРєР°С… СѓСЃР»СѓРі
		event.preventDefault();
		$(this).toggleClass('active');
	})

	.on('click', '.tabs-switcher:not(.active):not(.disabled)', function(e) {
		// РўР°Р±С‹ СЃ РіРµРЅРµСЂР°С†РёРµР№ СЃРѕР±С‹С‚РёСЏ
		var $thisTab = $(this),
			$tabContent = $thisTab.closest('.tabs').find('.tabs-item'),
			$thisTabContent = $tabContent.eq($thisTab.index());

		$thisTab
			.addClass('active')
			.siblings().removeClass('active');

		$tabContent.removeClass('active');
		$thisTabContent.addClass('active');
		
		$thisTab.trigger('tabClick', $thisTabContent);
	})
	.on('tabClick', function(e, data) {
		/**
		 * РћС‚Р»Р°РІР»РёРІР°РµРј СЃРѕР±С‹С‚РёРµ РєР»РёРєР° РїРѕ С‚Р°Р±Сѓ
		 * РњРѕР¶РµС‚ РїСЂРёРіРѕРґРёС‚СЊСЃСЏ РґР»СЏ РѕР±СЂР°Р±РѕС‚РєРё РєРѕРЅС‚РµРЅС‚Р° РѕС‚РєСЂС‹С‚РѕРіРѕ С‚Р°Р±Р°
		 */
		// console.log(e, data);
	})
	.on('click', '.show-collapsed-days', function(event) {
		event.preventDefault();
		var $this = $(this),
			$data = $this.data(),
			$week = $('[data-week-id="' + $data.expandId + '"]');

		if ($this.hasClass('expanded')) {
			$week.addClass('collapsed');
			$this.text($data.collapsedText).removeClass('expanded');
		} else {
			$week.removeClass('collapsed');
			$this.text($data.expandedText).addClass('expanded');
		}
		$.fn.matchHeight._update();
	})
	.on('change', '.styler-steps', function() {
		var url = $(this).find(":selected").data('url');
		if (url) {
			location = url;
		}
		return false;
	})
	.on('click', '.main-expand-btn', function(e) {
		e.preventDefault();
		var $this = $(this);

		$this.parent().hide().parent().find('.main-expand-wrapper').addClass('expanded');
	})
	.on('change', '[data-checkbox-toggle-block]', function() {
		var $this = $(this),
			$target = $($this.data('checkboxToggleBlock'));
		if ($this.prop('checked')) {
			$target.removeClass('hide');
		} else {
			$target.addClass('hide');
		}
	})
	.on('click', '.btn-schedule, .link-schedule', function (e) {
		// РџРµСЂРµРјРµРЅРЅР°СЏ РІС‹РЅРµСЃРµРЅР° СЃСЋРґР° РґР»СЏ СѓРґРѕР±СЃС‚РІР° РїРѕРґРґРµСЂР¶РєРё, 
		// РЅР° СЃРєРѕСЂРѕСЃС‚СЊ СЂР°Р±РѕС‚С‹ СЌС‚Рѕ РІР»РёСЏРµС‚ РјРµРЅСЊС€Рµ, С‡РµРј РЅР° СѓРґРѕР±СЃС‚РІРѕ РїРѕРґРґРµСЂР¶РєРё.
		var template = document.getElementById('schedule-template').innerHTML || '';

		if (template) {
			e.preventDefault();

			var $this = $(this),
				$thisData = $this.data(),
				time = $this.text();
			if ($this.hasClass('link-schedule')) {
				time = $this.parent().prev('.timeTop').text() + ' - ' + $this.parent().next('.timeBottom').text();
			}

			var show = (cnJsTemplater(template, {
				link: $this.prop('href'),
				time: time,
				data: $thisData
			}));

			$.magnificPopup.open({
				items: {
					src: show
				},
				type: 'inline'
			});
		}

	})
	.on('focusin touchend', 'ul.root > li > a', function(e) {
		// РћР±СЂР°Р±РѕС‚РєР° РІС‹РїР°РґР°СЋС‰РµРіРѕ РјРµРЅСЋ РІ С€Р°РїРєРµ
		var $this = $(this),
			$parent = $this.closest('li'),
			$second = $this.next('ul');
		if (e.type == 'focusin') {
			// РЈР±РёСЂР°РµРј Сѓ РІРЅСѓС‚СЂРµРЅРЅРёС… СЃСЃС‹Р»РѕРє РІ РјРµРЅСЋ tabindex
			$('li.parent ul a').prop('tabindex', false);
	
			// Р”РѕР±Р°РІР»СЏРµРј tabindex РґР»СЏ РІРЅСѓС‚СЂРµРЅРЅРёС… СЃСЃС‹Р»РѕРє РІ С‚РµРєСѓС‰РµРј РїСѓРЅРєС‚Рµ РјРµРЅСЋ
			$second.find('a').prop('tabindex', 1);

			if ($parent.hasClass('tabbed')) {
				$parent.removeClass('tabbed');
			} else {
				$parent.addClass('tabbed').siblings().removeClass('tabbed');
			}
		};
	})
	.on('focusin mouseenter', 'ul.root > li > a', function(e) {
		var $this = $(this),
			$ul = $this.next('ul'),
			thisPos = $this.position().top + 37;

		$ul.css('top', thisPos);
	})
	.on('input keyup', '.search-in-page-input', function () {
		var $this = $(this);

		if ($this.val() != '' && !$this.hasClass('not-empty')) {
			$this.addClass('not-empty');
		} 
		if($this.val() == '') {
			$this.removeClass('not-empty');
		}
	})
	.on('click', '.search-in-page-reset', function() {
		$('.search-in-page-input').removeClass('not-empty').focus();
	})

	;



if (window.frameCacheVars !== undefined) {
	// РљРѕРјРїРѕР·РёС‚
	BX.addCustomEvent('onFrameDataReceived', function (json) {
		mainJsFile();
	});
}
else {
	// РћР±С‹С‡РЅС‹Р№ СЂРµР¶РёРј
	jQuery(document).ready(function ($) {
		mainJsFile();
	});
}

// РћСЃРЅРѕРІРЅРѕР№ js-РєРѕРґ, РІС‹РїРѕР»РЅСЏРµРјС‹Р№ РїСЂРё Р·Р°РіСЂСѓР·РєРµ СЃС‚СЂР°РЅРёС†С‹
function mainJsFile() {
	
	// РЎС‚РёР»РёР·Р°С†РёСЏ СЃРµР»РµРєС‚РѕРІ
	$('.styler').styler({
		selectSearch: true,
		selectSearchLimit: 20,
		singleSelectzIndex: 1000,
		onSelectOpened: function() {
			// Рє РѕС‚РєСЂС‹С‚РѕРјСѓ РїСЂРёРјРµРЅСЏРµРј РїР»Р°РіРёРЅ СЃС‚РёР»РёР·Р°С†РёРё СЃРєСЂРѕР»Р»Р±Р°СЂР°
			$(this).find('ul').perfectScrollbar();
			// console.log('onSelectOpened', $(this).find('ul').html());
		},
		onFormStyled: function () {
			$('.jq-selectbox').addClass('opacity-one');
		}
	});

	// РћРґРёРЅР°РєРѕРІР°СЏ РІС‹СЃРѕС‚Р° Р±Р»РѕРєРѕРІ
	$('.equal').matchHeight();

	// РЎР»Р°Р№РґРµСЂ РЅР° РіР»Р°РІРЅРѕР№ СЃС‚СЂР°РЅРёС†Рµ
	$('.big-slider')
		.owlCarousel({
			items: 1,
			dotsContainer: '.big-slider-nav',
			nav: false,
			dots: true
		})
		.on('changed.owl.carousel', function (e) {
			// С‚.Рє. С‚РѕС‡РєРё СЃР»Р°Р№РґРµСЂР° РґРѕР±Р°РІР»СЏСЋС‚СЃСЏ С‚РѕР»СЊРєРѕ Рє РїРµСЂРІРѕРјСѓ РєРѕРЅС‚РµР№РЅРµСЂСѓ
			// Р° РїРѕ РґРёР·Р°Р№РЅСѓ РѕРЅРё РґРѕР»Р¶РЅС‹ Р±С‹С‚СЊ РІРѕ РІСЃРµС….
			// Р”РѕР±Р°РІРёРј РёС… РІ РѕСЃС‚Р°Р»СЊРЅС‹Рµ РїСЂРё СЃРјРµРЅРµ СЃР»Р°Р№РґРѕРІ
			var $slider = $(e.target),
				dotsHtml = $slider.find('.big-slider-nav:first').html();

			$.each($slider.find('.big-slider-nav'), function(index, val) {
				$(this).html(dotsHtml);
			});
		});

	// РљР°СЂСѓСЃРµР»СЊ РІ С„СѓС‚РµСЂРµ
	var $footerCarousel = $('.footer-carousel'),
		navEnable = ($footerCarousel.find('.carousel-item').length <= 3) ? false : true;

	$footerCarousel.owlCarousel({
		items: 3,
		nav: navEnable,
		dots: false,
		margin: 20
	});

	// Fallback РґР»СЏ ie9 РІ СЃРїРёСЃРєРµ РІСЂР°С‡РµР№
	$('.bx-ie9 .tree-column').autocolumnlist({
		columns: 3,
		classname: 'col col-4',
	});

	// Р›РёРїРєРёР№ Р±Р»РѕРє
	$('.sticky').stick_in_parent();

	// РўСѓР»С‚РёРїС‹
	$('.tooltip').tooltipster();

	// Р“Р°Р»РµСЂРµСЏ РєР°СЂС‚РёРЅРѕРє
	$('.image-gallery-item').magnificPopup({
		type: 'image',
		gallery: {
			enabled: 'true'
		}
	})
}


/**
 * РџСЂРѕСЃС‚РѕР№ js-С€Р°Р±Р»РѕРЅРёР·Р°С‚РѕСЂ
 * 
 * РЈСЃС‚Р°РЅРѕРІРєР° С€Р°Р±Р»РѕРЅР°:
 *  <template id="tpl">
 *  	<ul>
 *  		{% for(var i in this.items) { %}
 *  			<li>
 *  				<b>{% this.items[i].name %}</b>
 *  			</li>
 *  		{% } %}
 *  	</ul>
 *  </template>
 * Р?СЃРїРѕР»СЊР·РѕРІР°РЅРёРµ: 
 * var template = document.getElementById('tpl').innerHTML;
 * var $items = {0:{'name': 'one'}, 1: {'name': 'two'}};
 * var show = (cnJsTemplater(template, {
				items: $items
			}));
 * console.log(show);
 */

function cnJsTemplater(html, options) {
	'use strict';
	var re = /\{%(.+?)%\}/g,
		reExp = /(^( )?(var|if|for|else|switch|case|break|{|}|;))(.*)?/g,
		code = 'with(obj) { var r=[];\n',
		cursor = 0,
		result,
		match;
	var add = function (line, js) {
		js ? (code += line.match(reExp) ? line + '\n' : 'r.push(' + line + ');\n') :
			(code += line !== '' ? 'r.push("' + line.replace(/"/g, '\\"') + '");\n' : '');
		return add;
	};
	while (match = re.exec(html)) {
		add(html.slice(cursor, match.index))(match[1], true);
		cursor = match.index + match[0].length;
	}
	add(html.substr(cursor, html.length - cursor));
	code = (code + 'return r.join(""); }').replace(/[\r\t\n]/g, '');
	try {
		result = new Function('obj', code).apply(options, [options]);
	}
	catch (err) {
		console.error("'" + err.message + "'", " in \n\nCode:\n", code, "\n");
	}
	return result;
}