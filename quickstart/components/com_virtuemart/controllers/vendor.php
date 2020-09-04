<?php
/**
*
* Controller for the front end Manufacturerviews
*
* @package	VirtueMart
* @subpackage User
* @author Oscar van Eijk
* @author Max Milbers
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: manufacturer.php 2420 2010-06-01 21:12:57Z oscar $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

/**
 * VirtueMart Component Controller
 *
 * @package		VirtueMart
 */
class VirtueMartControllerVendor extends JControllerLegacy
{

	/**
	* Send the ask question email.
	* @author Kohl Patrick, Christopher Roussel
	*/
	public function mailAskquestion () {

		vRequest::vmCheckToken();

		$model = VmModel::getModel('vendor');
		$mainframe = JFactory::getApplication();
		$vars = array();
		$min = VmConfig::get('asks_minimum_comment_length', 50)+1;
		$max = VmConfig::get('asks_maximum_comment_length', 2000)-1 ;
		$commentSize = vRequest::getString ('comment');
		if (function_exists('mb_strlen')) {
			$commentSize =  mb_strlen($commentSize);
		} else {
			$commentSize =  strlen($commentSize);
		}

		$validMail = filter_var(vRequest::getVar('email'), FILTER_VALIDATE_EMAIL);

		$virtuemart_vendor_id = vRequest::getInt('virtuemart_vendor_id',1);

		$userId = VirtueMartModelVendor::getUserIdByVendorId($virtuemart_vendor_id);

		//$vendorUser = JFactory::getUser($userId);

		if ( $commentSize<$min || $commentSize>$max || !$validMail ) {
			$this->setRedirect(JRoute::_ ( 'index.php?option=com_virtuemart&view=vendor&task=contact&virtuemart_vendor_id=' . $virtuemart_vendor_id , FALSE),vmText::_('COM_VIRTUEMART_COMMENT_NOT_VALID_JS'));
			return ;
		}

		$user = JFactory::getUser();

		if($user->guest==1) {
			if(!$this->checkCaptcha( 'index.php?option=com_virtuemart&view=vendor&layout=contact&virtuemart_vendor_id=1' ) ){
				return ;
			}
		}

		$fromMail = vRequest::getVar('email');	//is sanitized then
		$fromName = vRequest::getVar('name','');//is sanitized then
		$fromMail = str_replace(array('\'','"',',','%','*','/','\\','?','^','`','{','}','|','~'),array(''),$fromMail);
		$fromName = str_replace(array('\'','"',',','%','*','/','\\','?','^','`','{','}','|','~'),array(''),$fromName);
		if (!empty($user->id)) {
			if(empty($fromMail)){
				$fromMail = $user->email;
			}
			if(empty($fromName)){
				$fromName = $user->name;
			}
		}

		$vars['user'] = array('name' => $fromName, 'email' => $fromMail);

		$VendorEmail = $model->getVendorEmail($virtuemart_vendor_id);
		$vars['vendor'] = array('vendor_store_name' => $fromName );

		if (shopFunctionsF::renderMail('vendor', $VendorEmail, $vars,'vendor')) {
			$string = 'COM_VIRTUEMART_MAIL_SEND_SUCCESSFULLY';
		}
		else {
			$string = 'COM_VIRTUEMART_MAIL_NOT_SEND_SUCCESSFULLY';
		}
		$mainframe->enqueueMessage(vmText::_($string));

		// Display it all
		$view = $this->getView('vendor', 'html');

		$view->setLayout('mail_confirmed');
		$view->display();
	}

	function checkCaptcha($retUrl){

		if(JFactory::getUser()->guest==1 and VmConfig::get ('ask_captcha')){

			$filled = vRequest::getVar ('g-recaptcha-response',false);
			if(!$filled){
				vmInfo('COM_VM_FILL_CAPTCHA');
				return false;
			}

			$recaptcha = vRequest::getVar ('recaptcha_response_field');
			JPluginHelper::importPlugin('captcha');
			$dispatcher = JDispatcher::getInstance();
			$res = $dispatcher->trigger('onCheckAnswer',$recaptcha);
			if(!$res[0]){
				$errmsg = vmText::_('PLG_RECAPTCHA_ERROR_INCORRECT_CAPTCHA_SOL');
				$this->setRedirect (JRoute::_ ($retUrl . '&captcha=1', FALSE), $errmsg);
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			return TRUE;
		}
	}

}

// No closing tag
