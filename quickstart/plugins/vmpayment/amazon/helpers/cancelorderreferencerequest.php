<?php

defined('_JEXEC') or die('Direct Access to ' . basename(__FILE__) . 'is not allowed.');

/**
 *
 * @package    VirtueMart
 * @subpackage vmpayment
 * @version $Id:$
 * @author Valérie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - March 11 2016 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 */
class amazonHelperCancelOrderReferenceRequest extends amazonHelper {

	public function __construct (OffAmazonPaymentsService_Model_CancelOrderReferenceRequest $captureRequest, $method) {
		parent::__construct($captureRequest, $method);
	}



	function getContents () {

		$contents = $this->tableStart("CancelOrderReferenceRequest");
		$contents .= $this->getRow("Dump: ", var_export($this->amazonData, true));

		$contents .= $this->tableEnd();

		return $contents;
	}


}