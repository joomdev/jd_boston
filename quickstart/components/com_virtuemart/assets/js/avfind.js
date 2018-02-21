/**
 * cvfind.js: Find product by dropdown 
 *
 * @package	VirtueMart
 * @subpackage Javascript Library
 * @author Max Milbers
 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

if (typeof Virtuemart === "undefined")
	Virtuemart = {};

Virtuemart.avFind = function(event) {
	event.preventDefault();

	var runs= 0, maxruns = 20;
	//We ensure with this, to get the right product, if more than one is displayed
	var container = jQuery(event.currentTarget);
	while(!container.hasClass('product-field-display') && runs<=maxruns){
		container = container.parent();
		runs++;
	}
	if(runs>maxruns){
		console.log('Could not find parent');
		return false;
	}
	Virtuemart.container = container;


	var cl = 'product-container';
	Virtuemart.containerSelector = '.'+cl;
	while(!Virtuemart.container.hasClass(cl)){
		Virtuemart.container = Virtuemart.container.parent();
	}
	//console.log('my new ajax container ',Virtuemart.container);
	url = false;
	found = false;
	//We check first if it is a radio
	jQuery(container).find('.avselection:checked').each(function() {
		found = true;
		url = jQuery(this).attr('url');
		if (typeof url === typeof undefined || url === false) {
			url = jQuery(this).val();
		}
		jQuery(this).val(url);
	});
	if(!found){
		jQuery(container).find('.avselection').each(function() {
			url = jQuery(this).attr('url');
			if (typeof url === typeof undefined || url === false) {
				url = jQuery(this).val();
			}
			jQuery(this).val(url);
		});
	}

	//Virtuemart.setBrowserNewState(url);
	//Virtuemart.updateContent(url);

	return url;

};


