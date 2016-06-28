(function () {
	'use strict';
	$(document)
		.on('click', '.special-settings a', function (e) {
			e.preventDefault();
			setSpecialVersion($(this).data());
		})
		.on('click', '[data-aa-off]', function(e) {
			e.preventDefault();
			unsetSpecialVersion();
		})
		.on('click keypress', '[data-aa-on]', function(e) {
			e.preventDefault();
			if ((e.type == 'keypress' && e.keyCode == '13') || e.type == 'click') {
				setDefaultsSpecialVersion();
			};
		});

	jQuery(document).ready(function ($) {
		setSpecialVersion();
	});

	/**
	 * РЈСЃС‚Р°РЅРѕРІРєР° РїР°СЂР°РјРµС‚СЂРѕРІ РѕС‚РѕР±СЂР°Р¶РµРЅРёСЏ СЃРїРµС†РІРµСЂСЃРёРё
	 * @param {object} data РћР±СЉРµРєС‚ СЃ РґР°РЅРЅС‹РјРё РґР»СЏ С„РѕСЂРјРёСЂРѕРІР°РЅРёСЏ РїР°СЂР°РјРµС‚СЂРѕРІ
	 */
	function setSpecialVersion(data) {
		var
		// РїРѕР»СѓС‡Р°РµРј С‚РµРєСѓС‰РµРµ Р·РЅР°С‡РµРЅРёРµ РїРµСЂРµРјРµРЅРЅРѕР№ $.cookie.json;
			cookieJson = $.cookie.json,
			$html = $('html'),
			// РџРѕР»СѓС‡Р°РµРј С‚РµРєСѓС‰РµРµ Р·РЅР°С‡РµРЅРёРµ Р°С‚СЂРёР±СѓС‚Р° class Рё html.
			htmlCurrentClass = $html.prop('class'),
			// РЈРґР°Р»СЏРµРј СЃС‚Р°СЂС‹Рµ РєР»Р°СЃСЃС‹ Сѓ html, РѕСЃС‚Р°РІР»СЏРµРј С‚РѕР»СЊРєРѕ С‡СѓР¶РёРµ РєР»Р°СЃСЃС‹, СЌС‚Рѕ РІР°Р¶РЅРѕ, 
			// С‚.Рє. РєР»Р°СЃСЃС‹ РґРѕР±Р°РІР»СЏРµРј РЅРµ С‚РѕР»СЊРєРѕ РјС‹.
			clearSpecialClasses = htmlCurrentClass.replace(/special-([a-z,A-Z,-]+)/g, ''),
			// РџСЂРёР·РЅР°Рє РІРєР»СЋС‡РµРЅРЅРѕР№ СЃРїРµС†РІРµСЂСЃРёРё, РѕРЅ Р¶Рµ СЃРїРµС†РёР°Р»СЊРЅС‹Р№ РєР»Р°СЃСЃ, РєРѕС‚РѕСЂС‹Р№ РґРѕР±Р°РІРёС‚СЃСЏ Рє html
			$aaVersion = {'aaVersion':'on'},
			// РџРµСЂРµРјРµРЅРЅР°СЏ РїРѕРґ РЅРѕРІС‹Рµ РєР»Р°СЃСЃС‹.
			htmlClass = '';

		// Р Р°Р±РѕС‚Р°РµРј СЃ РєСѓРєР°РјРё РІ json-С„РѕСЂРјР°С‚Рµ	
		$.cookie.json = true;

		// Р•СЃР»Рё РїРµСЂРµРґР°РЅС‹ РґР°РЅРЅС‹Рµ
		if (data) {
			// РћР±СЉРµРґРёРЅСЏРµРј СЃСѓС‰РµСЃС‚РІСѓСЋС‰РёРµ РєСѓРєРё СЃ РЅРѕРІС‹РјРё РґР°РЅРЅС‹РјРё РёР· СЃСЃС‹Р»РєРё.
			var $newCookies = $.extend($.cookie('aaSet'), data, $aaVersion);

			// Р—Р°РїРёСЃС‹РІР°РµРј РЅРѕРІСѓСЋ РєСѓРєСѓ
			$.cookie('aaSet', $newCookies, {
				expires: 365,
				path: '/',
				secure: false
			});
		}

		// РЈРґР°Р»СЏРµРј РЅРµРЅСѓР¶РЅС‹Рµ РєР»Р°СЃСЃС‹ a-current.
		$('.a-current').removeClass('a-current');

		// Р•СЃР»Рё РµСЃС‚СЊ РєСѓРєР° вЂ” СЂР°Р±РѕС‚Р°РµРј.
		if ($.cookie('aaSet')) {		
			// РџСЂРѕР±РµРіР°РµРј РїРѕ РјР°СЃСЃС‹РІСѓ РёР· РЅР°С€РµР№ РєСѓРєРё
			$.each($.cookie('aaSet'), function (key, val) {
				// Р¤РѕСЂРјРёСЂСѓРµРј СЃС‚СЂРѕРєСѓ СЃ РґРѕР±Р°РІР»СЏРµРјС‹РјРё РєР»Р°СЃСЃР°Рј (РёСЃРїРѕР»СЊР·РѕРІР°С‚СЊ С‚СѓС‚ .addClass вЂ” Р·Р»Рѕ!)
				htmlClass += ' special-' + key + '-' + val;
				// Р”РѕР±Р°РІР»СЏРµРј РЅСѓР¶РЅС‹Рµ РєР»Р°СЃСЃС‹ a-current.
				$('.' + key + '-' + val).addClass('a-current');

			});
			
			$html
			// Р—Р°РјРµРЅСЏРµРј С‚РµРєСѓС‰РёР№ Р°С‚СЂРёР±СѓС‚ РЅР° РѕС‡РёС‰РµРЅРЅС‹Р№ РѕС‚ Р»РёС€РЅРёС… РєР»Р°СЃСЃРѕРІ.
				.prop('class', clearSpecialClasses)
				// Р”РѕР±Р°РІР»СЏРµРј РІРЅРѕРІСЊ СЃС„РѕСЂРјРёСЂРѕРІР°РЅРЅС‹Рµ РєР»Р°СЃСЃС‹.
				.addClass(htmlClass);


			// Р’РѕР·РІСЂР°С‰Р°РµРј С„РѕСЂРјР°С‚ РєСѓРєРѕРІ РєР°Рє Р±С‹Р»Рѕ РґРѕ РЅР°СЃ.
			$.cookie.json = cookieJson;
		}

		// РџРµСЂРµСЃС‡С‘С‚ РІС‹СЃРѕС‚С‹ Р±Р»РѕРєРѕРІ С‚Р°Рј, РіРґРµ РЅРµРѕР±С…РѕРґРёРјРѕ.
		$.fn.matchHeight._update();

		return false;
	}

	/**
	 * РћС‚РєР»СЋС‡РµРЅРёРµ СЃРїРµС†РёР°Р»СЊРЅРѕР№ РІРµСЂСЃРёРё СЃР°Р№С‚Р°.
	 */
	function unsetSpecialVersion() {
		var 
			// РџРѕР»СѓС‡Р°РµРј Р·РЅР°С‡РµРЅРёРµ РєР»Р°СЃСЃР° С‚РµРіР° html.
			htmlCurrentClass = $('html').prop('class'),
			// РћС‡РёС‰Р°РµРј РѕС‚ РєР»Р°СЃСЃРѕРІ СЃРїРµС†РІРµСЂСЃРёРё
			clearSpecialClasses = htmlCurrentClass.replace(/special-([a-z,A-Z,-]+)/g, '');
		// Р—Р°РјРµРЅСЏРµРј С‚РµРєСѓС‰РёР№ Р°С‚СЂРёР±СѓС‚ РЅР° РѕС‡РёС‰РµРЅРЅС‹Р№ РѕС‚ Р»РёС€РЅРёС… РєР»Р°СЃСЃРѕРІ.
		$('html').prop('class', clearSpecialClasses);
		// РЈРґР°Р»СЏРµРј РєСѓРєРё
		$.removeCookie('aaSet', {path: '/'});
		
		// РџРµСЂРµСЃС‡С‘С‚ РІС‹СЃРѕС‚С‹ Р±Р»РѕРєРѕРІ С‚Р°Рј, РіРґРµ РЅРµРѕР±С…РѕРґРёРјРѕ.
		$.fn.matchHeight._update();
	}

	/**
	 * РЈСЃС‚Р°РЅРѕРІРєР° РґРµС„РѕР»С‚РЅС‹С… Р·РЅР°С‡РµРЅРёР№ РґР»СЏ СЃРїРµС†РІРµСЂСЃРёРё.
	 * @param {object} params РћР±СЉРµРєС‚ СЃ РґР°РЅРЅС‹РјРё РґР»СЏ С„РѕСЂРјРёСЂРѕРІР°РЅРёСЏ РїР°СЂР°РјРµС‚СЂРѕРІ.
	 */
	function setDefaultsSpecialVersion(params) {
		// Р—Р°РґР°С‘Рј Р·РЅР°С‡РµРЅРёСЏ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ
		var $specialDefaults = {
			'aaVersion':'on',
			'aaColor': 'black',
			'aaFontsize': 'small',
			'aaFont': 'serif',
			'aaKerning': 'normal',
			'aaImage': 'on'
		};

		// РћР±СЉРµРґРёРЅСЏРµРј Р·РЅР°С‡РµРЅРёСЏ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ СЃ РїРµСЂРµРґР°РЅРЅС‹РјРё РґР°РЅРЅС‹РјРё.
		var $setDefaulParams = $.extend($specialDefaults, params);

		// Р’С‹Р·С‹РІР°РµРј setSpecialVersion, РіРґРµ Рё РїСЂРѕРёСЃС…РѕРґРёС‚ РІРµСЃСЊ РїСЂРѕС†РµСЃСЃ.
		setSpecialVersion($setDefaulParams);
	}

})();


