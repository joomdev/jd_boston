jQuery(function($) {
	// fix container's height
	if ($("#content").length) {
		h = $(window).height() - $("#content").position().top + 10;
		$(".nr-main-container").css({
			"min-height" : h
		});
	}

	  jQuery("#menuspecial").specialmenu();
})



    jQuery(function($){ initPopovers(); $("body").on("subform-row-add", initPopovers); function initPopovers (event, container) { $(container || document).find(".hasPopover").popover({"html": true,"trigger": "hover focus","container": "body"});} });
		
		
