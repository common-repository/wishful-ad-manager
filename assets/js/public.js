/**
 * IIFE jQuery scripts.
 */
(function ($) {

	/**
	 * Load the codes on document ready.
	 */
	$(function ($) {
		var helpers = wishfulAdManager();

		var adContent = $('.wishful-ad-manager-content');

		adContent.on('click', function (e) {
			e.preventDefault();
			var href = $(this).children().attr('href');
			var target = $(this).children().attr('target');

			if ( 'undefined' !== typeof href && 'undefined' !== typeof target ) {
				var adID = helpers.getAdID(this);
				helpers.doAjax('click', adID);
				window.open( href, target);
			}
		});

		var scrollTimer, lastScrollFireTime = 0;
		function onWindowScroll() {

			// Throttle the scroll event
			var minScrollTime = 200;
			var now = new Date().getTime();

			function processScroll() {

				var seenAll = true;
				adContent.each(function (i, elem) {
					var $elem = $(elem);
					var seen = $elem.data('seen');

					if (seen !== true) {
						seenAll = false;
					}

					var isViewed = helpers.isAdInView($elem);

					if (seen === undefined && isViewed) {
						$elem.attr('data-seen', true);
						var adID = helpers.getAdID($elem);
						helpers.doAjax('view', adID);
					}
				});

				/**
				 * Unbind scroll event if all ads were visible at least once
				 */
				if (seenAll) {
					$(window).unbind('scroll');
				}
			}

			// throttle scroll logic
			if (!scrollTimer) {
				if (now - lastScrollFireTime > (3 * minScrollTime)) {
					processScroll();   // fire immediately on first scroll
					lastScrollFireTime = now;
				}
				scrollTimer = setTimeout(function () {
					scrollTimer = null;
					lastScrollFireTime = new Date().getTime();
					processScroll();
				}, minScrollTime);
			}

		}

		$(window).on('scroll', onWindowScroll);
	});

	/**
	 * Helpers functions object.
	 */
	function wishfulAdManager() {
		return {

			getAdID(adContent) {
				var adID = parseInt($(adContent).attr('data-id'));
				return adID;
			},

			/**
			 * Checks if ad content is in view or not.
			 *
			 * @param {*} adContent Adcontent html element.
			 */
			isAdInView(adContent) {
				var topOfElement = $(adContent).offset().top;
				var bottomOfElement = $(adContent).offset().top + $(adContent).outerHeight();
				var bottomOfScreen = $(window).scrollTop() + $(window).innerHeight();
				var topOfScreen = $(window).scrollTop();

				return (bottomOfScreen > topOfElement) && (topOfScreen < bottomOfElement)
			},

			/**
			 * Sends the ajax request.
			 *
			 * @param {String} type Event type, click || view
			 * @param {Number} adID Ad ID.
			 */
			doAjax(type, adID) {
				$.post(wishfulAdManagerData.ajaxurl, {
					action: 'wishful_ad_manager_ajax',
					type: type,
					nonce: wishfulAdManagerData.nonce,
					ad_id: adID,
				});
			},

		}
	}


})(jQuery);