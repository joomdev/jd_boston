<?php
/**
 *
 * Controller for the front end Orderviews
 *
 * @package	VirtueMart
 * @subpackage User
 * @author Oscar van Eijk
 * @author Max Milbers
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2019 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access for invoices');


// Load the controller framework
jimport('joomla.application.component.controller');

/**
 * VirtueMart Component Controller
 *
 * @package		VirtueMart
 */
class VirtueMartControllerInvoice extends JControllerLegacy
{

	var $unlockInvoice = 0;

	public function __construct()
	{
		parent::__construct();
		$this->useSSL = vmURI::useSSL();
		$this->useXHTML = false;
	}

	/**
	 * Override of display to prevent caching
	 *
	 * @return  JController  A JController object to support chaining.
	 */
	public function display($cachable = false, $urlparams = false)  {
		$format = vRequest::getCmd('format','html');
		$layout = vRequest::getCmd('layout', 'invoice');

		if ($format != 'pdf') {
			$viewName='invoice';

			$view = $this->getViewWithTemplate($viewName, $format);
			//$view = $this->getView($viewName, $format);
			$view->headFooter = true;
			$view->display();
		} else {
			//PDF needs more RAM than usual
			VmConfig::ensureMemoryLimit(96);

			//PDF needs xhtml links
			$this->useXHTML = true;

			$app = JFactory::getApplication();
			// Create the invoice PDF file on disk and send that back
			$orderDetails = $this->getOrderDetails();

			if(!$orderDetails){
				$app->redirect(JRoute::_('index.php?option=com_virtuemart'));
			}

			if($orderDetails['details']['BT']->invoice_locked and $this->unlockInvoice){
				$orderDetails['details']['BT']->invoice_locked = 0;
			}

			$fileLocation = $this->getInvoicePDF($orderDetails, 'invoice',$layout);
			if(!$fileLocation){
				$app->redirect(JRoute::_('index.php?option=com_virtuemart'),'Invoice not created');
			}

			$fileName = basename ($fileLocation);

			if (file_exists ($fileLocation)) {
				$maxSpeed = 200;
				$range = 0;
				$size = filesize ($fileLocation);
				$contentType = 'application/pdf';
				header ("Cache-Control: public");
				header ("Content-Transfer-Encoding: binary\n");
				header ('Content-Type: application/pdf');

				$contentDisposition = 'attachment';

				$agent = strtolower ($_SERVER['HTTP_USER_AGENT']);

				if (strpos ($agent, 'msie') !== FALSE) {
					$fileName = preg_replace ('/\./', '%2e', $fileName, substr_count ($fileName, '.') - 1);
				}

				header ("Content-Disposition: $contentDisposition; filename=\"$fileName\"");

				header ("Accept-Ranges: bytes");

				if (isset($_SERVER['HTTP_RANGE'])) {
					list($a, $range) = explode ("=", $_SERVER['HTTP_RANGE']);
					str_replace ($range, "-", $range);
					$size2 = $size - 1;
					$new_length = $size - $range;
					header ("HTTP/1.1 206 Partial Content");
					header ("Content-Length: $new_length");
					header ("Content-Range: bytes $range$size2/$size");
				}
				else {
					$size2 = $size - 1;
					header ("Content-Range: bytes 0-$size2/$size");
					header ("Content-Length: " . $size);
				}

				if ($size == 0) {
					die('Zero byte file! Aborting download');
				}

				//	set_magic_quotes_runtime(0);
				$fp = fopen ("$fileLocation", "rb");
				fseek ($fp, $range);

				while (!feof ($fp) and (connection_status () == 0)) {
					set_time_limit (0);
					print(fread ($fp, 1024 * $maxSpeed));
					flush ();
					ob_flush ();
					sleep (1);
				}
				fclose ($fp);

				$app->close();

			} else {
				// TODO: Error message
				// vmError("File $fileName not found!");
			}
		}
	}

	public function getOrderDetails() {

		$orderModel = VmModel::getModel('orders');

		return $orderModel->getMyOrderDetails(0,false,false,true);
	}

	public function samplePDF() {

		vmDefines::tcpdf();
		if(!class_exists('VmVendorPDF')){
			VmLanguage::loadJLang('com_virtuemart_config');
			vmError('COM_VIRTUEMART_TCPDF_NINSTALLED','COM_VIRTUEMART_TCPDF_NINSTALLED');
			return 0;
		}

		$pdf = new VmVendorPDF();
		$pdf->AddPage();
		$pdf->PrintContents(vmText::_('COM_VIRTUEMART_PDF_SAMPLEPAGE'));
		$pdf->Output("vminvoice_sample.pdf", 'I');
		JFactory::getApplication()->close();
	}

	function getViewWithTemplate($viewName, $format){

		$this->addViewPath( VMPATH_SITE .'/views' );
		$view = $this->getView($viewName, $format);
		$this->writeJs = false;
		$view->addTemplatePath( VMPATH_SITE .'/views/'. $viewName .'/tmpl' );

		$template = VmTemplate::loadVmTemplateStyle();
		$templateName = VmTemplate::setTemplate($template);

		if(!empty($templateName) and VmConfig::get('useLayoutOverrides',1)){
			$TemplateOverrideFolder = VMPATH_ROOT .'/templates/'.$templateName.'/html/com_virtuemart/invoice';
			if(file_exists($TemplateOverrideFolder)){
				$view->addTemplatePath( $TemplateOverrideFolder);
			}
		}
		return $view;
	}

	function getInvoicePDF($orderDetails, $viewName='invoice', $layout='invoice', $format='html', $force = false){

		vmdebug('getInvoicePDF start');
		vmLanguage::loadJLang('com_virtuemart',1);

		$path = VirtueMartModelInvoice::getInvoicePath();
		if(!$path){
			vmdebug('getInvoicePDF path missing');
			return false;
		}
		$invM = VmModel::getModel('invoice');

		$invoiceNumber = vRequest::getString('invoiceNumber',false);
		$invoiceDate = '';
		if($invoiceNumber) {
			vmdebug('getInvoicePDF by invoice number');
			$inv = $invM->getInvoiceEntry($invoiceNumber, true, '*', 'invoice_number');
			if($inv and !empty($inv['invoice_number'])){
				$invoiceDate = $inv['created_on'];
			} else {
				vmError('No Invoice found for $invoiceNumber '.$invoiceNumber);
				return false;
			}
		}

		if(empty($invoiceNumber)){

			$inv = $invM->getExistingIfUnlockedCreateNewInvoiceNumber($orderDetails['details']['BT'], $invoiceNumber);

			if(!empty($inv[0])){
				$invoiceNumber = $inv[0];
				$invoiceDate = $inv[1];
			} else {
				$invoiceNumber = FALSE;
				vmdebug('getInvoicePDF Cant create pdf, no entry for ',$inv);
				$r = 'getInvoicePDF Cant create pdf, no entry';
				vmError($r , $r.' for '.$inv);
				return false;
			}
		}

		if(!$invoiceNumber or empty($invoiceNumber)){
			$r = 'getInvoicePDF Cant create pdf, no entry for '.$invoiceNumber;
			vmError($r, $r);
			return 0;
		}
		if (shopFunctionsF::InvoiceNumberReserved($invoiceNumber)) {
			vmdebug('getInvoicePDF InvoiceNumberReserved ',$invoiceNumber);
			return 0;
		}

		//$path .= preg_replace('/[^A-Za-z0-9_\-\.]/', '_', 'vm'.$layout.'_'.$invoiceNumber.'.pdf');
		$path .= shopFunctionsF::getInvoiceName($invoiceNumber, $layout).'.pdf';

		if(file_exists($path) and !$force){

			return $path;
		}

		$view = $this->getViewWithTemplate($viewName, $format);

		$view->invoiceNumber = $invoiceNumber;
		$view->invoiceDate = $invoiceDate;

		$view->orderDetails = $orderDetails;
		$view->uselayout = $layout;
		$view->showHeaderFooter = false;

		$vendorModel = VmModel::getModel('vendor');
		$virtuemart_vendor_id = empty($orderDetails['details']['BT']->virtuemart_vendor_id)? 1:$orderDetails['details']['BT']->virtuemart_vendor_id;
		$vendor = $vendorModel->getVendor($virtuemart_vendor_id);

		$metadata = array (
			'title' => vmText::sprintf('COM_VIRTUEMART_INVOICE_TITLE',
				$vendor->vendor_store_name, $view->invoiceNumber,
				$orderDetails['details']['BT']->order_number),
			'keywords' => vmText::_('COM_VIRTUEMART_INVOICE_CREATOR'));

		vmDefines::tcpdf();

		return VmPdf::createVmPdf($view, $path, 'F', $metadata);
	}
}

// No closing tag
