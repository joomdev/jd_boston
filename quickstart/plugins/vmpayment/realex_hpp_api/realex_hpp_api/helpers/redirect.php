<?php
/**
 *
 * Realex payment plugin
 *
 * @author Valerie Isaksen
 * @version $Id: redirect.php 10130 2019-09-11 08:36:03Z Milbo $
 * @package VirtueMart
 * @subpackage payment
 * Copyright (C) 2004 - 2019 Virtuemart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */


defined('_JEXEC') or die('Restricted access');


class RealexHelperRealexRedirect extends RealexHelperRealex {


	function __construct ($method, $plugin) {
		parent::__construct($method, $plugin);


	}

	public function confirmedOrder (&$postRequest) {
		$selectedCCParams = array();
		if (!$this->doRealvault($selectedCCParams)) {
			$response = $this->sendPostRequest();
			$postRequest = true;
		} else {
			$response = $this->realvaultReceiptIn($selectedCCParams);
		}
		return $response;
	}


	function doRealVault (&$selectedCCParams) {
		//$saved_cc_selected = $this->customerData->getVar('saved_cc_selected');
		//$selectedCCParams = $this->getSelectedCCParams($saved_cc_selected, $this->cart->virtuemart_paymentmethod_id);
		$doRealVault = false;

		if (!JFactory::getUser()->guest AND $this->_method->realvault and $this->getStoredCCs(JFactory::getUser()->id)) {
			//if (!$selectedCCParams->addNew) {
			$doRealVault = true;
			//}
		}
		$this->debugLog((int)$doRealVault, 'Realex doRealVault:', 'debug');
		return $doRealVault;
	}


	function sendPostRequest () {
		$post_variables = $this->getPostVariables();

		$jump_url = $this->getJumpUrl();

		$html = '';
		if ($this->_method->debug) {
			$html .= '<form action="' . $jump_url . '" method="post" name="vm_realex_form" target="realex">';
		} else {
			if (VmConfig::get('css')) {
				$msg = vmText::_('VMPAYMENT_REALEX_HPP_API_REDIRECT_MESSAGE', true);
			} else {
				$msg='';
			}

			vmJsApi::addJScript('vm.paymentFormAutoSubmit', '
  			jQuery(document).ready(function($){
   				jQuery("body").addClass("vmLoading");
  				var msg="'.$msg.'";
   				jQuery("body").append("<div class=\"vmLoadingDiv\"><div class=\"vmLoadingDivMsg\">"+msg+"</div></div>");
    			jQuery("#vmPaymentForm").submit();
			})
		');
			$html .= '<form action="' . $jump_url . '" method="post" name="vm_realex_form" id="vmPaymentForm" accept-charset="UTF-8">';
		}
		$html .= '<input type="hidden" name="charset" value="utf-8">';

		foreach ($post_variables as $name => $value) {
			$html .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
		}

		if ($this->_method->debug) {

			$html .= '<div style="background-color:red;color:white;padding:10px;">
						<input type="submit"  value="The method is in debug mode. Click here to be redirected to Realex" />
						</div>';
			$this->debugLog($post_variables, 'sendPostRequest:', 'debug');

		}
		$html .= '</form>';

		return $html;
	}

	function getPostVariables () {

		$BT = $this->order['details']['BT'];
		$ST = ((isset($this->order['details']['ST'])) ? $this->order['details']['ST'] : $this->order['details']['BT']);


		// prepare postdata
		$post_variables = array();
		$post_variables['MERCHANT_ID'] = $this->_method->merchant_id;
		$post_variables['ACCOUNT'] = $this->_method->subaccount;
		$post_variables['ORDER_ID'] = $BT->order_number;
		$post_variables['AMOUNT'] = $this->getTotalInPaymentCurrency();
		$post_variables['CURRENCY'] = $this->getPaymentCurrency();
		$post_variables['LANG'] = $this->getPaymentLang();
		$post_variables['TIMESTAMP'] = $this->getTimestamp();
		$post_variables['DCC_ENABLE'] = $this->_method->dcc;

//		$post_variables['COMMENT1'] = $this->setComment1();
//		$post_variables['COMMENT2'] = 'virtuemart-rlx';
		
		$post_variables['MERCHANT_RESPONSE_URL'] = JURI::root() . 'index.php?option=com_virtuemart&format=raw&view=pluginresponse&task=pluginnotification&notificationTask=handleRedirect&tmpl=component';
		$post_variables['AUTO_SETTLE_FLAG'] = $this->getSettlement();

		if ($BT->virtuemart_user_id != 0) {
			//$post_variables['VAR_REF'] = $BT->order_number;
			$post_variables['CARD_STORAGE_ENABLE'] = $this->_method->realvault;
			if ($this->_method->realvault) {
				$payerRef = $this->getSavedPayerRef();
				if (!$payerRef) {
					$post_variables['PAYER_EXIST'] = 0;
					$post_variables['PMT_REF'] = '';
					$post_variables['PAYER_REF'] = $this->getNewPayerRef();
				} else {
					$post_variables['PAYER_REF'] = $payerRef;
					$post_variables['PAYER_EXIST'] = 1;
					$saved_cc_selected = $this->customerData->getVar('saved_cc_selected');
					// -1: use another card, empty no CC are stored
					if ($saved_cc_selected == -1 or empty($saved_cc_selected)) {
						$post_variables['PMT_REF'] = '';
					} else {
						$post_variables['PMT_REF'] = $this->getPmtRef();
					}
				}

				$post_variables['OFFER_SAVE_CARD'] = $this->_method->offer_save_card;

			} else {
				$post_variables['OFFER_SAVE_CARD'] = 0;
			}
		} else {
			$post_variables['OFFER_SAVE_CARD'] = 0;
			$post_variables['CARD_STORAGE_ENABLE'] = 0;
		}

		if ($this->_method->card_payment_button) {
			$post_variables['CARD_PAYMENT_BUTTON'] = $this->getCardPaymentButton($this->_method->card_payment_button);
		}

		if ($this->_method->realvault and $BT->virtuemart_user_id != 0) {
			$post_variables['SHA1HASH'] = $this->getSha1Hash($this->_method->shared_secret, $post_variables['TIMESTAMP'], $post_variables['MERCHANT_ID'], $post_variables['ORDER_ID'], $post_variables['AMOUNT'], $post_variables['CURRENCY'], $post_variables['PAYER_REF'], $post_variables['PMT_REF']);
		} else {
			$post_variables['SHA1HASH'] = $this->getSha1Hash($this->_method->shared_secret, $post_variables['TIMESTAMP'], $post_variables['MERCHANT_ID'], $post_variables['ORDER_ID'], $post_variables['AMOUNT'], $post_variables['CURRENCY']);
		}

		// use_tss? if uk
		if ($this->_method->tss) {
			$post_variables['RETURN_TSS'] = 1; // Transaction Suitability Score
			// <digits from postcode>|<digits from address>
			$post_variables['BILLING_CODE'] = $this->getCode($BT);
			$post_variables['BILLING_CO'] = ShopFunctions::getCountryByID($BT->virtuemart_country_id, 'country_2_code');

			$post_variables['SHIPPING_CODE'] = $this->getCode($ST);
			$post_variables['SHIPPING_CO'] = ShopFunctions::getCountryByID($ST->virtuemart_country_id, 'country_2_code');

		}

		$post_variables['gateway_url'] = $this->_getRealexUrl();
		
		$additionalHppData = array(
			"HPP_VERSION" => 2,
			"COMMENT1"    => "virtuemart"
		);
		
		// billing address is guaranteed to be present
		$billingAddress           = $BT;
		
		$additionalHppData[] = array(
			// customer fields
			"HPP_CUSTOMER_EMAIL" => $billingAddress->email ?: '',
			// Magento 1 does not have any phone number
		);
		
		$phoneCodes         = $this->getCountryPhoneCodes();
		$billingPhoneNumber = $billingAddress->phone_1 ?: ($billingAddress->phone_2 ?: false);
		$billingCountryAlpha2Code = ShopFunctions::getCountryByID($billingAddress->virtuemart_country_id, 'country_2_code');
		if ($billingPhoneNumber && $billingCountryAlpha2Code && isset($phoneCodes[$billingCountryAlpha2Code])) {
			$phoneCode = $phoneCodes[$billingCountryAlpha2Code];
			
			$formattedPhoneNumber = preg_replace("/^0+|[^\d]/", '', $billingPhoneNumber);
			if (substr($formattedPhoneNumber, 0, strlen($phoneCode)) === $phoneCode) {
				$formattedPhoneNumber = substr($formattedPhoneNumber, strlen($phoneCode));
			}
			
			if (is_string($formattedPhoneNumber)) {
				$additionalHppData["HPP_CUSTOMER_PHONENUMBER_MOBILE"] = $phoneCode . '|' . $formattedPhoneNumber;
			}
		}
		
		$hppBillingFields    = array(
			"HPP_BILLING_STREET1"    => $billingAddress->address_1,
			"HPP_BILLING_STREET2"    => $billingAddress->address_2,
			"HPP_BILLING_STREET3"    => '',
			"HPP_BILLING_CITY"       => $billingAddress->city,
			"HPP_BILLING_STATE"      => $billingCountryAlpha2Code && in_array($billingCountryAlpha2Code, array('US', 'CA')) ? ShopFunctions::getStateByID($billingAddress->virtuemart_state_id, 'state_2_code') : '',
			"HPP_BILLING_POSTALCODE" => $billingAddress->zip,
			"HPP_BILLING_COUNTRY"    => $billingCountryAlpha2Code ? $this->getCountryNumericCode($billingCountryAlpha2Code) : '',
		);
		$additionalHppData[] = $hppBillingFields;
		
		$isOrderVirtual = false;
		
		$shippingAddress           = isset($this->order['details']['ST']) ? $this->order['details']['ST'] : false;
		$shippingCountryAlpha2Code = ShopFunctions::getCountryByID($shippingAddress->virtuemart_country_id, 'country_2_code');
		
		$hppShippingFields = array(
			"HPP_SHIPPING_STREET1"    => !$isOrderVirtual && $shippingAddress ? $shippingAddress->address_1 : '',
			"HPP_SHIPPING_STREET2"    => !$isOrderVirtual && $shippingAddress ? $shippingAddress->address_2 : '',
			"HPP_SHIPPING_STREET3"    => '',
			"HPP_SHIPPING_CITY"       => !$isOrderVirtual && $shippingAddress ? $shippingAddress->city : '',
			"HPP_SHIPPING_STATE"      => !$isOrderVirtual && $shippingAddress && $shippingCountryAlpha2Code
				? (in_array($shippingCountryAlpha2Code, array('US', 'CA')) ? ShopFunctions::getStateByID($shippingAddress->virtuemart_state_id, 'state_2_code') : '')
				: '',
			"HPP_SHIPPING_POSTALCODE" => !$isOrderVirtual && $shippingAddress ? $shippingAddress->zip : '',
			"HPP_SHIPPING_COUNTRY"    => !$isOrderVirtual && $shippingAddress && $shippingCountryAlpha2Code ? $this->getCountryNumericCode($shippingCountryAlpha2Code) : ''
		);
		
		
		// order and type matter
		if (array_values($hppBillingFields) === array_values($hppShippingFields)) {
			$additionalHppData["HPP_ADDRESS_MATCH_INDICATOR"] = "TRUE";
			$additionalHppData[]                              = $hppShippingFields;
		} else {
			$additionalHppData["HPP_ADDRESS_MATCH_INDICATOR"] = "FALSE";
			$additionalHppData[]                              = $hppShippingFields;
		}
		
		foreach ($additionalHppData as $additionalHppProp => $additionalHppValue) {
			if (is_array($additionalHppValue)) {
				foreach ($additionalHppValue as $additionalHppPropChild => $additionalHppValueChild) {
					$post_variables[$additionalHppPropChild] = $additionalHppValueChild;
				}
			} else {
				$post_variables[$additionalHppProp] = $additionalHppValue;
			}
		}

		return $post_variables;

	}

	/**
	 * @param $realex_data
	 * @return bool
	 */
	function cardStorageResponse ($realex_data) {
		$userfield=false;
		if (isset($realex_data['REALWALLET_CHOSEN']) and  $realex_data['REALWALLET_CHOSEN'] == 0) {
			return false;
		}

		if (isset($realex_data['PAYER_SETUP']) and  $realex_data['PAYER_SETUP'] != self::PAYER_SETUP_SUCCESS) {
			$this->debugLog('cardStorageResponse PAYER_SETUP not successfull:' . $realex_data['PAYER_SETUP'] . ' ' . $realex_data['PAYER_SETUP_MSG'], 'debug');
			return false;
		}
		if ((isset($realex_data['PAYER_SETUP']) and  $realex_data['PAYER_SETUP'] == self::PAYER_SETUP_SUCCESS)) {
			$this->saveNewPayerRef($realex_data['SAVED_PAYER_REF']);
		}

		if ((isset($realex_data['PMT_SETUP']) and  $realex_data['PMT_SETUP'] != self::PMT_SETUP_SUCCESS)) {
			$this->debugLog('cardStorageResponse PMT_SETUP not successfull:' . $realex_data['PMT_SETUP'] . ' ' . $realex_data['PMT_SETUP_MSG'], 'debug');
			return false;
		}
		if ((isset($realex_data['PMT_SETUP']) and  $realex_data['PMT_SETUP'] == self::PMT_SETUP_SUCCESS)) {
			$userfield = $this->getPaymentRef($realex_data);
			//$this->storeNewPayment($userfield);
		}
		return $userfield;
	}


	/**
	 * @param $realex_data
	 * @return mixed
	 */
	function getPaymentRef ($realex_data) {

		$fields = array(
			'SAVED_PMT_TYPE',
			'SAVED_PMT_REF',
			'SAVED_PMT_DIGITS',
			'SAVED_PMT_EXPDATE',
			'SAVED_PMT_NAME',
		);
		$userfield['virtuemart_user_id'] = $this->order['details']['BT']->virtuemart_user_id;
		$userfield['merchant_id'] = $this->_method->merchant_id;
		foreach ($fields as $field) {
			if (isset($realex_data[$field])) {
				if ($field == 'SAVED_PMT_DIGITS') {
					$realex_data[$field] = shopFunctionsF::mask_string($realex_data[$field], '*');
				}
				$userfield['realex_hpp_api_' . strtolower($field)] = $realex_data[$field];
			}
		}
		return $userfield;
	}

	/**
	 * @param bool $enqueueMessage
	 * @return bool
	 */

	function validateConfirmedOrder ($enqueueMessage = true) {

		return $this->validate();

	}

	/**
	 * @param bool $enqueueMessage
	 * @return bool
	 */
	public function validate ($enqueueMessage = true) {
		if (!JFactory::getUser()->guest AND $this->_method->realvault) {
			if ($storedCCs = $this->getStoredCCs(JFactory::getUser()->id)) {
				$saved_cc_selected = $this->customerData->getVar('saved_cc_selected');
				if ($this->customerData->getVar('selected_method') AND empty($saved_cc_selected)) {
					vmInfo('VMPAYMENT_REALEX_HPP_API_PLEASE_SELECT_OPTION');
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * @param bool $enqueueMessage
	 * @return bool
	 */
	public function validateSelectCheckPayment ($enqueueMessage = true) {
		return $this->validate();
	}

	/**
	 * @return bool
	 */
	function validateCheckoutCheckDataPayment () {
		return $this->validate();
	}

	function getExtraPluginInfo () {
		$extraPluginInfo = array();
		$saved_cc_selected = $this->customerData->getVar('saved_cc_selected');
		if ($saved_cc_selected != -1) {
			$selected_cc = $this->getSelectedCCParams($saved_cc_selected);
			if (!empty($selected_cc)) {
				$extraPluginInfo['cc_type'] = $selected_cc->realex_hpp_api_saved_pmt_type;
				$extraPluginInfo['cc_number'] = $selected_cc->realex_hpp_api_saved_pmt_digits;
				$extraPluginInfo['cc_name'] = $selected_cc->realex_hpp_api_saved_pmt_name;

				$extraPluginInfo['cc_expire_month'] = "";
				$extraPluginInfo['cc_expire_year'] = "";
			}
		} else {
			$extraPluginInfo['cc_number'] = vmText::_('VMPAYMENT_REALEX_HPP_API_USE_ANOTHER_CC');
		}


		return $extraPluginInfo;
	}

	/**
	 * Validate the response hash from Realex.
	 * timestamp.merchantid.orderid.amount.curr.payerref.pmtref
	 */
	function validateResponseHash ($post) {
		if (is_array($post)) {
			$message = stripslashes($post['MESSAGE']);
			$message = str_replace('&#39;', "'", $message);
			$hash = $this->getSha1Hash($this->_method->shared_secret, $post['TIMESTAMP'], $post['MERCHANT_ID'], $post['ORDER_ID'], $post['RESULT'], $message, isset($post['PASREF']) ? $post['PASREF'] : "", isset($post['AUTHCODE']) ? $post['AUTHCODE'] : "");
			if ($hash != $post['SHA1HASH']) {
				$this->debugLog('validateResponseHash :' . var_export($post, true), 'debug');
				//$this->displayError(vmText::sprintf('VMPAYMENT_REALEX_HPP_API_ERROR_WRONG_HASH', $hash, print_r($post, true)));
				//echo vmText::sprintf('VMPAYMENT_REALEX_HPP_API_ERROR_WRONG_HASH', $hash, $post['SHA1HASH']);
				//print_r($_POST);
				return FALSE;
			}
		} else {
			return parent::validateResponseHash($post);
		}


		return true;
	}

	function setComment1 () {
		$amountValue = vmPSPlugin::getAmountInCurrency($this->order['details']['BT']->order_total, $this->order['details']['BT']->order_currency);
		$currencyDisplay = CurrencyDisplay::getInstance($this->cart->pricesCurrency);

		$shop_name = $this->getVendorInfo('vendor_store_name');
		return vmText::sprintf('VMPAYMENT_REALEX_HPP_API_COMMENT1', $amountValue['display'], $this->order['details']['BT']->order_number, $shop_name);
	}



	/**
	 * JumpUrl is a prefined URL that must be configurated in Realex
	 * @return string
	 */
	function getJumpUrl () {
		return $this->_method->referring_url;

	}
	
	/**
	 * @return array
	 */
	private function getCountryNumericCodes()
	{
		return array(
			'AF' => '004',
			'AX' => '248',
			'AL' => '008',
			'DZ' => '012',
			'AS' => '016',
			'AD' => '020',
			'AO' => '024',
			'AI' => '660',
			'AQ' => '010',
			'AG' => '028',
			'AR' => '032',
			'AM' => '051',
			'AW' => '533',
			'AU' => '036',
			'AT' => '040',
			'AZ' => '031',
			'BS' => '044',
			'BH' => '048',
			'BD' => '050',
			'BB' => '052',
			'BY' => '112',
			'BE' => '056',
			'BZ' => '084',
			'BJ' => '204',
			'BM' => '060',
			'BT' => '064',
			'BO' => '068',
			'BQ' => '535',
			'BA' => '070',
			'BW' => '072',
			'BV' => '074',
			'BR' => '076',
			'IO' => '086',
			'BN' => '096',
			'BG' => '100',
			'BF' => '854',
			'BI' => '108',
			'CV' => '132',
			'KH' => '116',
			'CM' => '120',
			'CA' => '124',
			'KY' => '136',
			'CF' => '140',
			'TD' => '148',
			'CL' => '152',
			'CN' => '156',
			'CX' => '162',
			'CC' => '166',
			'CO' => '170',
			'KM' => '174',
			'CG' => '178',
			'CD' => '180',
			'CK' => '184',
			'CR' => '188',
			'CI' => '384',
			'HR' => '191',
			'CU' => '192',
			'CW' => '531',
			'CY' => '196',
			'CZ' => '203',
			'DK' => '208',
			'DJ' => '262',
			'DM' => '212',
			'DO' => '214',
			'EC' => '218',
			'EG' => '818',
			'SV' => '222',
			'GQ' => '226',
			'ER' => '232',
			'EE' => '233',
			'ET' => '231',
			'SZ' => '748',
			'FK' => '238',
			'FO' => '234',
			'FJ' => '242',
			'FI' => '246',
			'FR' => '250',
			'GF' => '254',
			'PF' => '258',
			'TF' => '260',
			'GA' => '266',
			'GM' => '270',
			'GE' => '268',
			'DE' => '276',
			'GH' => '288',
			'GI' => '292',
			'GR' => '300',
			'GL' => '304',
			'GD' => '308',
			'GP' => '312',
			'GU' => '316',
			'GT' => '320',
			'GG' => '831',
			'GN' => '324',
			'GW' => '624',
			'GY' => '328',
			'HT' => '332',
			'HM' => '334',
			'VA' => '336',
			'HN' => '340',
			'HK' => '344',
			'HU' => '348',
			'IS' => '352',
			'IN' => '356',
			'ID' => '360',
			'IR' => '364',
			'IQ' => '368',
			'IE' => '372',
			'IM' => '833',
			'IL' => '376',
			'IT' => '380',
			'JM' => '388',
			'JP' => '392',
			'JE' => '832',
			'JO' => '400',
			'KZ' => '398',
			'KE' => '404',
			'KI' => '296',
			'KP' => '408',
			'KR' => '410',
			'KW' => '414',
			'KG' => '417',
			'LA' => '418',
			'LV' => '428',
			'LB' => '422',
			'LS' => '426',
			'LR' => '430',
			'LY' => '434',
			'LI' => '438',
			'LT' => '440',
			'LU' => '442',
			'MO' => '446',
			'MK' => '807',
			'MG' => '450',
			'MW' => '454',
			'MY' => '458',
			'MV' => '462',
			'ML' => '466',
			'MT' => '470',
			'MH' => '584',
			'MQ' => '474',
			'MR' => '478',
			'MU' => '480',
			'YT' => '175',
			'MX' => '484',
			'FM' => '583',
			'MD' => '498',
			'MC' => '492',
			'MN' => '496',
			'ME' => '499',
			'MS' => '500',
			'MA' => '504',
			'MZ' => '508',
			'MM' => '104',
			'NA' => '516',
			'NR' => '520',
			'NP' => '524',
			'NL' => '528',
			'NC' => '540',
			'NZ' => '554',
			'NI' => '558',
			'NE' => '562',
			'NG' => '566',
			'NU' => '570',
			'NF' => '574',
			'MP' => '580',
			'NO' => '578',
			'OM' => '512',
			'PK' => '586',
			'PW' => '585',
			'PS' => '275',
			'PA' => '591',
			'PG' => '598',
			'PY' => '600',
			'PE' => '604',
			'PH' => '608',
			'PN' => '612',
			'PL' => '616',
			'PT' => '620',
			'PR' => '630',
			'QA' => '634',
			'RE' => '638',
			'RO' => '642',
			'RU' => '643',
			'RW' => '646',
			'BL' => '652',
			'SH' => '654',
			'KN' => '659',
			'LC' => '662',
			'MF' => '663',
			'PM' => '666',
			'VC' => '670',
			'WS' => '882',
			'SM' => '674',
			'ST' => '678',
			'SA' => '682',
			'SN' => '686',
			'RS' => '688',
			'SC' => '690',
			'SL' => '694',
			'SG' => '702',
			'SX' => '534',
			'SK' => '703',
			'SI' => '705',
			'SB' => '090',
			'SO' => '706',
			'ZA' => '710',
			'GS' => '239',
			'SS' => '728',
			'ES' => '724',
			'LK' => '144',
			'SD' => '729',
			'SR' => '740',
			'SJ' => '744',
			'SE' => '752',
			'CH' => '756',
			'SY' => '760',
			'TW' => '158',
			'TJ' => '762',
			'TZ' => '834',
			'TH' => '764',
			'TL' => '626',
			'TG' => '768',
			'TK' => '772',
			'TO' => '776',
			'TT' => '780',
			'TN' => '788',
			'TR' => '792',
			'TM' => '795',
			'TC' => '796',
			'TV' => '798',
			'UG' => '800',
			'UA' => '804',
			'AE' => '784',
			'GB' => '826',
			'US' => '840',
			'UM' => '581',
			'UY' => '858',
			'UZ' => '860',
			'VU' => '548',
			'VE' => '862',
			'VN' => '704',
			'VG' => '092',
			'VI' => '850',
			'WF' => '876',
			'EH' => '732',
			'YE' => '887',
			'ZM' => '894',
			'ZW' => '716',
		);
	}
	
	private function getCountryPhoneCodes() {
		return array (
			'AD' => '376',
			'AE' => '971',
			'AF' => '93',
			'AG' => '1268',
			'AI' => '1264',
			'AL' => '355',
			'AM' => '374',
			'AN' => '599',
			'AO' => '244',
			'AQ' => '672',
			'AR' => '54',
			'AS' => '1684',
			'AT' => '43',
			'AU' => '61',
			'AW' => '297',
			'AZ' => '994',
			'BA' => '387',
			'BB' => '1246',
			'BD' => '880',
			'BE' => '32',
			'BF' => '226',
			'BG' => '359',
			'BH' => '973',
			'BI' => '257',
			'BJ' => '229',
			'BL' => '590',
			'BM' => '1441',
			'BN' => '673',
			'BO' => '591',
			'BR' => '55',
			'BS' => '1242',
			'BT' => '975',
			'BW' => '267',
			'BY' => '375',
			'BZ' => '501',
			'CA' => '1',
			'CC' => '61',
			'CD' => '243',
			'CF' => '236',
			'CG' => '242',
			'CH' => '41',
			'CI' => '225',
			'CK' => '682',
			'CL' => '56',
			'CM' => '237',
			'CN' => '86',
			'CO' => '57',
			'CR' => '506',
			'CU' => '53',
			'CV' => '238',
			'CX' => '61',
			'CY' => '357',
			'CZ' => '420',
			'DE' => '49',
			'DJ' => '253',
			'DK' => '45',
			'DM' => '1767',
			'DO' => '1809',
			'DZ' => '213',
			'EC' => '593',
			'EE' => '372',
			'EG' => '20',
			'ER' => '291',
			'ES' => '34',
			'ET' => '251',
			'FI' => '358',
			'FJ' => '679',
			'FK' => '500',
			'FM' => '691',
			'FO' => '298',
			'FR' => '33',
			'GA' => '241',
			'GB' => '44',
			'GD' => '1473',
			'GE' => '995',
			'GH' => '233',
			'GI' => '350',
			'GL' => '299',
			'GM' => '220',
			'GN' => '224',
			'GQ' => '240',
			'GR' => '30',
			'GT' => '502',
			'GU' => '1671',
			'GW' => '245',
			'GY' => '592',
			'HK' => '852',
			'HN' => '504',
			'HR' => '385',
			'HT' => '509',
			'HU' => '36',
			'ID' => '62',
			'IE' => '353',
			'IL' => '972',
			'IM' => '44',
			'IN' => '91',
			'IQ' => '964',
			'IR' => '98',
			'IS' => '354',
			'IT' => '39',
			'JM' => '1876',
			'JO' => '962',
			'JP' => '81',
			'KE' => '254',
			'KG' => '996',
			'KH' => '855',
			'KI' => '686',
			'KM' => '269',
			'KN' => '1869',
			'KP' => '850',
			'KR' => '82',
			'KW' => '965',
			'KY' => '1345',
			'KZ' => '7',
			'LA' => '856',
			'LB' => '961',
			'LC' => '1758',
			'LI' => '423',
			'LK' => '94',
			'LR' => '231',
			'LS' => '266',
			'LT' => '370',
			'LU' => '352',
			'LV' => '371',
			'LY' => '218',
			'MA' => '212',
			'MC' => '377',
			'MD' => '373',
			'ME' => '382',
			'MF' => '1599',
			'MG' => '261',
			'MH' => '692',
			'MK' => '389',
			'ML' => '223',
			'MM' => '95',
			'MN' => '976',
			'MO' => '853',
			'MP' => '1670',
			'MR' => '222',
			'MS' => '1664',
			'MT' => '356',
			'MU' => '230',
			'MV' => '960',
			'MW' => '265',
			'MX' => '52',
			'MY' => '60',
			'MZ' => '258',
			'NA' => '264',
			'NC' => '687',
			'NE' => '227',
			'NG' => '234',
			'NI' => '505',
			'NL' => '31',
			'NO' => '47',
			'NP' => '977',
			'NR' => '674',
			'NU' => '683',
			'NZ' => '64',
			'OM' => '968',
			'PA' => '507',
			'PE' => '51',
			'PF' => '689',
			'PG' => '675',
			'PH' => '63',
			'PK' => '92',
			'PL' => '48',
			'PM' => '508',
			'PN' => '870',
			'PR' => '1',
			'PT' => '351',
			'PW' => '680',
			'PY' => '595',
			'QA' => '974',
			'RO' => '40',
			'RS' => '381',
			'RU' => '7',
			'RW' => '250',
			'SA' => '966',
			'SB' => '677',
			'SC' => '248',
			'SD' => '249',
			'SE' => '46',
			'SG' => '65',
			'SH' => '290',
			'SI' => '386',
			'SK' => '421',
			'SL' => '232',
			'SM' => '378',
			'SN' => '221',
			'SO' => '252',
			'SR' => '597',
			'ST' => '239',
			'SV' => '503',
			'SY' => '963',
			'SZ' => '268',
			'TC' => '1649',
			'TD' => '235',
			'TG' => '228',
			'TH' => '66',
			'TJ' => '992',
			'TK' => '690',
			'TL' => '670',
			'TM' => '993',
			'TN' => '216',
			'TO' => '676',
			'TR' => '90',
			'TT' => '1868',
			'TV' => '688',
			'TW' => '886',
			'TZ' => '255',
			'UA' => '380',
			'UG' => '256',
			'US' => '1',
			'UY' => '598',
			'UZ' => '998',
			'VA' => '39',
			'VC' => '1784',
			'VE' => '58',
			'VG' => '1284',
			'VI' => '1340',
			'VN' => '84',
			'VU' => '678',
			'WF' => '681',
			'WS' => '685',
			'XK' => '381',
			'YE' => '967',
			'YT' => '262',
			'ZA' => '27',
			'ZM' => '260',
			'ZW' => '263',
		);
	}
	
	/**
	 * @param $alpha2
	 *
	 * @return mixed|string
	 */
	private function getCountryNumericCode($alpha2)
	{
		$countries = $this->getCountryNumericCodes();
		
		return isset($countries[$alpha2]) ? $countries[$alpha2] : '';
	}
}
