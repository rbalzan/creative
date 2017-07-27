jQuery(document).ready(function() {
	var revapi;



	// Make Content Visible
	jQuery(".fullwidthbanner ul , .fullscreenbanner ul").removeClass('hide');


	/**
		@HALFSCREEN SLIDER
	**/
	if(jQuery(".fullwidthbanner").length > 0) {

		// Default Thumbs [small]
		var thumbWidth 			= 100,
			thumbHeight 		= 50,
			hideThumbs			= 200,
			navigationType		= "bullet",
			navigationArrows	= "solo",
			navigationVOffset	= 10;

		// Shadow
		_shadow = jQuery(".fullwidthbanner").attr('data-shadow') || 0;

		// Small Thumbnails
		if(jQuery(".fullwidthbanner").hasClass('thumb-small')) {
			var navigationType 		= "thumb";
		}
		
		// Large Thumbnails
		if(jQuery(".fullwidthbanner").hasClass('thumb-large')) {
			var navigationType 		= "thumb";
				thumbWidth 			= 195,
				thumbHeight 		= 95,
				hideThumbs			= 0,
				navigationArrows	= "solo",
				navigationVOffset	= -94;

				// Hide thumbs on mobile - Avoid gaps
				/**
				if(jQuery(window).width() < 800) {
					setTimeout(function() {
						var navigationVOffset = 10;
						jQuery("div.tp-thumbs").addClass('hidden');
					}, 100);
				}
				**/
		}

		// Init Revolution Slider
		revapi = jQuery('.fullwidthbanner').revolution({
			dottedOverlay:"none",
			delay:5000,
			startwidth:1440,
			startheight: jQuery(".fullwidthbanner").attr('data-height') || 500,
			hideThumbs:hideThumbs,

			thumbWidth:thumbWidth,
			thumbHeight:thumbHeight,
			thumbAmount: parseInt(jQuery(".fullwidthbanner ul li").length) || 2,

			navigationType:navigationType,
			navigationArrows:navigationArrows,
			navigationStyle:jQuery('.fullwidthbanner').attr('data-navigationStyle') || "round", // round,square,navbar,round-old,square-old,navbar-old (see docu - choose between 50+ different item)

			touchenabled:"on",
			onHoverStop:"on",

			navigationHAlign:"center",
			navigationVAlign:"bottom",
			navigationHOffset:0,
			navigationVOffset:navigationVOffset,

			soloArrowLeftHalign:"left",
			soloArrowLeftValign:"center",
			soloArrowLeftHOffset:20,
			soloArrowLeftVOffset:0,

			soloArrowRightHalign:"right",
			soloArrowRightValign:"center",
			soloArrowRightHOffset:20,
			soloArrowRightVOffset:0,

			parallax:"mouse",
			parallaxBgFreeze:"on",
			parallaxLevels:[7,4,3,2,5,4,3,2,1,0],

			shadow: parseInt(_shadow),
			fullWidth:"on",
			fullScreen:"off",

			stopLoop:"off",
			stopAfterLoops:-1,
			stopAtSlide:-1,

			spinner:"spinner0",
			shuffle:"off",

			autoHeight:"off",
			forceFullWidth:"off",

			hideThumbsOnMobile:"off",
			hideBulletsOnMobile:"on",
			hideArrowsOnMobile:"on",
			hideThumbsUnderResolution:0,

			hideSliderAtLimit:0,
			hideCaptionAtLimit:768,
			hideAllCaptionAtLilmit:0,
			startWithSlide:0,
			fullScreenOffsetContainer: ""
		});

		// Used by styleswitcher onle - delete this on production!
		jQuery("#is_wide, #is_boxed").bind("click", function() { revapi.revredraw(); });
	}


});	//ready
