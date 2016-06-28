$(document).on('click', '.filter-trigger', function(event) {
	var cookieName = talonGetCookie('filter-expanded'),
		$filterBlock = $('.filter-block');
	if (!cookieName) {
		talonSetCookie('filter-expanded', 'yes', 365);
		$filterBlock.find('form').slideDown('600', function() {
			$filterBlock.removeClass('filter-collapsed');
		});
	} else {
		talonSetCookie('filter-expanded', false, 0);
		$filterBlock.find('form').slideUp('600', function() {
			$filterBlock.addClass('filter-collapsed');
		});
	}

});

function talonSetCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	var expires = "expires=" + d.toUTCString();
	document.cookie = cname + "=" + cvalue + "; " + expires;
}

function talonGetCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1);
		if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
	}
	return "";
}
