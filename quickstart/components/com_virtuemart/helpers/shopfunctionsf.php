<?php
/**
 *
 * Contains shop functions for the front-end
 *
 * @package    VirtueMart
 * @subpackage Helpers
 *
 * @author Max Milbers
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2015 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: shopfunctionsf.php 10278 2020-03-03 18:13:26Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die('Restricted access');


class shopFunctionsF {

	static public function getLoginForm ($cart = FALSE, $order = FALSE, $url = '', $layout = 'login') {

		$body = '';
		$show = TRUE;

		if($cart) {
			$show = VmConfig::get( 'oncheckout_show_register', 1 );
		}
		if($show == 1) {
			//This is deprecated and will be replaced by the commented lines below (vmView instead of VirtuemartViewUser)
			//$view = new VirtuemartViewUser();
			$view = new vmView();
			$body = $view->renderVmSubLayout($layout,array('show' => $show, 'order' => $order, 'from_cart' => $cart, 'url' => $url));
		}

		return $body;
	}

	static public function getLastVisitedCategoryId ($default = 0) {
		$session = JFactory::getSession();
		return $session->get( 'vmlastvisitedcategoryid', $default, 'vm' );
	}

	static public function setLastVisitedCategoryId ($categoryId) {
		$session = JFactory::getSession();
		return $session->set( 'vmlastvisitedcategoryid', (int)$categoryId, 'vm' );
	}

	static public function getLastVisitedItemId ($default = 0) {
		$session = JFactory::getSession();
		return $session->get( 'vmlastvisitedItemid', $default, 'vm' );
	}

	static public function setLastVisitedItemId ($id) {
		$session = JFactory::getSession();
		return $session->set( 'vmlastvisitedItemid', (int)$id, 'vm' );
	}

	static public function getLastVisitedManuId () {
		$session = JFactory::getSession();
		return $session->get( 'vmlastvisitedmanuid', 0, 'vm' );
	}

	static public function setLastVisitedManuId ($manuId) {
		$session = JFactory::getSession();
		return $session->set( 'vmlastvisitedmanuid', (int)$manuId, 'vm' );
	}

	/**
	 * @param $orderable
	 * @return string
	 * @deprecated
	 */
	static public function getAddToCartButton ($orderable) {
		return self::renderVmSubLayout('addtocartbtn',array('orderable'=>$orderable));
	}

	static public function isFEmanager ($task = 0) {

        return vmAccess::isFEmanager($task);

	}

	/**
	 * Just an idea, still WIP
	 * @param $type
	 * @return mixed
	 */
	static function renderFormField($type){
		//Get custom field
		JFormHelper::addFieldPath(VMPATH_ADMIN .'/fields');
		$types = JFormHelper::loadFieldType($type, false);
		return $types->getOptions();
	}

	/**
	 * Return the order status name for a given code
	 *
	 * @author Oscar van Eijk
	 * @access public
	 *
	 * @param char $_code Order status code
	 * @return string The name of the order status
	 */
	static public function getOrderStatusName ($_code) {

		static $orderNames = array();
		$db = JFactory::getDBO ();
		$_code = $db->escape ($_code);
		if(!isset($orderNames[$_code])){
			$_q = 'SELECT `order_status_name` FROM `#__virtuemart_orderstates` WHERE `order_status_code` = "' . $_code . '"';
			$db->setQuery ($_q);
			$orderNames[$_code] = $db->loadObject ();
			if (empty($orderNames[$_code]->order_status_name)) {
				vmError ('getOrderStatusName: couldnt find order_status_name for ' . $_code);
				return 'current order status broken';
			} else {
				$orderNames[$_code] = vmText::_($orderNames[$_code]->order_status_name);
			}
		}

		return $orderNames[$_code];
	}

	/**
	 * Render a simple country list
	 *
	 * @author jseros, Max Milbers, Valérie Isaksen
	 *
	 * @param int $countryId Selected country id
	 * @param boolean $multiple True if multiple selections are allowed (default: false)
	 * @param mixed $_attrib string or array with additional attributes,
	 * e.g. 'onchange=somefunction()' or array('onchange'=>'somefunction()')
	 * @param string $_prefix Optional prefix for the formtag name attribute
	 * @return string HTML containing the <select />
	 */
	static public function renderCountryList ($countryId = 0, $multiple = FALSE, $_attrib = array(), $_prefix = '', $required = 0, $idTag = 'virtuemart_country_id', $name = 'virtuemart_country_id') {

		$countryModel = VmModel::getModel ('country');
		$countries = $countryModel->getCountries (TRUE, TRUE, FALSE);
		$attrs = array();
		$optText = 'country_name';
		$optKey = 'virtuemart_country_id';
		$name = $_prefix.$name;
		$idTag = $_prefix.$idTag;
		$attrs['class'] = 'virtuemart_country_id';
		$attrs['class'] = 'vm-chzn-select';

		// Load helpers and  languages files
		vmLanguage::loadJLang('com_virtuemart_countries');
		vmJsApi::jQuery();
		vmJsApi::chosenDropDowns();

		$countries_list=array();
		$lang = vmLanguage::getLanguage();
		$prefix="COM_VIRTUEMART_COUNTRY_";

		$countries_list = shopfunctionsF::kSortUmlaut($countries, $prefix, 'country_3_code', 'country_name', $optKey, $optText);

		if ($required != 0) {
			$attrs['class'] .= ' required';
		}

		if ($multiple) {
			$attrs['multiple'] = 'multiple';
			$name .= '[]';
		} else {
			$emptyOption = JHtml::_ ('select.option', '', vmText::_ ('COM_VIRTUEMART_LIST_EMPTY_OPTION'), $optKey, $optText);
			array_unshift ($countries_list, $emptyOption);
		}

		if (is_array ($_attrib)) {
			$attrs = array_merge ($attrs, $_attrib);
		} else {
			$_a = explode ('=', $_attrib, 2);
			$attrs[$_a[0]] = $_a[1];
		}

		return JHtml::_ ('select.genericlist', $countries_list, $name, $attrs, $optKey, $optText, $countryId, $idTag);
	}

    static function kSortUmlaut($objArray, $prefix, $code, $name, $optKey, $optText){

		$lang = vmLanguage::getLanguage();
		$aSearch   = array("Ä","ä","Ö","ö","Ü","ü","ß","-");
		$aReplace  = array("Ae","ae","Oe","oe","Ue","ue","ss"," ");
		$ret = array();
		foreach ($objArray as  $obj) {
			$trValue = $lang->hasKey($prefix.$obj->country_3_code) ?   vmText::_($prefix.$obj->{$code})  : $obj->{$name};

			$ckey = 0;
			if($obj->ordering){
				$ckey = $obj->ordering;
			} else {
				$ckey = str_replace($aSearch, $aReplace, $trValue);// $country_string;
			}
			//vmdebug('we had here '.$ckey,$objArray[$ckey]);
			$ret[$ckey] = new stdClass();;
			$ret[$ckey]->{$optKey} = $obj->virtuemart_country_id;
			$ret[$ckey]->{$optText} = $trValue;
		}

		ksort($ret);
		return $ret;
    }

	/**
	 * Render a simple state list
	 *
	 * @author Max Milbers, Valerie Isaksen
	 *
	 * @param int $stateID Selected state id
	 * @param int $countryID Selected country id
	 * @param string $dependentField Parent <select /> ID attribute
	 * @param string $_prefix Optional prefix for the formtag name attribute
	 * @return string HTML containing the <select />
	 */
	static public function renderStateList ($stateId = '0', $_prefix = '', $multiple = FALSE, $required = 0,$attribs=array(),$idTag = 'virtuemart_state_id', $suffix='_field') {

		if (is_array ($stateId)) {
			$stateId = implode (",", $stateId);
		}

		vmJsApi::JcountryStateList ($stateId,$_prefix, $suffix);

		if(!isset($attrs['class'])){
			$attrs['class'] = '';
		}
		if(!empty($required)){
			$attrs['class'] .= ' required';
		}
		$attrs['class'] .= ' vm-chzn-select';
		if ($multiple) {
			$attrs['name'] = $_prefix . 'virtuemart_state_id[]';
			$attrs['multiple'] = 'multiple';
		} else {
			$attrs['name'] = $_prefix . 'virtuemart_state_id';
		}

		if (is_array ($attribs)) {
			$attrs = array_merge ($attrs, $attribs);
		}

		$attrString= JArrayHelper::toString($attrs);
		$listHTML = '<select  id="'.$_prefix.$idTag.'" ' . $attrString . '>
						<option value="">' . vmText::_ ('COM_VIRTUEMART_LIST_EMPTY_OPTION') . '</option>
						</select>';

		return $listHTML;
	}

	/**
	 * This generates the list when the user have different ST addresses saved
	 *
	 * @author Max Milbers
	 */
	static function generateStAddressList ($view, $userModel, $task) {

		// Shipment address(es)
		$_addressList = $userModel->getUserAddressList ($userModel->getId (), 'ST');
		if (count ($_addressList) == 1 && empty($_addressList[0]->address_type_name)) {
			return vmText::_ ('COM_VIRTUEMART_USER_NOSHIPPINGADDR');
		} else {
			$_shipTo = array();
			$useXHTTML = empty($view->useXHTML) ? false : $view->useXHTML;
			$useSSL = empty($view->useSSL) ? FALSE : $view->useSSL;

			for ($_i = 0; $_i < count ($_addressList); $_i++) {
				if (empty($_addressList[$_i]->virtuemart_user_id)) {
					$_addressList[$_i]->virtuemart_user_id = JFactory::getUser ()->id;
				}
				if (empty($_addressList[$_i]->virtuemart_userinfo_id)) {
					$_addressList[$_i]->virtuemart_userinfo_id = 0;
				}
				if (empty($_addressList[$_i]->address_type_name)) {
					$_addressList[$_i]->address_type_name = 0;
				}

				$_shipTo[] = '<li>' . '<a href="index.php'
					. '?option=com_virtuemart'
					. '&view=user'
					. '&task=' . $task
					. '&addrtype=ST'
					. '&virtuemart_user_id[]=' . $_addressList[$_i]->virtuemart_user_id
					. '&virtuemart_userinfo_id=' . $_addressList[$_i]->virtuemart_userinfo_id
					. '">' . $_addressList[$_i]->address_type_name . '</a> ' ;
				$_shipTo[] = '&nbsp;&nbsp;<a href="'.JRoute::_ ('index.php?option=com_virtuemart&view=user&task=removeAddressST&virtuemart_user_id[]=' . $_addressList[$_i]->virtuemart_user_id . '&virtuemart_userinfo_id=' . $_addressList[$_i]->virtuemart_userinfo_id.'&'.JSession::getFormToken().'=1', $useXHTTML, $useSSL ). '" >'.'<i class="icon-delete"></i>'.vmText::_('COM_VIRTUEMART_USER_DELETE_ST').'</a></li>';
			}

			$addLink = '<a href="' . JRoute::_ ('index.php?option=com_virtuemart&view=user&task=' . $task . '&new=1&addrtype=ST&virtuemart_user_id[]=' . $userModel->getId ().'&'.JSession::getFormToken().'=1', $useXHTTML, $useSSL) . '"><span class="vmicon vmicon-16-editadd"></span> ';
			$addLink .= vmText::_ ('COM_VIRTUEMART_USER_FORM_ADD_SHIPTO_LBL') . ' </a>';

			return $addLink . '<ul>' . join ('', $_shipTo) . '</ul>';
		}
	}


	/**
	 * used mostly in the email, to display the vendor address
	 * Attention, this function will be removed from any view.html.php
	 *
	 * @static
	 * @param        $vendorId
	 * @param string $lineSeparator
	 * @param array  $skips
	 * @return string
	 */
	static public function renderVendorAddress ($vendorId,$lineSeparator="<br />", $skips = array('name','username','email','agreed')) {

		$vendorModel = VmModel::getModel('vendor');
		$vendorFields = $vendorModel->getVendorAddressFields($vendorId);

		$vendorAddress = '';
		foreach ($vendorFields['fields'] as $field) {
			if(in_array($field['name'],$skips)) continue;
			if (!empty($field['value'])) {
				$vendorAddress .= $field['value'];
				if ($field['name'] != 'title' and $field['name'] != 'first_name' and $field['name'] != 'middle_name' and $field['name'] != 'zip') {
					$vendorAddress .= $lineSeparator;
				} else {
					$vendorAddress .= ' ';
				}
			}
		}
		return $vendorAddress;
	}


	/**
	 *
	 * @author Max Milbers
	 */
	static public function addProductToRecent ($productId) {

		$session = JFactory::getSession();
		$products_ids = $session->get( 'vmlastvisitedproductids', array(), 'vm' );
		$key = array_search( $productId, $products_ids );
		if($key !== FALSE) {
			unset($products_ids[$key]);
		}
		array_unshift( $products_ids, $productId );
		$products_ids = array_unique( $products_ids );

		$maxSize = (int)VmConfig::get('max_recent_products', 10);
		if(count( $products_ids )>$maxSize) {
			array_splice( $products_ids, $maxSize );
		}

		return $session->set( 'vmlastvisitedproductids', $products_ids, 'vm' );
	}

	/**
	 * Gives ids the recently by the shopper visited products
	 *
	 * @author Max Milbers
	 */
	static public function getRecentProductIds ($nbr = 3) {

		$session = JFactory::getSession();
		$ids = $session->get( 'vmlastvisitedproductids', array(), 'vm' );
		if(count( $ids )>$nbr) {
			array_splice( $ids, $nbr );
		}
		return $ids;
	}

	static public function sortLoadProductCustomsStockInd(&$products,$pModel){

		if(!$products) return;
		$customfieldsModel = VmModel::getModel ('Customfields');

		foreach($products as $i => $productItem){

			if (!empty($productItem->customfields)) {
				$product = clone($productItem);
				$customfields = array();
				foreach($productItem->customfields as $cu){
					$customfields[] = clone ($cu);
				}

				$customfieldsSorted = array();
				$customfieldsModel -> displayProductCustomfieldFE ($product, $customfields);
				$product->stock = $pModel->getStockIndicator($product);
				foreach ($customfields as $k => $custom) {
					if (!empty($custom->layout_pos)  ) {
						$customfieldsSorted[$custom->layout_pos][] = $custom;
					} else {
						$customfieldsSorted['normal'][] = $custom;
					}
					unset($customfields[$k]);
				}

				$product->customfieldsSorted = $customfieldsSorted;
				unset($product->customfields);
				$products[$i] = $product;
			} else {

				$productItem->stock = $pModel->getStockIndicator($productItem);
				$products[$i] = $productItem;
			}
		}
	}

	static public function calculateProductRowsHeights($products,$currency,$products_per_row){

		$rowsHeight = array();
		if(!$products) return $rowsHeight;

		$col = 1;
		$nb = 1;
		$row = 1;
		$BrowseTotalProducts = count($products);
		$rowHeights = array();


		foreach($products as $product){

			$priceRows = 0;
			//Lets calculate the height of the prices
			foreach($currency->_priceConfig as $name=>$values){
				if(!empty($currency->_priceConfig[$name][0])){
					if(!empty($product->prices[$name]) or $name == 'billTotal' or $name == 'billTaxAmount'){
						$priceRows++;
					}
				}
			}
			$rowHeights[$row]['price'][] = $priceRows;
			$position = 'addtocart';
			if(!empty($product->customfieldsSorted[$position])){

				//Hack for Multi variants
				$mvRows = 0;$i=0;
				foreach($product->customfieldsSorted[$position] as $custom){
					if($custom->field_type=='C'){
						//vmdebug('my custom',$custom);
						$mvRows += count($custom->selectoptions);
						$i++;
					}
				}
				$customs = count($product->customfieldsSorted[$position]);
				if(!empty($mvRows)){
					$customs = $customs - $i +$mvRows;
				}
			} else {
				$customs = 0;
			}
			$position = 'ontop';
			if(!empty($product->customfieldsSorted[$position])){
				foreach($product->customfieldsSorted[$position] as $custom){
					if($custom->field_type=='A'){
						$customs++;
					}
				}
			}

			$rowHeights[$row]['customfields'][] = $customs;
			$rowHeights[$row]['product_s_desc'][] = empty($product->product_s_desc)? 0:1;
			$rowHeights[$row]['avail'][] = empty($product->product_availability)? 0:1;

			$nb ++;

			if ($col == $products_per_row || $nb>$BrowseTotalProducts) {

				foreach($rowHeights[$row] as $group => $cols){

					$rowsHeight[$row][$group] = 0;
					foreach($cols as $c){
						$rowsHeight[$row][$group] =  max($rowsHeight[$row][$group],$c);
					}

				}
				$col = 1;
				$rowHeights = array();
				$row++;
			} else {
				$col ++;
			}

		}

		return $rowsHeight;
	}

	/**
	 * Renders sublayouts
	 *
	 * @param $name
	 * @param int $viewData viewdata for the rendered sublayout, do not remove
	 * @return string
	 */
	static public function renderVmSubLayout($name,$viewData=0){

		$lPath = VmView::getVmSubLayoutPath ($name);

		if($lPath){
			ob_start ();
			include ($lPath);
			return ob_get_clean();
		} else {
			vmdebug('renderVmSubLayout layout not found '.$name);
		}

	}

    /**
     * renders sub layout in a bootstrap grid layout
     *
     * @param     $name
     * @param int $viewData
     *
     * @since 3.8
     * @author Eugen Stranz
     */
    static public function renderVmSubLayoutAsGrid ($name, $viewData = 0)
    {
        // get the content of the first index in the array and save it in a variable
        // this variable will be used in the for each loop to generate the grid
        // we then delete the first index as there is no point in passing it twice
        reset($viewData);
        $itemCollection = $viewData[key($viewData)];
        unset($viewData[key($viewData)]);

        if(!isset($viewData['options']))
        {
            $viewData['options'] = array ();
        }

        // Grid Settings & Calculation
        $itemsPerRow              = vRequest::get(
            'items_per_row',
            array ( 'xs' => 1, 'sm' => 2, 'md' => 3, 'lg' => 3, 'xl' => 3 ),
            FILTER_UNSAFE_RAW,
            FILTER_FLAG_NO_ENCODE,
            $viewData['options']
        );
        $iRowItemsPerDevice       = array ( 'xs' => 0, 'sm' => 0, 'md' => 0, 'lg' => 0, 'xl' => 0 );
        $totalItems               = count($itemCollection);
        $iItems                   = 0;
        $gridClassNamesForNewLine = array (
            'xs' => 'col-12 d-block d-sm-none',
            'sm' => 'col-12 d-none d-sm-block d-md-none d-lg-none d-xl-none',
            'md' => 'col-12 d-none d-sm-none d-md-block d-lg-none d-xl-none',
            'lg' => 'col-12 d-none d-sm-none d-md-none d-lg-block d-xl-none',
            'xl' => 'col-12 d-none d-sm-none d-md-none d-lg-none d-xl-block',
        );
        $fixedColumnWidth         = vRequest::get(
            'fixed_column_width',
            false,
            FILTER_UNSAFE_RAW,
            FILTER_FLAG_NO_ENCODE,
            $viewData['options']
        );
        if ($fixedColumnWidth)
        {
            $columnClassNames         = array ();
            $possibleGridColumnWitdhs = array ( 1, 2, 3, 4, 6 );
            foreach ($itemsPerRow as $deviceSize => $itemPerRow)
            {
                if (in_array($itemPerRow, $possibleGridColumnWitdhs))
                {
                    $columnClassNames[] = ($deviceSize == 'xs')
                        ? 'col-' . (12 / $itemPerRow)
                        : 'col-' . $deviceSize . '-' . (12 / $itemPerRow);
                }
                else
                {
                    $columnClassNames[]       = ($deviceSize == 'xs')
                        ? 'col-4'
                        : 'col-' . $deviceSize . '-4';
                    $itemsPerRow[$deviceSize] = 3;
                }
            }
            $columnClassNames[] = 'd-flex';
        }
        else
        {
            // $columnClassNames = array ( 'col', 'd-flex' );
            $columnClassNames = array ( 'col' );
        }

        // Display Settings
        $showHorizontalLine      = vRequest::get(
            'show_horizontal_line',
            true,
            FILTER_UNSAFE_RAW,
            FILTER_FLAG_NO_ENCODE,
            $viewData['options']
        );
        $showVerticalLine        = vRequest::get(
            'show_vertical_line',
            true,
            FILTER_UNSAFE_RAW,
            FILTER_FLAG_NO_ENCODE,
            $viewData['options']
        );
        $addMarginBottomToColumn = vRequest::get(
            'add_margin_bottom_to_column',
            false,
            FILTER_UNSAFE_RAW,
            FILTER_FLAG_NO_ENCODE,
            $viewData['options']
        );
        if (!$showHorizontalLine)
        {
            $addMarginBottomToColumn = true;
        }
        else
        {
            $addMarginBottomToColumn = false;
        }

        // Output The Items
        ob_start();
        ?>
        <div class="row">
            <?php
            // Loop Through The Items Of The Collection
            foreach ($itemCollection as $item)
            {
                // Vertical Line Logic
                $newLineClassName = array ();
                if ($showVerticalLine)
                {
                    foreach ($iRowItemsPerDevice as $deviceSize => $iRowItem)
                    {
                        if (($iRowItemsPerDevice[$deviceSize] + 1) == $itemsPerRow[$deviceSize])
                        {
                            $newLineClassName[] = ' end-' . $deviceSize;
                        }
                        else
                        {
                            $newLineClassName[] = ' vl-' . $deviceSize;
                        }
                    }
                }
                ?>
                <div class="<?php echo implode(' ', $columnClassNames) . implode('', $newLineClassName) ?>">
                    <?php
                    $viewData[$name] = $item;
                    echo self::renderVmSubLayout($name, $viewData);
                    ?>
                </div>
                <?php
                $iItems++;

                // Logic For New Line Force
                foreach ($iRowItemsPerDevice as $deviceSize => $iRowItem)
                {
                    $iRowItemsPerDevice[$deviceSize]++;
                    if ($iRowItemsPerDevice[$deviceSize] == $itemsPerRow[$deviceSize]
                        && $iItems < $totalItems)
                    {
                        // Add Margin Bottom If We Horizontal Line is Disabled
                        if ($addMarginBottomToColumn)
                        {
                            $gridClassNamesForNewLine[$deviceSize] .= ($deviceSize == 'xs')
                                ? ' mb-4'
                                : ' mb-' . $deviceSize . '-4';
                        }
                        ?>
                        <div class="new-line <?php echo $gridClassNamesForNewLine[$deviceSize] ?>">
                            <?php if ($showHorizontalLine): ?>
                                <hr>
                            <?php endif ?>
                        </div>
                        <?php
                        $iRowItemsPerDevice[$deviceSize] = 0;
                    }
                }
            }
            ?>
        </div>
        <?php
        // Return Content And Clear Memory
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }


	/**
	 * Prepares a view for rendering email, then renders and sends
	 *
	 * @param object $controller
	 * @param string $viewName View which will render the email
	 * @param string $recipient shopper@whatever.com
	 * @param array $vars variables to assign to the view
	 */
	//TODO this is quirk, why it is using here $noVendorMail, but everywhere else it is using $doVendor => this make logic trouble
	static public function renderMail ($viewName, $recipient, $vars = array(), $controllerName = NULL, $noVendorMail = FALSE,$useDefault=true) {

		self::loadOrderLanguages();

		$view = self::prepareViewForMail($viewName, $vars, $controllerName);
		$user = self::sendVmMail( $view, $recipient, $noVendorMail );

		if(isset($view->doVendor) && !$noVendorMail) {
			//We need to ensure the language for the vendor here
			if(!empty($vars['virtuemart_vendor_id'])){
			    $vendorId = $vars['virtuemart_vendor_id'];
			} else {
				$vendorId = 1;
			}
			$vendorUserId = VmModel::getModel('vendor')->getUserIdByVendorId($vendorId);
			$vu = JFactory::getUser($vendorUserId);
			$vLang = $vu->getParam('admin_language',VmConfig::$jDefLangTag);

			vmLanguage::setLanguageByTag($vLang);
			self::sendVmMail( $view, $view->vendorEmail, TRUE );
		}

		return $user;

	}

	public static function prepareViewForMail($viewName, $vars, $controllerName = false) {

		if(!$controllerName) $controllerName = $viewName;
		$controllerClassName = 'VirtueMartController'.ucfirst( $controllerName );
		if(!class_exists( $controllerClassName )) require(VMPATH_SITE .'/controllers/'.$controllerName.'.php');
		$controller = new $controllerClassName();
		//$controller = new VirtueMartControllerVirtuemart();
		// refering to http://forum.virtuemart.net/index.php?topic=96318.msg317277#msg317277
		$controller->addViewPath( VMPATH_SITE .'/views' );

		$view = $controller->getView( $viewName, 'html' );

		//refering to http://forum.virtuemart.net/index.php?topic=96318.msg317277#msg317277
		$view->addTemplatePath( VMPATH_SITE.'/views/'.$viewName.'/tmpl' );

		$template = VmTemplate::loadVmTemplateStyle();
		VmTemplate::setTemplate($template);
		if($template and VmConfig::get('useLayoutOverrides',1)){
			if(is_array($template) and isset($template['template'])){
				$view->addTemplatePath( VMPATH_ROOT .'/templates/'.$template['template'].'/html/com_virtuemart/'.$viewName );
			} else {
				$view->addTemplatePath( VMPATH_ROOT .'/templates/'.$template.'/html/com_virtuemart/'.$viewName );
			}
		}

		foreach( $vars as $key => $val ) {
			$view->{$key} = $val;
		}

		return $view;
	}

	/**
	 * @deprecated use the class vmTemplate instead
	 * @return string
	 */
	public static function loadVmTemplateStyle(){

		static $res = null;
		if($res!==null) return $res;
		$res = VmTemplate::loadVmTemplateStyle();

	}


	/**
	 * This function sets the right template on the view
	 * @author Max Milbers
	 * @deprecated use class VmTemplates instead
	 */
	static function setVmTemplate ($view, $catTpl = 0, $prodTpl = 0, $catLayout = 0, $prodLayout = 0) {

		return VmTemplate::setVmTemplate($view, $catTpl, $prodTpl, $catLayout, $prodLayout);
	}

	/**
     * Loads lang files for the set language, new language system reloades all already loaded files automatically for the new language
	 * @param int $language
	 */
	static public function loadOrderLanguages($language = 0){

		vmLanguage::setLanguageByTag($language);

		$s = TRUE;
		$cache = TRUE;

		vmLanguage::loadJLang('com_virtuemart', 0, $language, $cache);
		vmLanguage::loadJLang('com_virtuemart', $s, $language, $cache);
		vmLanguage::loadJLang('com_virtuemart_shoppers', $s, $language, $cache);
		vmLanguage::loadJLang('com_virtuemart_orders', $s, $language, $cache);
	}


	/**
	 * With this function you can use a view to sent it by email.
	 * Just use a task in a controller
	 *
	 * @param string $view for example user, cart
	 * @param string $recipient shopper@whatever.com
	 * @param bool $vendor true for notifying vendor of user action (e.g. registration)
	 */

	public static function sendVmMail (&$view, $recipient, $noVendorMail = FALSE) {

		VmConfig::ensureMemoryLimit(96);

		ob_start();

		$view->renderMailLayout( $noVendorMail, $recipient );
		$body = ob_get_contents();
		ob_end_clean();

		$subject = (isset($view->subject)) ? $view->subject : vmText::_( 'COM_VIRTUEMART_DEFAULT_MESSAGE_SUBJECT' );
		$mailer = JFactory::getMailer();
		$mailer->addRecipient( $recipient );

		$subjectMailer= '=?utf-8?B?'.base64_encode($subject).'?=';
		$mailer->setSubject(  html_entity_decode( $subjectMailer , ENT_QUOTES, 'UTF-8') );
		$mailer->isHTML( VmConfig::get( 'order_mail_html', TRUE ) );
		$mailer->setBody( $body );
		$replyTo = array();
		$replyToName = array();
 
		if(!$noVendorMail) {
			$replyTo[0] = $view->vendorEmail;
			$replyToName[0] = $view->vendor->vendor_name;
		} else {
			if(isset($view->orderDetails['details']) && isset($view->orderDetails['details']['BT'])) {
				$replyTo[0] = $view->orderDetails['details']['BT']->email;
				$replyToName[0] = $view->orderDetails['details']['BT']->first_name . ' ' . $view->orderDetails['details']['BT']->last_name;
			} else {
				if(is_object($view->user)){
					$replyTo[0] = isset($view->user->email)? $view->user->email:false;
					$replyToName[0] = isset($view->user->name)? $view->user->name:false;
				} else {
					$replyTo[0] = isset($view->user['email'])? $view->user['email']:false;
					$replyToName[0] = isset($view->user['name'])? $view->user['name']:false;
				}
			}
		}
 
		if(count($replyTo)) {
			if(version_compare(JVERSION, '3.5', 'ge')) {
				$mailer->addReplyTo($replyTo, $replyToName);
			} else {
				$replyTo[1] = $replyToName[0];
				$mailer->addReplyTo($replyTo);
			}
		}

		// set proper sender
		$sender = array();
		if(!empty($view->vendorEmail) and VmConfig::get( 'useVendorEmail', 0 )) {
			$sender[0] = $view->vendorEmail;
			$sender[1] = $view->vendor->vendor_name;
		} else {
			// use default joomla's mail sender
			$app = JFactory::getApplication();
			$sender[0] = $app->getCfg( 'mailfrom' );
			$sender[1] = $app->getCfg( 'fromname' );
			if(empty($sender[0])){
				$config = JFactory::getConfig();
				$sender = array( $config->get( 'mailfrom' ), $config->get( 'fromname' ) );
			}
		}

		$mailer->setSender($sender);

		$mediaToSend = array();
		if(isset($view->mediaToSend)) {
			foreach( (array)$view->mediaToSend as $media ) {
				$mailer->addAttachment( $media );
			}
			$mediaToSend = $view->mediaToSend;
			$view->mediaToSend = array();
		}

		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('plgVmOnSendVmEmail',array(&$view,&$mailer,$noVendorMail));

		$debug_email = VmConfig::get('debug_mail', false);
		if (VmConfig::get('debug_mail', false) == '1') {
			$debug_email = 'debug_email';
		}

		if ($debug_email) {
			if (!is_array($recipient)) {
				$recipient = array($recipient);
			}
			$no = '';
			if ($debug_email == 'debug_email') {
				$no = 'no';
			}
			$msg = 'Debug mail active, '.$no.' mail sent. The mail to send subject ' . $subject . ' to "' . implode(' ', $recipient) . '" from ' . $sender[0] . ' ' . $sender[1] . ' ' . vmText::$language->getTag() . '<br>' . $body;
			if (VmConfig::showDebug()) {
				vmdebug($msg, $mediaToSend);
			} else {
				vmInfo($msg);
			}
			if ($debug_email == 'debug_email') {
				return true;
			}
		}
		try {
			$return = $mailer->Send();
		}
		catch (Exception $e)
		{
			VmConfig::$logDebug = true;
			vmdebug('Error sending mail ',$e);
			vmError('Error sending mail ');
			// this will take care of the error message
			return false;
		}


		return $return; 
	}




	function sendRatingEmailToVendor ($data) {

		$vars = array();
		$productModel = VmModel::getModel ('product');
		$product = $productModel->getProduct ($data['virtuemart_product_id']);
		$vars['subject'] = vmText::sprintf('COM_VIRTUEMART_RATING_EMAIL_SUBJECT', $product->product_name);
		$vars['mailbody'] = vmText::sprintf('COM_VIRTUEMART_RATING_EMAIL_BODY', $product->product_name);

		$vendorModel = VmModel::getModel ('vendor');
		$vendor = $vendorModel->getVendor ($product->virtuemart_vendor_id);
		$vendorModel->addImages ($vendor);
		$vars['vendor'] = $vendor;
		$vars['vendorEmail'] = $vendorModel->getVendorEmail ($product->virtuemart_vendor_id);
		$vars['vendorAddress'] = shopFunctionsF::renderVendorAddress ($product->virtuemart_vendor_id);

	    shopFunctionsF::renderMail ('productdetails', $vars['vendorEmail'], $vars, 'productdetails', TRUE);

	}

	static public function getTaxNameWithValue($name, $value){

		$value = rtrim(trim($value,'0'),'.');
		if(empty($value)) return $name;
		if(strpos($name,(string)$value)!==false){
			$tax = $name;
		} else {
			$tax = $name.' '.$value.'%';
		}
		return $tax;
	}

	/**
	 *
	 * Enter description here ...
	 * @author Max Milbers
	 * @author Iysov
	 * @param string $string
	 * @param int $maxlength
	 * @param string $suffix
	 */
	static public function limitStringByWord ($string, $maxlength, $suffix = '') {

		if(function_exists( 'mb_strlen' )) {
			// use multibyte functions by Iysov
			if(mb_strlen( $string )<=$maxlength) return $string;
			$string = mb_substr( $string, 0, $maxlength );
			$index = mb_strrpos( $string, ' ' );
			if($index === FALSE) {
				return $string;
			} else {
				return mb_substr( $string, 0, $index ).$suffix;
			}
		} else { // original code here
			if(strlen( $string )<=$maxlength) return $string;
			$string = substr( $string, 0, $maxlength );
			$index = strrpos( $string, ' ' );
			if($index === FALSE) {
				return $string;
			} else {
				return substr( $string, 0, $index ).$suffix;
			}
		}
	}

	static public function vmSubstr($str,$s,$e = null){
		if(function_exists( 'mb_substr' )) {
			return mb_substr( $str, $s, $e );
		} else {
			return substr( $str, $s, $e );
		}
	}

	/**
	 * Admin UI Tabs
	 * Gives A Tab Based Navigation Back And Loads The Templates With A Nice Design
	 * @param $load_template = a key => value array. key = template name, value = Language File contraction
	 * @example 'shop' => 'COM_VIRTUEMART_ADMIN_CFG_SHOPTAB'
	 */
	static function buildTabs ($view, $load_template = array()) {

		vmJsApi::addJScript( 'vmtabs' );
		$html = '<div id="ui-tabs">';
		$i = 1;
		foreach( $load_template as $tab_content => $tab_title ) {
			$html .= '<div id="tab-'.$i.'" class="tabs" title="'.vmText::_( $tab_title ).'">';
			$html .= $view->loadTemplate( $tab_content );
			$html .= '<div class="clear"></div>
			    </div>';
			$i++;
		}
		$html .= '</div>';
		echo $html;
	}


	/**
	 * Checks if Joomla language keys exist and combines it according to existing keys.
	 * @string $pkey : primary string to search for Language key (must have %s in the string to work)
	 * @string $skey : secondary string to search for Language key
	 * @return string
	 * @author Max Milbers
	 * @author Patrick Kohl
	 */
	static function translateTwoLangKeys ($pkey, $skey) {

		$upper = strtoupper( $pkey ).'_2STRINGS';
		if(vmText::_( $upper ) !== $upper) {
			return vmText::sprintf( $upper, vmText::_( $skey ) );
		} else {
			return vmText::_( $pkey ).' '.vmText::_( $skey );
		}
	}

	
	/**
	 * Get Virtuemart itemID from joomla menu
	 * @author Maik K�nnemann
	 */
	static function getMenuItemId( $lang = '*' ) {

		$itemID = '';

		if(empty($lang)) $lang = '*';

		$component	= JComponentHelper::getComponent('com_virtuemart');

		$db = JFactory::getDbo();
		$q = 'SELECT * FROM `#__menu` WHERE `component_id` = "'. $component->id .'" and `language` = "'. $lang .'"';
		$db->setQuery( $q );
		$items = $db->loadObjectList();
		if(empty($items)) {
			$q = 'SELECT * FROM `#__menu` WHERE `component_id` = "'. $component->id .'" and `language` = "*"';
			$db->setQuery( $q );
			$items = $db->loadObjectList();
		}

		foreach ($items as $item) {
			if(strstr($item->link, 'view=virtuemart')) {
				$itemID = $item->id;
				break;
			}
		}

		if(empty($itemID) && !empty($items[0]->id)) {
			$itemID = $items[0]->id;
		}

		return $itemID;
	}

	static function triggerContentPlugin(  &$article, $context, $field) {
	// add content plugin //
		$dispatcher = JDispatcher::getInstance ();
		JPluginHelper::importPlugin ('content');
		$article->text = $article->{$field};

		jimport ('joomla.registry.registry');
		$params = new JRegistry('');
		if (!isset($article->event)) {
			$article->event = new stdClass();
		}
		$results = $dispatcher->trigger ('onContentPrepare', array('com_virtuemart.'.$context, &$article, &$params, 0));
		// More events for 3rd party content plugins
		// This do not disturb actual plugins, because we don't modify $vendor->text
		$res = $dispatcher->trigger ('onContentAfterTitle', array('com_virtuemart.'.$context, &$article, &$params, 0));
		$article->event->afterDisplayTitle = trim (implode ("\n", $res));

		$res = $dispatcher->trigger ('onContentBeforeDisplay', array('com_virtuemart.'.$context, &$article, &$params, 0));
		$article->event->beforeDisplayContent = trim (implode ("\n", $res));

		$res = $dispatcher->trigger ('onContentAfterDisplay', array('com_virtuemart.'.$context, &$article, &$params, 0));
		$article->event->afterDisplayContent = trim (implode ("\n", $res));

		$article->{$field} = $article->text;
	}

	static public function mask_string($cc, $mask_char='X'){
		return str_pad(substr($cc, -4), strlen($cc), $mask_char, STR_PAD_LEFT);
	}

	/*
	 * get The invoice Folder Name
	 * @return the invoice folder name
	 */
	static function getInvoiceFolderName() {
		return   'invoices' ;
	}

	/**
	 * Get the file name for the invoice or deliverynote.
	 * The layout argument currently is either 'invoice' or 'deliverynote'
	 * @return The full filename of the invoice/deliverynote without file extension, sanitized not to contain problematic characters like /
	 */
	static function getInvoiceName($invoice_number, $layout='invoice'){

		$tmpT = false;
		vmLanguage::loadJLang('com_virtuemart_orders', true);
		if(VmConfig::get('invoiceNameInShopLang',true)){
			$tmpT = VmConfig::$vmlangTag;
			vmLanguage::setLanguageByTag(VmConfig::$jDefLangTag);
		}
		$prefix = vmText::_('COM_VIRTUEMART_FILEPREFIX_'.strtoupper($layout));
		if($tmpT!=false){
			vmLanguage::setLanguageByTag($tmpT);
		}
		if($prefix == 'COM_VIRTUEMART_FILEPREFIX_'.strtoupper($layout)){
			$prefix = 'vm'.$layout.'_';
		}
		return $prefix.preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $invoice_number);
	}

	static public function getInvoiceDownloadButton($orderInfo, $descr = 'COM_VIRTUEMART_PRINT', $icon = 'system/pdf_button.png'){
		$html = '';
		if(!empty($orderInfo->invoiceNumber)){
			if(!$sPath = shopFunctions::checkSafePath()){
				return $html;
			}
			$path = $sPath.self::getInvoiceFolderName().DS.self::getInvoiceName($orderInfo->invoiceNumber).'.pdf';
			//$path .= preg_replace('/[^A-Za-z0-9_\-\.]/', '_', 'vm'.$layout.'_'.$orderInfo->invoiceNumber.'.pdf');
			
			if(file_exists( $path )) {
				$link = JURI::root(true).'/index.php?option=com_virtuemart&view=invoice&layout=invoice&format=pdf&tmpl=component&order_number='.$orderInfo->order_number.'&order_pass='.$orderInfo->order_pass;
				$pdf_link = "<a class='button invoice' href=\"javascript:void window.open('".$link."', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');\"  >";
				$pdf_link .= $orderInfo->invoiceNumber.' ';
				$pdf_link .= JHtml::_('image',$icon, vmText::_($descr), NULL, true);
				$pdf_link .= '</a>';
				$html = $pdf_link;
			}
		}
		return $html;
	}

	/*
	 * @author Valerie
	 */
	static function InvoiceNumberReserved ($invoice_number) {

		if (($pos = strpos ($invoice_number, 'reservedByPayment_')) === FALSE) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	static public function renderCaptcha($config = 'reg_captcha',$id = 'dynamic_recaptcha_1'){

		if(VmConfig::get ($config) and JFactory::getUser()->guest==1 ){

			$reCaptchaName = 'recaptcha'; // the name of the captcha plugin - retrieved from the custom component's parameters

			JPluginHelper::importPlugin('captcha', $reCaptchaName); // will load the plugin selected, not all of them - we need to know what plugin's events we need to trigger

			$dispatcher = JEventDispatcher::getInstance();
			$dispatcher->trigger('onInit', $id);
			$output = $dispatcher->trigger('onDisplay', array($reCaptchaName, $id, 'class="g-recaptcha required"'));
			return isset($output[0])? $output[0]:'';
		}
		return '';
	}

	static public function summarizeRulesForBill($order, $payShipment=true){

		$discountsBill = array();
		$taxBill = array();
		//vmdebug('summarizeRulesForBill $taxBill input',$order['calc_rules']);
		foreach($order['items'] as $item){
			//vmdebug('summarizeRulesForBill $item->product_subtotal_with_tax',$item->product_subtotal_with_tax);
			foreach($order['calc_rules'] as $rule){

				//The virtuemart_order_item_id is missing for the payment and shipment rules, these are handled below
				if(isset($rule->virtuemart_order_item_id) and $rule->virtuemart_order_item_id == $item->virtuemart_order_item_id){

					if($rule->calc_kind == 'VatTax' /*or $rule->calc_kind == 'Tax' */){

						$rule->label = shopFunctionsF::getTaxNameWithValue($rule->calc_rule_name,$rule->calc_value);

						if(!isset($taxBill[$rule->virtuemart_calc_id])){
							$taxBill[$rule->virtuemart_calc_id] = clone($rule);
							$taxBill[$rule->virtuemart_calc_id]->calc_amount = 0.0;
							$taxBill[$rule->virtuemart_calc_id]->subTotal = 0.0;
						}

                        $taxBill[$rule->virtuemart_calc_id]->calc_amount += $rule->calc_amount * $item->product_quantity ;
                        //vmdebug('summarizeRulesForBill  $rule->calc_amount after multiplied with quantity = '.$item->product_quantity, $rule->calc_amount);
                        $taxBill[$rule->virtuemart_calc_id]->subTotal += $item->product_subtotal_with_tax;

					}
				} else {
					if($rule->calc_kind == 'DBTaxRulesBill' or $rule->calc_kind == 'DATaxRulesBill'){
						$discountsBill[$rule->virtuemart_calc_id] = $rule;
					} else if($rule->calc_kind == 'taxRulesBill'){
						//vmdebug('summarizeRulesForBill  taxRulesBill ', $rule);

						if(!isset($taxBill[$rule->virtuemart_calc_id])){
							$rule->label = shopFunctionsF::getTaxNameWithValue($rule->calc_rule_name,$rule->calc_value);
							$taxBill[$rule->virtuemart_calc_id] = clone($rule);
							$taxBill[$rule->virtuemart_calc_id]->subTotal = $taxBill[$rule->virtuemart_calc_id]->calc_amount;
						}

					}

				}
			}
		}


        if($payShipment){
			$idWithMax = 0;
			if(VmConfig::get('radicalShipPaymentVat',true)){

				$maxValue = 0.0;
				foreach($order['calc_rules'] as $rule){
					if($rule->calc_kind == 'taxRulesBill' or $rule->calc_kind == 'VatTax'){

						if(empty($idWithMax) or $maxValue<=$taxBill[$rule->virtuemart_calc_id]->subTotal){
							$idWithMax = $rule->virtuemart_calc_id;
							$maxValue = $taxBill[$rule->virtuemart_calc_id]->subTotal;
						}
					}
				}
				//vmdebug('radicalShipPaymentVat my $rule ',$maxValue,$taxBill);
			}

			foreach($order['calc_rules'] as $i=> $rule) {

				if($rule->calc_kind == 'payment' or $rule->calc_kind == 'shipment') {
					$keyN= 'order_'.$rule->calc_kind;
					if(empty($idWithMax)){

                        foreach($taxBill as $tax){
							$sum = $order['details']['BT']->order_salesPrice;
							$t1 = $tax->calc_value * 0.01 * $tax->subTotal/$sum;
							$toAdd = $t1 * $order['details']['BT']->{$keyN} ;
							//vmdebug('ShipPay Rules $t1 '.$tax->calc_value * 0.01.' * '. $tax->subTotal.'/'.$sum.' = '.$t1);
							//vmdebug('ShipPay Rules $toAdd '.$t1.' * '. $order['details']['BT']->$keyN. ' = '.$toAdd. ' on '.$taxBill[$tax->virtuemart_calc_id]->calc_amount);
							$taxBill[$tax->virtuemart_calc_id]->calc_amount += $t1 * $order['details']['BT']->{$keyN} ;

							//vmdebug('ShipPay Rules '.$t1.' * '. $order['details']['BT']->$keyN.'='.$t1 * $order['details']['BT']->$keyN);
                        }

					} else {
						$taxBill[$idWithMax]->calc_amount += $rule->calc_amount ;

					}
				}
			}
        }
		//vmdebug('summarizeRulesForBill $taxBill return',$taxBill);
		return array('discountsBill' => $discountsBill, 'taxBill' => $taxBill);
	}
}
