$(document)
	.on('click', 'ul.tabs li:not(.current):not(.disabled)', function (callback) {
			var thisTab = $(this);
			thisTab
				.addClass('current')
				.siblings().removeClass('current')
				.parents('.tab-section')
				.find('.box').eq(thisTab.index())
				.fadeIn(150, function () {
					thisTab.trigger('tabClick');
				})
				.siblings('.box').hide();
		})
		.on('click', function (e) {
			if ($(e.target).closest('.typeahead-wrapper').length) return;
			$('.typeahead-wrapper').fadeOut('150');
		})
		.on('click', '.step, .all-doctors:not(.noimage) ul li', function () {
			var href = $(this).find("a").attr("href");
			if (href == '#' || href == '' || href == undefined)
				return false;
			window.location = $(this).find("a").attr("href");
			return false;
		})

jQuery(document).ready(function($) {
	$('.letter').fixer({
		gap: 90
	});
	$(".sticky-block").sticky({topSpacing:0});

	$('.ppvr').popover();

});
