<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Valérie Isaksen
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the VirtueMart Component
 *
 * @package		VirtueMart
 * @author
 */
class VirtuemartViewOrders extends VmViewAdmin {

	function display($tpl = null) {

		$app = JFactory::getApplication();
		$orderStatusModel=VmModel::getModel('orderstatus');
		$orderStates = $orderStatusModel->getOrderStatusList(true);

		$this->SetViewTitle( 'ORDER');

		$orderModel = VmModel::getModel();

		$this->lists['search'] = $orderModel->get('search', '');

		$curTask = vRequest::getCmd('task');
		if ($curTask == 'edit') {
			vmLanguage::loadJLang('com_virtuemart_shoppers',TRUE);
			vmLanguage::loadJLang('com_virtuemart_orders', true);

			// Load addl models
			$userFieldsModel = VmModel::getModel('userfields');

			// Get the data
			$virtuemart_order_id = vRequest::getInt('virtuemart_order_id');
			$order = $orderModel->getOrder($virtuemart_order_id);

			if(empty($order['details'])){
				$app->redirect('index.php?option=com_virtuemart&view=orders',vmText::_('COM_VIRTUEMART_ORDER_NOTFOUND'));;
			}

			$_orderID = $order['details']['BT']->virtuemart_order_id;
			$orderbt = $order['details']['BT'];
//			quorvia consider whether the store wants to populate empty ST address with the BT address
			if (($order['details']['has_ST'] == true OR VmConfig::get('populateEmptyST', 1 ) == 1) and isset($order['details']['ST']) ) {
				$orderst = $order['details']['ST'];
			}
			$invM = VmModel::getModel('invoice');
			$orderbt ->invoiceNumbers = $invM->getInvoiceNumbers($orderbt->virtuemart_order_id);

			$currency = CurrencyDisplay::getInstance(0,$order['details']['BT']->virtuemart_vendor_id);

			$this->assignRef('currency', $currency);

			$_userFields = $userFieldsModel->getUserFields(
					 'account'
					, array('captcha' => true, 'delimiters' => true) // Ignore these types
					, array('delimiter_userinfo','user_is_vendor' ,'username','name','password', 'password2', 'agreed', 'address_type') // Skips
			);
			$userFieldsCart = $userFieldsModel->getUserFields(
				'cart'
				, array('captcha' => true, 'delimiters' => true) // Ignore these types
				, array('delimiter_userinfo','user_is_vendor' ,'username','password', 'password2', 'agreed', 'address_type') // Skips
			);
			$_userFields = array_merge($userFieldsCart,$_userFields);

			//Fallback for customer_note
			if(empty($orderbt->customer_note) and !empty($orderbt->oc_note)){
				$orderbt->customer_note = $orderbt->oc_note;
			}

			$userfields = $userFieldsModel->getUserFieldsFilled(
					 $_userFields
					,$orderbt
					,'BT_'
			);

			$_userFields = $userFieldsModel->getUserFields(
					 'shipment'
					, array() // Default switches
					, array('delimiter_userinfo', 'username', 'email', 'password', 'password2', 'agreed', 'address_type') // Skips
			);

			$shipmentfields = $userFieldsModel->getUserFieldsFilled(
					 $_userFields
					,$orderst
					,'ST_'
			);


			$_orderStatusList = array();
			$orderStatesUnpaid = array();
			$os_trigger_refunds = VmConfig::get('os_trigger_refunds', array('R'));
			$this->adjustOrderStatuslists($orderStates, $_orderStatusList, $orderStatesUnpaid);

			$_itemStatusUpdateFields = array();
			$_itemAttributesUpdateFields = array();

			$attr = array('class' => 'selectItemStatusCode', 'style' => 'width:160px');
			// for the order item template
			$_itemStatusUpdateFields[0] = JHtml::_('select.genericlist', $orderStates, "item_id[0][order_status]", $attr, 'order_status_code', 'order_status_name', 'P', 'order_item_status_0',true);


			$this->toRefund = array();
			$orderbt->toPay = floatval($orderbt->order_total);
			foreach($order['items'] as $i => $_item) {


				$order['items'][$i]->linkedit = 'index.php?option=com_virtuemart&view=product&task=edit&virtuemart_product_id='.$_item->virtuemart_product_id;

				//I dont like this solution, but it would need changing how an item is loaded
				$order['items'][$i]->tax_rule_id = array();
				foreach($order['calc_rules'] as $r =>$rule){
					if( $rule->virtuemart_order_item_id == $_item->virtuemart_order_item_id){

						if( $rule->calc_kind == 'VatTax' or $rule->calc_kind == 'Tax'){
							//$order['items'][$i]->tax_rule[$rule->virtuemart_calc_id] = $rule;
							$order['items'][$i]->tax_rule_id[] = $rule->virtuemart_calc_id;
						}
						//$order['items'][$i]->calc_rules[$rule->virtuemart_calc_id] = $rule;

					}

				}

				if(in_array($_item->order_status,$os_trigger_refunds)){
					$this->toRefund[] = $_item;
					$orderbt->toPay -= $this->currency->roundByPriceConfig($_item->product_subtotal_with_tax);
				}

				if($orderbt->paid < $orderbt->toPay){
					$usedOStates = $orderStatesUnpaid;
				} else {
					$usedOStates = $orderStates;
				}

				$_itemStatusUpdateFields[$_item->virtuemart_order_item_id] = JHtml::_('select.genericlist', $usedOStates, "item_id[".$_item->virtuemart_order_item_id."][order_status]", $attr, 'order_status_code', 'order_status_name', $_item->order_status, 'order_item_status'.$_item->virtuemart_order_item_id,true);

			}
			$orderbt->toPay = $this->currency->roundByPriceConfig(($orderbt->toPay));


			$rulesSorted = shopFunctionsF::summarizeRulesForBill($order);
			$this->discountsBill = $rulesSorted['discountsBill'];
			$this->taxBill = $rulesSorted['taxBill'];
//vmdebug('my taxBill rules',$this->taxBill);
			if(!isset($_orderStatusList[$orderbt->order_status])){
				if(empty($orderbt->order_status)){
					$orderbt->order_status = 'unknown';
				}
				$_orderStatusList[$orderbt->order_status] = vmText::sprintf('COM_VIRTUEMART_UNKNOWN_ORDER_STATUS',$orderbt->order_status);
			}

			VmModel::getModel('calc');
			$this->taxList = array();
			$this->taxList[0] = array('text'=>vmText::_('COM_VIRTUEMART_PRODUCT_TAX_NONE'), 'value'=>0);
			$taxes = VirtueMartModelCalc::getTaxes();
			if($taxes){
				foreach ($taxes as $i=>$tax) {
					$this->taxList[$tax->virtuemart_calc_id] = array('text'=>vmText::_($tax->calc_name), 'value'=>$tax->virtuemart_calc_id);
				}
			}

			/* Assign the data */
			$this->assignRef('orderdetails', $order);
			$this->assignRef('orderID', $_orderID);
			$this->assignRef('userfields', $userfields);
			$this->assignRef('shipmentfields', $shipmentfields);
			$this->assignRef('orderstatuslist', $_orderStatusList);
			$this->assignRef('itemstatusupdatefields', $_itemStatusUpdateFields);
			$this->assignRef('itemattributesupdatefields', $_itemAttributesUpdateFields);
			$this->assignRef('orderbt', $orderbt);
			$this->assignRef('orderst', $orderst);
			$this->assignRef('virtuemart_shipmentmethod_id', $orderbt->virtuemart_shipmentmethod_id);

			/* Data for the Edit Status form popup */
			$_currentOrderStat = $order['details']['BT']->order_status;
			// used to update all item status in one time
			$_orderStatusSelect = JHtml::_('select.genericlist', $orderStates, 'order_status', 'style="width:200px;"', 'order_status_code', 'order_status_name', $_currentOrderStat, 'order_items_status',true);
			$this->assignRef('orderStatSelect', $_orderStatusSelect);
			$this->assignRef('currentOrderStat', $_currentOrderStat);

			/* Toolbar */
			if (JVM_VERSION < 3) { $backward="back"; $list='back';} else {$backward='backward';$list='list';}
			JToolbarHelper::custom( 'prevItem', $backward,'','COM_VIRTUEMART_ITEM_PREVIOUS',false);
			JToolbarHelper::custom( 'nextItem', 'forward','','COM_VIRTUEMART_ITEM_NEXT',false);
			JToolbarHelper::divider();
			JToolbarHelper::custom( 'cancel', $list,'','COM_VIRTUEMART_ORDER_LIST_LBL',false,false);
			self::showhelp();
		}
		else if ($curTask == 'editOrderItem') {

			$this->assignRef('orderstatuses', $orderStates);

			$model = VmModel::getModel();
			$orderId = vRequest::getString('orderId', '');
			$orderLineItem = vRequest::getVar('orderLineId', '');
			$this->assignRef('virtuemart_order_id', $orderId);
			$this->assignRef('virtuemart_order_item_id', $orderLineItem);

			$orderItem = $model->getOrderLineDetails($orderId, $orderLineItem);
			$this->assignRef('orderitem', $orderItem);
		}
		else {
			$this->setLayout('orders');

			$model = VmModel::getModel();
			$this->addStandardDefaultViewLists($model,'created_on');
			$orderStatusModel =VmModel::getModel('orderstatus');
			$order_status_code = vRequest::getCmd('order_status_code','');
			$this->lists['state_list'] = $orderStatusModel->renderOSList($order_status_code,'order_status_code',FALSE,' onchange="this.form.submit();" style="width:180px;"');
			$this->lists['bulk_state_list'] = $orderStatusModel->renderOSList($order_status_code,'order_status_code_bulk',FALSE,'id="order_status_code_bulk" onchange="Virtuemart.set2status();" style="width:180px;"');

			$this->orderStatesUnpaid = array();
			$_orderStatusList= array();
			$this->adjustOrderStatuslists($orderStates, $_orderStatusList, $this->orderStatesUnpaid);
			//$this->lists['state_list_unpaid'] = $orderStatusModel->renderOSList($orderStatesUnpaid,'order_status_code',FALSE,' onchange="this.form.submit();" style="width:180px;"');

			$orderslist = $model->getOrdersList();

			$this->assignRef('orderstatuses', $orderStates);
			$this->orderStatesColors=array();
			foreach($orderStates as $orderState) {
				$this->orderStatesColors[$orderState->order_status_code]=$orderState->order_status_color;
			}


			// quorvia added for display colors
			$shipmentMethodModel=VmModel::getModel('shipmentmethod');
			$shipmentMethodModel->_noLimit = true;
			$shipmentMethods = $shipmentMethodModel->getShipments(false);
			$this->shipmentColors=array();
			foreach($shipmentMethods as $shipmentMethod) {
				if (!empty($shipmentMethod->display_color)) {
					$this->shipmentColors[$shipmentMethod->virtuemart_shipmentmethod_id] = $shipmentMethod->display_color;
				}
			}

			$this->lists['vendors']='';
			if($this->showVendors()){
			//	$this->lists['vendors'] = Shopfunctions::renderVendorList(userstate missing, 'virtuemart_vendor_id', true);
			}

			/* Apply currency This must be done per order since it's vendor specific */
			$_currencies = array(); // Save the currency data during this loop for performance reasons

			if ($orderslist) {
				$invM = VmModel::getModel('invoice');
			    foreach ($orderslist as $virtuemart_order_id => $order) {

				    if(!empty($order->order_currency)){
					    $currency = $order->order_currency;
				    } else {
						$vId = empty($order->virtuemart_vendor_id)? 1:$order->virtuemart_vendor_id;
						$currObj = VirtueMartModelVendor::getVendorCurrency($vId);
						$currency = $currObj->virtuemart_currency_id;
					}
				    //This is really interesting for multi-X, but I avoid to support it now already, lets stay it in the code
				    if (!array_key_exists('curr'.$currency, $_currencies)) {
					    $_currencies['curr'.$currency] = CurrencyDisplay::getInstance($currency,$order->virtuemart_vendor_id);
				    }

					$orderslist[$virtuemart_order_id]->order_total = $_currencies['curr'.$currency]->priceDisplay($order->order_total);

					$orderslist[$virtuemart_order_id]->invoiceNumbers = $invM->getInvoiceNumbers($order->virtuemart_order_id);
			    }

			}

			//update order items button
			/*$q = 'SELECT * FROM #__virtuemart_order_items WHERE `product_discountedPriceWithoutTax` IS NULL ';
			$db = JFactory::getDBO();
			$db->setQuery($q);
			//$res = $db->loadRow();
			if(true) {
				JToolbarHelper::custom('updateCustomsOrderItems', 'new', 'new', vmText::_('COM_VIRTUEMART_REPORT_UPDATEORDERITEMS'),false);
				vmError('COM_VIRTUEMART_UPDATEORDERITEMS_WARN');
			}*/
			/*
			 * UpdateStatus removed from the toolbar; don't understand how this was intented to work but
			 * the order ID's aren't properly passed. Might be readded later; the controller needs to handle
			 * the arguments.
			 */

			/* Toolbar */
			//JToolbarHelper::customX( 'CreateOrderHead', 'new','new','New',false);

			JToolbarHelper::save('updatestatus', vmText::_('COM_VIRTUEMART_UPDATE_STATUS'));

			if (vmAccess::manager('orders.delete') && !VmConfig::get('ordersAddOnly',false)) {
				JToolbarHelper::spacer('80');
				JToolbarHelper::deleteList();
			}
			self::showhelp();
			/* Assign the data */
			$this->assignRef('orderslist', $orderslist);

			$this->pagination = $model->getPagination();

		}
		if(VmConfig::isSiteByApp()) {
			$bar = JToolBar::getInstance( 'toolbar' );
			$bar->appendButton( 'Link', 'back', 'COM_VIRTUEMART_LEAVE', 'index.php?option=com_virtuemart&manage=0' );
		}

		shopFunctions::checkSafePathBase();

		parent::display($tpl);
	}

	function adjustOrderStatuslists ($orderStates, &$_orderStatusList, &$orderStatesUnpaid){

		$os_trigger_refunds = VmConfig::get('os_trigger_refunds', array('R'));
		// Create an array to allow orderlinestatuses to be translated
		// We'll probably want to put this somewhere in ShopFunctions...

		foreach ($orderStates as $orderState) {
			//$_orderStatusList[$orderState->virtuemart_orderstate_id] = $orderState->order_status_name;
			//When I use update, I have to use this?
			$_orderStatusList[$orderState->order_status_code] = vmText::_($orderState->order_status_name);
			$tmp = clone($orderState);
			if(in_array($orderState->order_status_code,$os_trigger_refunds)) {

				$tmp->order_status_name = 'Unrecommended '.vmText::_( $orderState->order_status_name );
				$orderStatesUnpaid[] = $tmp;
			} else {
				$orderStatesUnpaid[] = $tmp;
			}

		}

	}
	/**
	 * @author Max Milbers
	 * @author Valérie Isaksen
	 * @param $order
	 * @param $print_link
	 * @param $deliverynote_link
	 * @param $invoice_links
	 */
	function createPrintLinks($order,&$print_link,&$deliverynote_link,&$invoice_links){

		$baseUrl = 'index.php?option=com_virtuemart&view=orders&task=callInvoiceView&tmpl=component&virtuemart_order_id=' . $order->virtuemart_order_id;
		/* Print view URL */
		$print_url = $baseUrl .'&layout=invoice';
		$print_link = "<a href=\"javascript:void window.open('$print_url', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');\"  >";
		$print_link .= '<span class="hasTooltip print_32" title="' . vmText::_ ('COM_VIRTUEMART_PRINT').' '. $order->order_number.'">&nbsp;</span></a>';
		$invoice_links_array = array();
		$deliverynote_link = '';
		$pdfDummi= '&d='.rand(0,100);
		if (!$order->invoiceNumbers) {
			$invoice_url = $baseUrl .'&layout=invoice&format=pdf&create_invoice='.$order->order_create_invoice_pass.$pdfDummi;
			$invoice_links_array[]= "<a href=\"$invoice_url\"  >".'<span class="hasTooltip invoicenew_32" title="' . vmText::_ ('COM_VIRTUEMART_INVOICE_CREATE') . '"></span></a>';
		} else {
			foreach ($order->invoiceNumbers as $invoiceNumber) {
				if (!shopFunctions::InvoiceNumberReserved ($invoiceNumber)) {
					$invoice_url = $baseUrl .'&layout=invoice&format=pdf'.$pdfDummi.'&invoiceNumber='.$invoiceNumber;
					$invoice_links_array[] = "<a href=\"$invoice_url\"  >" . '<span class="hasTooltip invoice_32" title="' . vmText::_ ('COM_VIRTUEMART_INVOICE') .' '.$invoiceNumber. '"></span></a>';
				}
			}
		}

		if (!$order->invoiceNumbers) {
			$deliverynote_url = $baseUrl .'&layout=deliverynote&format=pdf&create_invoice='.$order->order_create_invoice_pass.$pdfDummi;
			$deliverynote_link = "<a href=\"$deliverynote_url\"  >" . '<span class="hasTooltip deliverynotenew_32" title="' . vmText::_ ('COM_VIRTUEMART_DELIVERYNOTE_CREATE') . '"></span></a>';
		} else {
			/*
			 * TODO: InvoiceNumberReserved is used by some payments like Klarna
			 */
			$invoiceNumber= $order->invoiceNumbers [0];
			if (!shopFunctionsF::InvoiceNumberReserved ($invoiceNumber)) {
				$deliverynote_url = $baseUrl .'&layout=deliverynote&format=pdf&virtuemart_order_id=' . $order->virtuemart_order_id .$pdfDummi;
				$deliverynote_link = "<a href=\"$deliverynote_url\"  >" . '<span class="hasTooltip deliverynote_32" title="' . vmText::_ ('COM_VIRTUEMART_DELIVERYNOTE').' '.$invoiceNumber . '"></span></a>';
		}

		}
		$invoice_links=implode(' ', $invoice_links_array);
	}
}
