/**
 * vmvalidator.js: set country chosen dropdowns to required
 *
 * @package	VirtueMart
 * @subpackage Javascript Library
 * @author Max Milbers
 * @copyright Copyright (c) 2014-18 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

	function setDropdownRequiredByResult(id,prefiks){
		//console.log('setDropdownRequiredByResult '+prefiks+id);
		var results = 0;

		var cField = jQuery('#'+prefiks+id+'_field');
		if(typeof cField!=='undefined' && cField.length > 0){
			var lField = jQuery('[for="'+prefiks+id+'_field"]');
			var chznField = jQuery('#'+prefiks+id+'_field_chzn');

			if(chznField.length > 0) {
			// in case of chznFields
				results = chznField.find('.chzn-results li').length;
			} else {
				//native selectboxes
				results = cField.find('option').length;
			}

			if(results<2){
				cField.removeClass('required');
				cField.removeAttr('required');

				if (typeof lField!=='undefined') {
					lField.removeClass('invalid');
					lField.attr('aria-invalid', 'false');
					//console.log('Remove invalid lfield',id);
				}
			} else if(cField.attr('aria-required')=='true'){
				cField.addClass('required');
				cField.attr('required','required');

				lField.addClass('invalid');
				lField.attr('aria-invalid', 'true');
			}
		}
	}

	function setChznRequired(id,prefiks){
		//console.log('setChznRequired ',id);
		var cField = jQuery('#'+prefiks+id+'_field');
		if(typeof cField!=='undefined' && cField.length > 0){

			var chznField = jQuery('#'+prefiks+id+'_field_chzn');
			if(chznField.length > 0) {
				var aField = chznField.find('a');
				var lField = jQuery('[for="'+prefiks+id+'_field"]');

				if(cField.attr('aria-invalid')=='true'){
					//console.log('setChznRequired set invalid');
					aField.addClass('invalid');
					lField.addClass('invalid');
				} else {
					//console.log('setChznRequired set valid');
					aField.removeClass('invalid');
					lField.removeClass('invalid');
				}
			}
		}
	}
	
		function myValidator(f, r) {

		var regfields = Virtuemart.regfields;

		var requ = '';
		if(r == true){
			requ = 'required';
		}

		if (typeof regfields !== 'undefined') {
			for (i = 0; i < regfields.length; i++) {
				var elem = jQuery('#' + regfields[i] + '_field');
				elem.attr('class', requ);
			}
		}

		setDropdownRequiredByResult('virtuemart_country_id','');
		setDropdownRequiredByResult('virtuemart_state_id','');

		var prefiks = Virtuemart.prefiks;
		if(prefiks!=''){
			setDropdownRequiredByResult('virtuemart_country_id',prefiks);
			setDropdownRequiredByResult('virtuemart_state_id',prefiks);
		}

		if (document.formvalidator.isValid(f)) {
			if (jQuery('#recaptcha_wrapper').is(':hidden') && (r == true)) {
				jQuery('#recaptcha_wrapper').show();
			} else {
				return true;	//sents the form, we dont use js.submit()
			}
		} else {
			setChznRequired('virtuemart_country_id','');
			setChznRequired('virtuemart_state_id','');
			if(prefiks!=''){
				setChznRequired('virtuemart_country_id',prefiks);
				setChznRequired('virtuemart_state_id',prefiks);
			}
			if (jQuery('#recaptcha_wrapper').is(':hidden') && (r == true)) {
				jQuery('#recaptcha_wrapper').show();
			}
			var msg = Virtuemart.requiredMsg;
			alert(msg + ' ');
		}
		return false;
	}
