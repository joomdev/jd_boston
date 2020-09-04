<?php
/**
*
* User controller
*
* @package	VirtueMart
* @subpackage User
* @author VirtueMart Team
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2018 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: user.php 10305 2020-04-29 08:28:18Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * Controller class for the user
 *
 * @package    	VirtueMart
 * @subpackage 	User
 * @author     	Oscar van Eijk
 * @author 		Max Milbers
 */
class VirtuemartControllerUser extends VmController {

	/**
	 * Method to display the view
	 *
	 * @access public
	 * @author
	 */
	function __construct(){

		parent::__construct('virtuemart_user_id');
	}

	/**
	 * Handle the edit task
	 */
	function edit($view=0){

		//We set here the virtuemart_user_id, when no virtuemart_user_id is set to 0, for adding a new user
		//In every other case the virtuemart_user_id is sent.
		$cid = vRequest::getInt('virtuemart_user_id',vRequest::getInt('cid',0));
		vRequest::setVar('virtuemart_user_id', $cid);

		parent::edit('edit');
	}

	function addST(){

		$this->edit();
	}

	function removeAddressST(){

		vRequest::vmCheckToken();
		$virtuemart_userinfo_id = vRequest::getInt('virtuemart_userinfo_id');
		$virtuemart_user_id = vRequest::getInt('virtuemart_user_id');

		//Lets do it dirty for now
		$userModel = VmModel::getModel('user');
		vmdebug('removeAddressST',$virtuemart_user_id,$virtuemart_userinfo_id);
		$userModel->setId($virtuemart_user_id[0]);
		$userModel->removeAddress($virtuemart_userinfo_id);

		$this->setRedirect( 'index.php?option=com_virtuemart&view=user&task=edit&virtuemart_user_id[]='.$virtuemart_user_id[0] );
	}

	function editshop(){

		$user = JFactory::getUser();
		//the virtuemart_user_id var gets overriden in the edit function, when not set. So we must set it here
		vRequest::setVar('virtuemart_user_id', (int)$user->id);
		$this->edit();

	}
	function cancel(){

		$lastTask = vRequest::getCmd('last_task');
		if ($lastTask == 'edit_shop') $this->setRedirect('index.php?option=com_virtuemart');
		else $this->setRedirect('index.php?option=com_virtuemart&view=user');
	}

	/**
	 * Handle the save task
	 * Checks already in the controller the rights todo so and sets the data by filtering the post
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){
		vRequest::vmCheckToken();

		if (!vmAccess::manager('user.edit')) {
			$msg = vmText::_('_NOT_AUTH');
		} else {
			$model = VmModel::getModel('user');

			if($data===0) $data = vRequest::getRequest();

			$this->getStrByAcl(array('vendor_store_name','vendor_store_desc','vendor_terms_of_service','vendor_legal_info', 'vendor_letter_css', 'vendor_letter_header_html', 'vendor_letter_footer_html', 'vendor_mail_free1','vendor_mail_free2'),$data);

			$ids = vRequest::getInt('virtuemart_user_id');

			if($ids){
				if(is_array($ids) and isset($ids[0])){
					$model->setId((int)$ids[0]);
					//vmdebug('my user controller set '.(int)$ids[0],$ids);
				} else{
					$model->setId((int)$ids);
					//vmdebug('my user controller set '.(int)$ids,$ids);
				}
			}
			$ret=$model->store($data);
			if(!$ret){
				$msg = '';
			} else {
				$msg = $ret['message'];
			}

			if(!isset($data['virtuemart_shoppergroup_id'])){
				$data['virtuemart_shoppergroup_id'] = array();
			}
		}
		$cmd = vRequest::getCmd('task');
		$lastTask = vRequest::getCmd('last_task');
		if($cmd == 'apply'){
			if ($lastTask == 'editshop') $redirection = 'index.php?option=com_virtuemart&view=user&task=editshop';
			else $redirection = 'index.php?option=com_virtuemart&view=user&task=edit&virtuemart_user_id[]='.$ret['newId'];
		} else {
			if ($lastTask == 'editshop') $redirection = 'index.php?option=com_virtuemart';
			else $redirection = 'index.php?option=com_virtuemart&view=user';
		}
// 		$this->setRedirect($redirection, $ret['message']);
		$this->setRedirect($redirection);
	}


}

//No Closing tag
